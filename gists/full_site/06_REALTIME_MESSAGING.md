---
description: L'Artisan Marketplace - Real-time Messaging Subsystem
---

# Real-time Messaging

This module implements a persistent, unified chat center where buyers and sellers can discuss specific artisanal products in real-time.

## 1. Messaging Controller
### `app/Http/Controllers/MessageController.php`
The core engine for starting conversations, fetching message history, and managing real-time broadasts.

```php
public function sendMessage(Request $request, Conversation $conversation)
{
    $request->validate(['contenu' => 'required|string']);

    // Create message record
    $message = $conversation->messages()->create([
        'expediteur_id' => auth()->id(),
        'contenu' => $request->contenu,
    ]);

    // Broadcast event for real-time delivery
    broadcast(new \App\Events\MessageSent($message))->toOthers();

    return response()->json($message);
}
```

## 2. Frontend Polling & State Management
### `resources/views/message.blade.php@AlpineChat`
Since WebSockets/Echo might not be available on all shared hosts, the app uses **Auto-Polling** built into Alpine.js. It continually checks the server for new messages every few seconds.

```javascript
Alpine.data('messaging', (myId) => ({
    conversations: [],
    currentConversation: null,
    messages: [],
    pollTimer: null,

    init() {
        this.fetchConversations();
    },

    // Polling mechanism
    startPolling(conversationId) {
        if (this.pollTimer) clearInterval(this.pollTimer);
        this.pollTimer = setInterval(async () => {
            if (!this.currentConversation || this.currentConversation.id !== conversationId) {
                clearInterval(this.pollTimer);
                return;
            }
            try {
                // Fetch latest messages from API
                const res = await axios.get(`/api/conversations/${conversationId}/messages`);
                const newMessages = res.data.messages || [];
                
                if (newMessages.length > this.messages.length) {
                    this.messages = newMessages; // Update UI state
                    this.scrollToBottom();
                }
            } catch (e) { /* Fail silently to prevent console spam */ }
        }, 3000); // Checks every 3 seconds
    }
}));
```

## 3. Trusted Interface
### `resources/views/partials/chat-content.blade.php`
Responsive chat template with Alpine.js bindings, unread indicators, and blocking status.

```html
<div class="messages-container p-6 overflow-y-auto">
    <template x-for="msg in messages" :key="msg.id">
        <div class="flex items-end gap-3" :class="msg.expediteur_id == authId ? 'flex-row-reverse' : 'flex-row'">
            <!-- Message Balloon -->
            <div class="p-4 rounded-2xl shadow-sm" :class="...colors">
                <p x-text="msg.contenu"></p>
                <p class="text-[10px] font-bold" x-text="msg.time"></p>
            </div>
        </div>
    </template>
</div>
```

## 4. Safety: Blocking System
Allows users to terminate communication with specific individuals.

```php
public function toggleBlock(User $user)
{
    auth()->user()->blockedUsers()->toggle($user->id);
    return response()->json(['is_blocked' => auth()->user()->hasBlocked($user->id)]);
}
```
