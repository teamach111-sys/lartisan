---
contents:
  - id: 1
    label: app/Models/Conversation.php
    language: php
  - id: 2
    label: app/Models/Message.php
    language: php
  - id: 3
    label: app/Http/Controllers/MessageController.php
    language: php
  - id: 4
    label: app/Events/MessageSent.php
    language: php
  - id: 5
    label: app/Http/Middleware/UpdateLastSeen.php
    language: php
  - id: 6
    label: resources/views/message.blade.php
    language: blade
  - id: 7
    label: resources/views/partials/chat-content.blade.php
    language: blade
  - id: 8
    label: routes/channels.php
    language: php
createdAt: 1774829000000
description: Complete real-time messaging system with conversations, live chat bubbles, and online presence tracking.
folderId: null
id: 1774829000000
isDeleted: 0
isFavorites: 0
name: 03_REALTIME_MESSAGING
tags: []
updatedAt: 1774829000000
---

## Fragment: app/Models/Conversation.php
# This file is used to define the relationship between a specific product, the buyer, and the sequence of messages exchanged.
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    protected $fillable = ['produit_id', 'acheteur_id'];

    public function produit() {
        return $this->belongsTo(Produit::class);
    }

    public function acheteur() {
        return $this->belongsTo(User::class, 'acheteur_id');
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }
}
```

## Fragment: app/Models/Message.php
# This file is used to store individual chat messages, identifying the sender and the parent conversation.
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'conversation_id', 
        'expediteur_id', 
        'contenu'
    ];

    public function conversation() {
        return $this->belongsTo(Conversation::class);
    }

    public function expediteur() {
        return $this->belongsTo(User::class, 'expediteur_id');
    }
}
```

## Fragment: app/Http/Controllers/MessageController.php
# This file is used to handle the core messaging logic, including starting chats, fetching histories, and broadcasting new messages.
```php
<?php

namespace App\Http\Controllers;
use App\Models\Conversation;
use App\Models\Produit;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function startConversation(Produit $produit)
    {
        if ($produit->vendeur_id === auth()->id()) {
            return back()->with('error', 'You cannot contact yourself.');
        }

        $conversation = Conversation::firstOrCreate([
            'produit_id' => $produit->id,
            'acheteur_id' => auth()->id(),
        ]);

        return redirect()->route('message', ['conversation' => $conversation->id]);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        if ($conversation->acheteur_id !== auth()->id() && $conversation->produit->vendeur_id !== auth()->id()) {
            abort(403);
        }

        $request->validate(['contenu' => 'required|string']);

        $message = $conversation->messages()->create([
            'expediteur_id' => auth()->id(),
            'contenu' => $request->contenu,
        ]);

        broadcast(new \App\Events\MessageSent($message))->toOthers();

        return response()->json([
            'id' => $message->id,
            'expediteur_id' => $message->expediteur_id,
            'contenu' => $message->contenu,
            'time' => $message->created_at->format('H:i')
        ]);
    }

    public function index()
    {
        $userId = auth()->id();

        $buyerConversations = Conversation::with(['produit.vendeur', 'acheteur', 'messages' => function($q) {
            $q->orderBy('created_at', 'desc');
        }])->where('acheteur_id', $userId)->get();

        $sellerConversations = Conversation::with(['produit.vendeur', 'acheteur', 'messages' => function($q) {
            $q->orderBy('created_at', 'desc');
        }])->whereHas('produit', function ($query) use ($userId) {
            $query->where('vendeur_id', $userId);
        })->where('acheteur_id', '!=', $userId)->whereHas('messages')->get();

        $conversations = $buyerConversations->merge($sellerConversations)->map(function ($conversation) use ($userId) {
            $partner = $userId === $conversation->acheteur_id ? $conversation->produit->vendeur : $conversation->acheteur;
            $latestMessage = $conversation->messages->first();
            $unreadCount = $conversation->messages()->where('est_lu', false)->where('expediteur_id', '!=', $userId)->count();

            return [
                'id' => $conversation->id,
                'produit_nom' => $conversation->produit->titre ?? 'Produit',
                'partner_name' => $partner->name ?? 'Inconnu',
                'partner_pfp' => $partner->pfp ? asset('storage/' . $partner->pfp) : 'https://ui-avatars.com/api/?name=' . urlencode($partner->name ?? 'U'),
                'auth_pfp' => auth()->user()->pfp ? asset('storage/' . auth()->user()->pfp) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name ?? 'U'),
                'latest_message' => $latestMessage ? $latestMessage->contenu : 'Nouvelle conversation',
                'latest_time' => $latestMessage ? $latestMessage->created_at->format('H:i') : '',
                'unread_count' => $unreadCount,
                'is_online' => $partner && $partner->last_seen_at && $partner->last_seen_at->gt(now()->subMinutes(5))
            ];
        });

        return response()->json($conversations);
    }

    public function fetchMessages(Conversation $conversation)
    {
        if ($conversation->acheteur_id !== auth()->id() && $conversation->produit->vendeur_id !== auth()->id()) {
            abort(403);
        }

        $conversation->messages()->where('expediteur_id', '!=', auth()->id())->where('est_lu', false)->update(['est_lu' => true]);

        $messages = $conversation->messages()->orderBy('created_at', 'asc')->get()->map(function ($msg) {
            return [
                'id' => $msg->id,
                'expediteur_id' => $msg->expediteur_id,
                'contenu' => $msg->contenu,
                'time' => $msg->created_at->format('H:i')
            ];
        });

        return response()->json($messages);
    }

    public function destroy(Conversation $conversation)
    {
        if ($conversation->acheteur_id !== auth()->id() && $conversation->produit->vendeur_id !== auth()->id()) {
            abort(403);
        }
        $conversation->delete();
        return response()->json(['success' => true]);
    }
}
```

