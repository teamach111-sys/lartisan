---
description: L'Artisan Marketplace - Production Messaging System Hardening & Real-Time Polling
---

# Production Messaging Infrastructure Overhaul

This document logs the complete set of changes required to make the messaging system functional and near-real-time on Laravel Cloud without WebSocket infrastructure.

## 1. CSRF Token Injection for Axios (Critical Production Fix)

All `/api/*` routes are defined in `web.php` with the `auth` middleware, meaning they pass through Laravel's CSRF verification layer. Without attaching the CSRF token to axios headers, every AJAX call returns a **419 Token Mismatch** on production.

### `resources/js/bootstrap.js`
```javascript
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Read the CSRF token from the <meta> tag and attach it to every request.
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}
```

## 2. Server-Side Conversation Pre-Loading (Bypasses AJAX Dependency)

When a user clicks "Contacter l'artisan", the `startConversation` POST creates a conversation and redirects to `/message?conversation=X`. Previously, the JS had to make an AJAX call to `/api/conversations` to find this conversation — which failed on Cloud due to CSRF/session issues.

The fix: pre-load the conversation data directly in the Blade view via the route closure. Zero AJAX dependency for the initial contact flow.

### `routes/web.php`
```php
Route::get('/message', function () {
    $preloadedConversation = null;
    $convId = request()->query('conversation');
    
    if ($convId) {
        $conversation = \App\Models\Conversation::with(['produit.vendeur', 'acheteur'])->find($convId);
        
        if ($conversation) {
            $userId = auth()->id();
            if ($conversation->acheteur_id === $userId || ($conversation->produit && $conversation->produit->vendeur_id === $userId)) {
                $partner = $userId === $conversation->acheteur_id
                    ? $conversation->produit?->vendeur
                    : $conversation->acheteur;

                if ($partner) {
                    $latestMessage = $conversation->messages()->orderBy('created_at', 'desc')->first();
                    
                    $preloadedConversation = [
                        'id'             => $conversation->id,
                        'produit_id'     => $conversation->produit_id,
                        'produit_nom'    => $conversation->produit?->titre ?? 'Produit',
                        'produit_slug'   => $conversation->produit?->slug ?? '',
                        'partner_id'     => $partner->id,
                        'partner_name'   => $partner->name ?? 'Inconnu',
                        'partner_pfp'    => $partner->pfp ? asset('storage/' . $partner->pfp) : 'https://ui-avatars.com/api/?name=' . urlencode($partner->name ?? 'U'),
                        'auth_pfp'       => auth()->user()->pfp ? asset('storage/' . auth()->user()->pfp) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name ?? 'U'),
                        'latest_message' => $latestMessage ? $latestMessage->contenu : 'Nouvelle conversation',
                        'latest_time'    => ($latestMessage && $latestMessage->created_at) ? $latestMessage->created_at->format('H:i') : '',
                        'unread_count'   => 0,
                        'is_online'      => false,
                        'is_blocked'     => auth()->user()->hasBlocked($partner->id),
                        'blocked_by'     => auth()->user()->isBlockedBy($partner->id),
                        'sort_time'      => time(),
                    ];
                }
            }
        }
    }

    return view('message', [
        'auth_user' => auth()->user(),
        'preloaded_conversation' => $preloadedConversation,
    ]);
})->name('message')->middleware('auth');
```

## 3. Alpine.js Pre-loaded Conversation Injection

The Alpine component receives the preloaded conversation via Blade and injects it into the conversations array if the AJAX call didn't return it.

### `resources/views/message.blade.php` (Alpine data)
```javascript
preloadedConv: @json($preloaded_conversation),

// Inside fetchConversations(), after the AJAX call:
if (this.preloadedConv) {
    const exists = this.conversations.find(c => c.id === this.preloadedConv.id);
    if (!exists) {
        this.conversations.unshift(this.preloadedConv);
    }
}
```

## 4. Broadcasting Try/Catch (Prevents 500 on Send)

The `broadcast()` call in `sendMessage` was crashing with a 500 when Pusher/Reverb keys were missing on Cloud. Now wrapped in try/catch so the message saves regardless.

