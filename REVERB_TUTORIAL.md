# 🚀 Guide: Real-Time Messaging with Laravel Reverb & AlpineJS

This guide will teach you how to build a modern, real-time messaging system from scratch using **Laravel 11**, **Reverb**, and **AlpineJS**. 

---

## 🏗️ Step 1: Backend Installation

### 1. Install Reverb
Run the following command in your project root. This will install the package and set up the default `broadcasting.php` and `channels.php` files.
```bash
composer require laravel/reverb
php artisan reverb:install
```

### 2. Configure Environment `.env`
Ensure your broadcasting driver is set to `reverb` and check your app keys.
```ini
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=your_id
REVERB_APP_KEY=your_key
REVERB_APP_SECRET=your_secret
REVERB_HOST="127.0.0.1"
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

---

## 📡 Step 2: Events and Broadcasting

### 1. Create the `MessageSent` Event
Generate an event that tells Laravel what to broadcast.
```bash
php artisan make:event MessageSent
```

Update `app/Events/MessageSent.php`:
```php
class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message) {
        $this->message = $message;
    }

    public function broadcastOn(): array {
        // Change to PrivateChannel for security, Channel for public testing
        return [new PrivateChannel('messenger.' . $this->message->conversation_id)];
    }

    public function broadcastAs(): string {
        return 'message.sent';
    }

    public function broadcastWith(): array {
        return [
            'id' => $this->message->id,
            'expediteur_id' => $this->message->expediteur_id,
            'contenu' => $this->message->contenu,
            'time' => $this->message->created_at->format('H:i'),
        ];
    }
}
```

### 2. Authorize the Channel
In `routes/channels.php`, define who can listen to this private channel:
```php
Broadcast::channel('messenger.{conversationId}', function ($user, $conversationId) {
    // Check if user is part of the conversation (acheteur or vendeur)
    return (int) $user->id === (int) $conversation->acheteur_id 
        || (int) $user->id === (int) $conversation->vendeur_id;
});
```

### 3. Dispatch in Controller
In your `sendMessage` method, use the `broadcast()` helper:
```php
public function sendMessage(Request $request, Conversation $conversation) {
    $message = $conversation->messages()->create([...]);
    
    // Broadcast to everyone EXCEPT the sender
    broadcast(new \App\Events\MessageSent($message))->toOthers();
}
```

---

## 🎨 Step 3: Frontend Setup

### 1. Install NPM Dependencies
```bash
npm install --save-dev laravel-echo pusher-js
```

### 2. Create `resources/js/echo.js`
This file connects your browser to the Reverb server.
```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
```
*Import this file in your `app.js`:* `import './echo';`

### 3. Add CSRF Meta Tag
In your main layout file (`<head>` section):
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

---

## 🔥 Step 4: AlpineJS Integration

Inside your Blade view, use `window.Echo` to listen for new messages.

```javascript
selectConversation(conversation) {
    // 1. Leave old channel to avoid double listeners
    if (this.currentConversation) {
        window.Echo.leave(`messenger.${this.currentConversation.id}`);
    }

    this.currentConversation = conversation;

    // 2. Listen to the new private channel
    window.Echo.private(`messenger.${conversation.id}`)
        .listen('.message.sent', (event) => {
            // Push the message received from Echo into Alpine's array
            this.messages.push(event);
            this.scrollToBottom();
        });
}
```

---

## 🟢 Bonus: Online Status Tracking

1. **Database**: Add `last_seen_at` timestamp to `users` table.
2. **Middleware**: Create `UpdateLastSeen` to set `auth()->user()->update(['last_seen_at' => now()])` on every request.
3. **Check**: If `last_seen_at > now()->subMinutes(5)`, they are online!

---

---

## ⚡ Automation: Single-Command Setup

Instead of opening three terminals, you can now start everything in one window:

```bash
npm run dev:all
```

This command uses `concurrently` to launch:
- `php artisan serve` (port 8000)
- `npm run dev` (Vite)
- `php artisan reverb:start` (port 8080)
- It also runs `npm run build` once before starting the servers.