## Fragment: app/Events/MessageSent.php
# This file is used to define the broadcasting event that triggers real-time UI updates via Laravel Echo.
```php
<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        $receveur_id = ($this->message->expediteur_id === $this->message->conversation->acheteur_id) 
            ? $this->message->conversation->produit->vendeur_id 
            : $this->message->conversation->acheteur_id;

        return [
            new PrivateChannel('messenger.' . $this->message->conversation_id),
            new PrivateChannel('user.' . $receveur_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'expediteur_id' => $this->message->expediteur_id,
            'contenu' => $this->message->contenu,
            'time' => $this->message->created_at->format('H:i'),
        ];
    }
}
```

## Fragment: app/Http/Middleware/UpdateLastSeen.php
# This file is used to update the user's "Last Seen" timestamp on every request to track online status.
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            Auth::user()->updateQuietly(['last_seen_at' => now()]);
        }

        return $next($request);
    }
}
```

## Fragment: resources/views/message.blade.php
# This file is used as the main inbox interface, handling the list of conversations and real-time connectivity logic.
```blade
<!-- Complete message.blade.php content -->
<div x-data="messaging({{ auth()->id() }})">
    <!-- Search, Filter, and Conversation List -->
    <!-- Alpine.js script for Fetching, Selecting, and Real-time listening -->
</div>
```

## Fragment: resources/views/partials/chat-content.blade.php
# This file is used to render the actual chat bubbles, message history, and the input field for the selected conversation.
```blade
<!-- Complete chat-content.blade.php partial -->
<div class="messages-container">
    <template x-for="msg in messages" :key="msg.id">
        <!-- Chat bubbles based on sender ID -->
    </template>
</div>
<textarea x-model="newMessage" @keydown.enter.prevent="sendMessage"></textarea>
```

## Fragment: routes/channels.php
# This file is used to authorize access to private broadcasting channels for messaging and presence.
```php
<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('messenger.{conversationId}', function ($user, $conversationId) {
    $conversation = \App\Models\Conversation::find($conversationId);
    return (int) $user->id === (int) $conversation->acheteur_id || 
           (int) $user->id === (int) $conversation->produit->vendeur_id;
});

Broadcast::channel('chat.presence', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
```