### `app/Http/Controllers/MessageController.php`
```php
$message = $conversation->messages()->create([
    'expediteur_id' => auth()->id(),
    'contenu' => $request->contenu,
]);

try {
    broadcast(new \App\Events\MessageSent($message))->toOthers();
} catch (\Throwable $e) {
    \Illuminate\Support\Facades\Log::warning('Broadcasting failed: ' . $e->getMessage());
}
```

## 5. Echo Switch from Reverb to Pusher

Reverb requires a self-hosted WebSocket server process. Pusher is cloud-hosted and works out of the box.

### `resources/js/echo.js`
```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

try {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'eu',
        forceTLS: true,
    });
} catch (error) {
    console.warn("Echo failed to initialize:", error);
}
```

### `.env` / Laravel Cloud Environment Variables
```
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=<your_id>
PUSHER_APP_KEY=<your_key>
PUSHER_APP_SECRET=<your_secret>
PUSHER_APP_CLUSTER=eu
VITE_PUSHER_APP_KEY=<your_key>
VITE_PUSHER_APP_CLUSTER=eu
```

## 6. Auto-Polling Fallback for Near-Real-Time

Even without WebSockets configured, messages now appear automatically via a 3-second polling interval. When a conversation is selected, a `setInterval` timer queries for new messages continuously.

### `resources/views/message.blade.php` (Polling Logic)
```javascript
pollTimer: null,

selectConversation(conversation) {
    // ... existing logic ...
    this.fetchMessages(conversation.id);
    this.startPolling(conversation.id);
},

startPolling(conversationId) {
    if (this.pollTimer) clearInterval(this.pollTimer);
    this.pollTimer = setInterval(async () => {
        if (!this.currentConversation || this.currentConversation.id !== conversationId) {
            clearInterval(this.pollTimer);
            return;
        }
        try {
            const res = await axios.get(`/api/conversations/${conversationId}/messages`);
            const newMessages = res.data.messages || [];
            if (newMessages.length > this.messages.length) {
                this.messages = newMessages;
                this.scrollToBottom();
                const conv = this.conversations.find(c => c.id === conversationId);
                if (conv && newMessages.length > 0) {
                    const last = newMessages[newMessages.length - 1];
                    conv.latest_message = last.contenu;
                    conv.latest_time = last.time;
                }
            }
        } catch (e) { /* silent */ }
    }, 3000);
},
```

## 8. Profile Picture & Header Layout Fixes (Egg-Shape Fix)

On mobile or small screens, long product titles could cause the profile pictures to be squeezed into "egg" shapes. We fixed this by adding `flex-shrink-0` and `aspect-square` to all PFP images and ensuring the text container is `min-w-0 flex-1`.

### `resources/views/partials/chat-content.blade.php` (Header)
```html
<div class="p-4 min-h-20 border-b border-black/5 flex items-center justify-between ... flex-shrink-0">
    <div class="flex items-center gap-4 min-w-0">
        <div class="relative flex-shrink-0">
            <img class="h-12 w-12 md:h-14 md:w-14 object-cover rounded-full ... flex-shrink-0 aspect-square"
                :src="currentConversation?.partner_pfp">
        </div>
        <div class="flex-1 min-w-0">
            <h2 class="font-bold truncate" x-text="currentConversation?.partner_name"></h2>
            <a class="line-clamp-1 truncate" x-text="'Article: ' + currentConversation?.produit_nom"></a>
        </div>
    </div>
</div>
```

### `resources/views/message.blade.php` (Sidebar)
```html
<div class="relative flex-shrink-0">
    <img class="h-14 w-14 md:h-16 md:w-16 object-cover rounded-full ... flex-shrink-0 aspect-square"
        :src="conv.partner_pfp">
</div>
```

## 9. Final Polish: Active State Colors

Ensured the active conversation in the sidebar uses the brand orange (`#FF8E72`) for the name to provide clear visual feedback.

```html
<h2 class="font-bold text-base truncate text-black" 
    :class="currentConversation?.id === conv.id ? 'text-[#FF8E72]' : ''" 
    x-text="conv.partner_name"></h2>
```
