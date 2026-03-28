# L'Artisan — Real-Time Messaging Feature

> Includes everything required for Laravel Reverb WebSockets, Echo frontend, and the Messaging SPA.

## 1. Environment & Dependencies

### `.env`
```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

### `Terminal`
```bash
composer require laravel/reverb
php artisan reverb:install
npm install laravel-echo pusher-js
```

---

## 2. Migrations

### `create_conversations_table.php`
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->cascadeOnDelete();
            $table->foreignId('acheteur_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('last_message_at')->nullable()->index();
            $table->unique(['produit_id', 'acheteur_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
```

### `create_messages_table.php`
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();
            $table->foreignId('expediteur_id')->constrained('users');
            $table->text('contenu');
            $table->boolean('est_lu')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
```

---

## 3. Models

### `app/Models/Conversation.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['produit_id', 'acheteur_id'];

    public function produit() { return $this->belongsTo(Produit::class); }
    public function acheteur() { return $this->belongsTo(User::class, 'acheteur_id'); }
    public function messages() { return $this->hasMany(Message::class); }
}
```

### `app/Models/Message.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['conversation_id', 'expediteur_id', 'contenu'];

    public function conversation() { return $this->belongsTo(Conversation::class); }
    public function expediteur() { return $this->belongsTo(User::class, 'expediteur_id'); }
}
```

---

## 4. Middleware & Events

### `app/Http/Middleware/UpdateLastSeen.php` (Online Status)
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            Auth::user()->updateQuietly(['last_seen_at' => now()]);
        }
        return $next($request);
    }
}
```
*(Register this in `bootstrap/app.php`)*

### `app/Events/MessageSent.php` (Broadcasting)
```php
<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('messenger.' . $this->message->conversation_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'id'            => $this->message->id,
            'expediteur_id' => $this->message->expediteur_id,
            'contenu'       => $this->message->contenu,
            'time'          => $this->message->created_at->format('H:i'),
        ];
    }
}
```

---

## 5. Channel Authorization

### `routes/channels.php`
```php
<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('messenger.{conversationId}', function ($user, $conversationId) {
    $conversation = \App\Models\Conversation::where('id', (int) $conversationId)->first();

    if (!$conversation) { return false; }

    return (int) $user->id === (int) $conversation->acheteur_id ||
           (int) $user->id === (int) $conversation->produit->vendeur_id;
});
```

---

## 6. Controllers

### `app/Http/Controllers/MessageController.php`
*(Handles SPA, fetching conversations/messages, sending messages, and broadcasting).*

---

## 7. Routes

### `routes/web.php`
```php
// Button on product page to contact seller
Route::post('/message/{produit}', [MessageController::class, 'startConversation'])->name('produit.contact')->middleware('auth');

// SPA Interface
Route::get('/message', function () {
    return view('message', ['auth_user' => auth()->user()]);
})->name('message')->middleware('auth');

// API Endpoints
Route::middleware('auth')->group(function () {
    Route::get('/api/conversations', [MessageController::class, 'index']);
    Route::get('/api/conversations/{conversation}/messages', [MessageController::class, 'fetchMessages']);
    Route::post('/api/conversations/{conversation}/messages', [MessageController::class, 'sendMessage']);
});
```

---

## 8. Frontend

### `resources/js/echo.js`
```js
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
*(Import in `app.js`)*

### `resources/views/message.blade.php` & `partials/chat-content.blade.php`
*(See `FULL_SITE_GIST.md` for the entire Alpine.js and Blade implementation of the real-time chat).*
