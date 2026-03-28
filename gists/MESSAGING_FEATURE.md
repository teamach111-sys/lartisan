---
contents:
  - id: 1
    label: .env
    language: env
  - id: 2
    label: Terminal
    language: bash
  - id: 3
    label: create_conversations_table.php
    language: php
  - id: 4
    label: create_messages_table.php
    language: php
  - id: 5
    label: app/Models/Conversation.php
    language: php
  - id: 6
    label: app/Models/Message.php
    language: php
  - id: 7
    label: app/Http/Middleware/UpdateLastSeen.php
    language: php
  - id: 8
    label: app/Events/MessageSent.php
    language: php
  - id: 9
    label: routes/channels.php
    language: php
  - id: 10
    label: routes/web.php
    language: php
  - id: 11
    label: resources/js/echo.js
    language: js
createdAt: 1774641546623
description: null
folderId: null
id: 1774641546623
isDeleted: 0
isFavorites: 0
name: MESSAGING_FEATURE
tags: []
updatedAt: 1774641546623
---

## Fragment: .env
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

## Fragment: Terminal
```bash
composer require laravel/reverb
php artisan reverb:install
npm install laravel-echo pusher-js
```

## Fragment: create_conversations_table.php
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

## Fragment: create_messages_table.php
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

## Fragment: app/Models/Conversation.php
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

## Fragment: app/Models/Message.php
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

## Fragment: app/Http/Middleware/UpdateLastSeen.php
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

## Fragment: app/Events/MessageSent.php
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

## Fragment: routes/channels.php
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

## Fragment: routes/web.php
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

## Fragment: resources/js/echo.js
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

