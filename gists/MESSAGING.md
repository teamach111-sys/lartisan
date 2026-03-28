---
contents:
  - id: 1
    label: create_conversations_table.php
    language: php
  - id: 2
    label: create_messages_table.php
    language: php
  - id: 3
    label: last_seen_at
    language: php
  - id: 4
    label: app/Models/Conversation.php
    language: php
  - id: 5
    label: app/Models/Message.php
    language: php
  - id: 6
    label: app/Models/User.php
    language: php
  - id: 7
    label: app/Http/Middleware/UpdateLastSeen.php
    language: php
  - id: 8
    label: bootstrap/app.php
    language: php
  - id: 9
    label: app/Events/MessageSent.php
    language: php
  - id: 10
    label: routes/channels.php
    language: php
  - id: 11
    label: app/Http/Controllers/MessageController.php
    language: php
  - id: 12
    label: routes/web.php
    language: php
  - id: 13
    label: resources/js/echo.js
    language: js
  - id: 14
    label: resources/js/app.js
    language: js
  - id: 15
    label: resources/views/message.blade.php
    language: blade
  - id: 16
    label: resources/views/partials/chat-content.blade.php
    language: blade
createdAt: 1774641546655
description: null
folderId: null
id: 1774641546655
isDeleted: 0
isFavorites: 0
name: MESSAGING
tags: []
updatedAt: 1774641546655
---

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
            $table->unique(['produit_id', 'acheteur_id']); // One conversation per buyer per product
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
            $table->boolean('est_lu')->default(false)->index(); // Unread badge support
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
```

## Fragment: last_seen_at
```php
// In your users migration or a new alter migration:
$table->timestamp('last_seen_at')->nullable();
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
        'contenu',
        // 'est_lu' defaults to false
    ];

    public function conversation() {
        return $this->belongsTo(Conversation::class);
    }

    public function expediteur() {
        return $this->belongsTo(User::class, 'expediteur_id');
    }
}
```

## Fragment: app/Models/User.php
```php
// Add to $fillable:
'last_seen_at',

// Add to $casts:
'last_seen_at' => 'datetime',

// Add relationship (if you have products):
public function produits() {
    return $this->hasMany(Produit::class, 'vendeur_id');
}
```

## Fragment: app/Http/Middleware/UpdateLastSeen.php
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

## Fragment: bootstrap/app.php
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->append(\App\Http\Middleware\UpdateLastSeen::class);
})
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

    if (!$conversation) {
        return false;
    }

    // Only the buyer and the seller of that product can listen
    return (int) $user->id === (int) $conversation->acheteur_id ||
           (int) $user->id === (int) $conversation->produit->vendeur_id;
});
```

## Fragment: app/Http/Controllers/MessageController.php
```php
<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Produit;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Create or find a conversation when a buyer clicks "Contact Seller".
     */
    public function startConversation(Produit $produit)
    {
        if ($produit->vendeur_id === auth()->id()) {
            return back()->with('error', 'You cannot contact yourself.');
        }

        $conversation = Conversation::firstOrCreate([
            'produit_id'  => $produit->id,
            'acheteur_id' => auth()->id(),
        ]);

        return redirect()->route('message', ['conversation' => $conversation->id]);
    }

    /**
     * List all conversations for the sidebar.
     * Buyers always see their conversations.
     * Sellers only see conversations once a message has been sent.
     */
    public function index()
    {
        $userId = auth()->id();

        $buyerConversations = Conversation::with(['produit.vendeur', 'acheteur', 'messages' => function($q) {
            $q->orderBy('created_at', 'desc');
        }])
        ->where('acheteur_id', $userId)
        ->get();

        $sellerConversations = Conversation::with(['produit.vendeur', 'acheteur', 'messages' => function($q) {
            $q->orderBy('created_at', 'desc');
        }])
        ->whereHas('produit', function ($query) use ($userId) {
            $query->where('vendeur_id', $userId);
        })
        ->where('acheteur_id', '!=', $userId)
        ->whereHas('messages') // Only show to seller after first message
        ->get();

        $conversations = $buyerConversations->merge($sellerConversations)
        ->map(function ($conversation) use ($userId) {
            $partner = $userId === $conversation->acheteur_id
                ? $conversation->produit->vendeur
                : $conversation->acheteur;

            $latestMessage = $conversation->messages->first();

            $unreadCount = $conversation->messages()
                ->where('est_lu', false)
                ->where('expediteur_id', '!=', $userId)
                ->count();

            return [
                'id'             => $conversation->id,
                'produit_id'     => $conversation->produit_id,
                'produit_nom'    => $conversation->produit->titre ?? 'Produit',
                'produit_slug'   => $conversation->produit->slug ?? '',
                'partner_name'   => $partner->name ?? 'Inconnu',
                'partner_pfp'    => $partner->pfp
                    ? asset('storage/' . $partner->pfp)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($partner->name ?? 'U'),
                'auth_pfp'       => auth()->user()->pfp
                    ? asset('storage/' . auth()->user()->pfp)
                    : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name ?? 'U'),
                'latest_message' => $latestMessage ? $latestMessage->contenu : 'Nouvelle conversation',
                'latest_time'    => $latestMessage ? $latestMessage->created_at->format('H:i') : '',
                'unread_count'   => $unreadCount,
                'is_online'      => $partner && $partner->last_seen_at
                    && $partner->last_seen_at->gt(now()->subMinutes(5)),
            ];
        });

        return response()->json($conversations);
    }

    /**
     * Fetch message history for a single conversation.
     * Also marks all incoming messages as read.
     */
    public function fetchMessages(Conversation $conversation)
    {
        if ($conversation->acheteur_id !== auth()->id() &&
            $conversation->produit->vendeur_id !== auth()->id()) {
            abort(403);
        }

        // Bulk mark as read (like WhatsApp)
        $conversation->messages()
            ->where('expediteur_id', '!=', auth()->id())
            ->where('est_lu', false)
            ->update(['est_lu' => true]);

        $messages = $conversation->messages()->orderBy('created_at', 'asc')->get()
            ->map(function ($msg) {
                return [
                    'id'            => $msg->id,
                    'expediteur_id' => $msg->expediteur_id,
                    'contenu'       => $msg->contenu,
                    'time'          => $msg->created_at->format('H:i'),
                ];
            });

        return response()->json($messages);
    }

    /**
     * Send a new message and broadcast it via Reverb.
     */
    public function sendMessage(Request $request, Conversation $conversation)
    {
        if ($conversation->acheteur_id !== auth()->id() &&
            $conversation->produit->vendeur_id !== auth()->id()) {
            abort(403);
        }

        $request->validate(['contenu' => 'required|string']);

        $message = $conversation->messages()->create([
            'expediteur_id' => auth()->id(),
            'contenu'       => $request->contenu,
        ]);

        broadcast(new \App\Events\MessageSent($message))->toOthers();

        return response()->json([
            'id'            => $message->id,
            'expediteur_id' => $message->expediteur_id,
            'contenu'       => $message->contenu,
            'time'          => $message->created_at->format('H:i'),
        ]);
    }
}
```

## Fragment: routes/web.php
```php
use App\Http\Controllers\MessageController;

// Main messaging page
Route::get('/message', function () {
    return view('message', ['auth_user' => auth()->user()]);
})->name('message')->middleware('auth');

// Start a conversation from a product page
Route::post('/message/{produit}', [MessageController::class, 'startConversation'])
    ->name('produit.contact')
    ->middleware('auth');

// API routes (protected)
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

## Fragment: resources/js/app.js
```js
import './echo';
```

## Fragment: resources/views/message.blade.php
```blade
<x-layoutdash2>
    <x-slot:title>Mes Messages</x-slot:title>
    <x-slot:h1>Mes Messages</x-slot:h1>
    <x-slot:topbar>
        <style>
            .scrollbar-hide::-webkit-scrollbar { display: none; }
            .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        </style>
        <div class="flex md:flex-row md:justify-between md:items-center flex-col pb-1">
            <div class="h-15 flex items-center gap-2 overflow-x-auto snap-x snap-mandatory scroll-smooth">
                <a href="" class="flex-shrink-0 snap-center border cursor-pointer text-[15px] rounded-[50px] p-2 transition-all duration-200">Tous</a>
                <a href="" class="flex-shrink-0 snap-center border cursor-pointer text-[15px] hover:border-black border-transparent rounded-[50px] p-2 transition-all duration-200">Non lus</a>
            </div>
            <div>
                <button onclick="window.location.reload()"
                    class="text-[15px] hidden md:block mx-auto bg-white rounded-sm h-auto p-2 border cursor-pointer transition-all duration-200 hover:bg-[#FF8E72] hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000]">
                    Actualiser
                </button>
            </div>
        </div>
    </x-slot:topbar>

    <div x-data="messaging({{ auth()->id() }})" class="flex flex-col md:flex-row h-full overflow-hidden gap-0 md:gap-10">

        {{-- Contacts Sidebar --}}
        <div class="w-full md:w-[320px] lg:w-[380px] flex flex-col flex-shrink-0" :class="currentConversation ? 'hidden md:flex' : 'flex'">
            <div class="flex items-center justify-between mb-6 px-1">
                <h3 class="text-xs font-black uppercase tracking-widest text-black opacity-30">Conversations</h3>
                <div class="relative w-52 group">
                    <input type="text" x-model="searchQuery" placeholder="Rechercher..."
                        class="w-full pl-9 pr-4 py-2 text-sm bg-white border border-black rounded-sm focus:outline-none focus:bg-[#FF8E72]/5 transition-all text-black placeholder:text-black/20 focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="size-4 absolute left-3 top-1/2 -translate-y-1/2 opacity-30">
                        <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            <div class="flex flex-row md:flex-col gap-4 overflow-x-auto md:overflow-y-auto pb-4 md:pb-0 pl-1 md:pl-2 pt-1 md:pt-2 md:pr-2 scrollbar-hide flex-1">
                <template x-for="conv in filteredConversations" :key="conv.id">
                    <div @click="selectConversation(conv)"
                        :class="currentConversation?.id === conv.id
                            ? 'bg-white border-[#FF8E72] shadow-[6px_6px_0px_0px_#000000] -translate-x-1 -translate-y-1'
                            : 'bg-white border-black hover:shadow-[4px_4px_0px_0px_#000000] hover:-translate-x-0.5 hover:-translate-y-0.5'"
                        class="flex items-center gap-4 p-4 rounded-sm border border-black cursor-pointer transition-all min-w-[280px] md:min-w-0 relative group">

                        <div class="relative flex-shrink-0">
                            <img class="h-14 w-14 md:h-16 md:w-16 object-cover rounded-full border border-black/5"
                                :src="conv.partner_pfp" alt="">
                            <div :class="conv.is_online ? 'bg-green-500' : 'bg-gray-300'"
                                class="absolute bottom-0 right-0 h-4 w-4 rounded-full border-2 border-white shadow-sm"></div>
                        </div>

                        <div class="flex-1 overflow-hidden">
                            <div class="flex items-center justify-between mb-0.5">
                                <h2 class="font-bold text-base truncate text-black" :class="currentConversation?.id === conv.id ? 'text-[#FF8E72]' : ''" x-text="conv.partner_name"></h2>
                                <span class="text-[10px] font-medium opacity-30 whitespace-nowrap ml-2" x-text="conv.latest_time"></span>
                            </div>
                            <p class="text-[10px] font-black uppercase text-black/40 truncate mb-1" x-text="conv.produit_nom"></p>
                            <p class="text-xs text-gray-400 truncate font-medium leading-none" x-text="conv.latest_message || 'Démarrer la discussion'"></p>
                        </div>

                        <template x-if="conv.unread_count > 0">
                            <div class="absolute -top-2 -right-2 bg-[#FF8E72] text-white text-[10px] font-black h-6 w-6 rounded-full flex items-center justify-center border-2 border-white shadow-sm z-10"
                                x-text="conv.unread_count"></div>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        {{-- Desktop Chat Area --}}
        <div class="hidden md:flex flex-1 bg-white flex-col border border-black/10 relative overflow-hidden rounded-sm">
            <template x-if="currentConversation">
                <div class="h-full flex flex-col">
                    @include('partials.chat-content')
                </div>
            </template>
            <template x-if="!currentConversation">
                <div class="h-full flex flex-col items-center justify-center text-center p-12 bg-gray-50/20">
                    <p class="text-sm font-black text-black/20 uppercase tracking-[0.2em]">Cliquez sur un artisan à gauche pour démarrer la discussion !</p>
                </div>
            </template>
        </div>

        {{-- Mobile Full-Screen Chat (Teleported to Body) --}}
        <template x-teleport="body">
            <div x-show="currentConversation && isMobile"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-y-full"
                 x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-y-0"
                 x-transition:leave-end="translate-y-full"
                 class="fixed inset-0 z-[10000] bg-white flex flex-col md:hidden overflow-hidden h-full w-full">
                <div class="flex flex-col flex-1 h-full w-full bg-white">
                    @include('partials.chat-content')
                </div>
            </div>
        </template>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('messaging', (myId) => ({
                conversations: [],
                messages: [],
                currentConversation: null,
                newMessage: '',
                searchQuery: '',
                authUser: @json($auth_user),
                isMobile: window.innerWidth < 768,

                init() {
                    window.addEventListener('resize', () => {
                        this.isMobile = window.innerWidth < 768;
                    });
                    this.fetchConversations();
                },

                get filteredConversations() {
                    if (!this.searchQuery.trim()) return this.conversations;
                    const query = this.searchQuery.toLowerCase();
                    return this.conversations.filter(c =>
                        c.partner_name.toLowerCase().includes(query) ||
                        c.produit_nom.toLowerCase().includes(query)
                    );
                },

                async fetchConversations() {
                    const res = await axios.get('/api/conversations');
                    this.conversations = res.data;

                    // Auto-open if URL has ?conversation=X
                    const urlParams = new URLSearchParams(window.location.search);
                    const convId = urlParams.get('conversation');
                    if (convId) {
                        const target = this.conversations.find(c => c.id == convId);
                        if (target) this.selectConversation(target);
                    }
                },

                selectConversation(conversation) {
                    if (this.currentConversation) {
                        window.Echo.leave(`messenger.${this.currentConversation.id}`);
                    }

                    this.currentConversation = conversation;
                    this.messages = [];

                    // Real-time listener
                    window.Echo.private(`messenger.${conversation.id}`)
                        .listen('.message.sent', (e) => {
                            if (this.currentConversation && e.id && !this.messages.find(m => m.id === e.id)) {
                                this.messages.push(e);
                                this.scrollToBottom();
                            }
                        });

                    conversation.unread_count = 0; // Optimistic badge clear
                    this.fetchMessages(conversation.id);
                },

                async fetchMessages(conversationId) {
                    const res = await axios.get(`/api/conversations/${conversationId}/messages`);
                    this.messages = res.data;
                    this.scrollToBottom();
                },

                async sendMessage() {
                    if (!this.newMessage.trim() || !this.currentConversation) return;

                    const content = this.newMessage;
                    this.newMessage = '';

                    // Optimistic bubble
                    const tempMessage = {
                        id: Date.now(),
                        expediteur_id: myId,
                        contenu: content,
                        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    };
                    this.messages.push(tempMessage);
                    this.scrollToBottom();

                    try {
                        await axios.post(`/api/conversations/${this.currentConversation.id}/messages`, {
                            contenu: content
                        });
                    } catch (err) {
                        console.error(err);
                    }
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const containers = document.querySelectorAll('.messages-container');
                        containers.forEach(c => { if (c) c.scrollTop = c.scrollHeight; });
                    });
                }
            }));
        });
    </script>
</x-layoutdash2>
```

## Fragment: resources/views/partials/chat-content.blade.php
```blade
{{-- Header --}}
<div class="p-4 h-20 border-b border-black/5 flex items-center justify-between bg-white z-10 flex-shrink-0">
    <div class="flex items-center gap-4">
        {{-- Back Arrow (Mobile) --}}
        <button @click="currentConversation = null" class="md:hidden p-2 hover:bg-gray-50 border border-black rounded-sm transition-all text-black">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </button>

        <div class="relative">
            <img class="h-12 w-12 md:h-14 md:w-14 object-cover rounded-full border border-black/5 shadow-sm"
                :src="currentConversation?.partner_pfp" alt="">
            <div :class="currentConversation?.is_online ? 'bg-green-500' : 'bg-gray-300'"
                class="absolute bottom-0 right-0 h-4 w-4 rounded-full border-2 border-white shadow-sm"></div>
        </div>
        <div>
            <div class="flex items-center gap-2 overflow-hidden flex-nowrap">
                <h2 class="font-bold text-base md:text-lg text-black truncate" x-text="currentConversation?.partner_name"></h2>
                <span :class="currentConversation?.is_online ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500'"
                    class="px-1.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest whitespace-nowrap"
                    x-text="currentConversation?.is_online ? 'En Ligne' : 'Hors Ligne'"></span>
            </div>
            <p class="line-clamp-1 text-[10px] font-black uppercase text-black/40 tracking-tight"
                x-text="'Article: ' + currentConversation?.produit_nom"></p>
        </div>
    </div>

    <div class="flex items-center gap-2">
        <button class="p-2 hover:bg-gray-50 rounded-full transition-colors opacity-30 hover:opacity-100">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path fill-rule="evenodd" d="M10.5 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm0 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm0 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
</div>

{{-- Messages --}}
<div class="messages-container p-6 md:p-10 flex-1 border-b border-black/5 w-full overflow-y-auto bg-gray-50/30 flex flex-col gap-6">
    <template x-for="msg in messages" :key="msg.id">
        <div class="flex items-end gap-3"
            :class="msg.expediteur_id == {{ auth()->id() }} ? 'flex-row-reverse' : 'flex-row'">
            <img class="h-9 w-9 md:h-10 md:w-10 object-cover rounded-full border border-black/5 bg-white shadow-sm"
                :src="msg.expediteur_id == {{ auth()->id() }}
                    ? currentConversation?.auth_pfp
                    : currentConversation?.partner_pfp"
                alt="">

            <div class="max-w-[85%] md:max-w-[75%] break-words p-4 md:px-6 md:py-4 shadow-sm border border-black/5"
                :class="msg.expediteur_id == {{ auth()->id() }}
                    ? 'bg-[#F4F4F0] text-gray-800 rounded-2xl rounded-tr-none'
                    : 'bg-white text-gray-800 rounded-2xl rounded-tl-none'">
                <p class="text-base md:text-[16px] font-medium leading-relaxed" x-text="msg.contenu"></p>
                <div class="flex items-center justify-end gap-2 mt-2 opacity-50">
                    <p class="text-[10px] font-bold uppercase" x-text="msg.time"></p>
                    <template x-if="msg.expediteur_id == {{ auth()->id() }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-3.5">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                        </svg>
                    </template>
                </div>
            </div>
        </div>
    </template>
</div>

{{-- Input Area --}}
<div class="p-6 md:p-8 bg-white border-t border-black/5">
    <div class="relative flex items-center gap-4">
        {{-- flex items-center on this wrapper keeps the send button vertically centered --}}
        <div class="flex-1 relative group flex items-center">
            <textarea x-model="newMessage" @keydown.enter.prevent="sendMessage"
                class="w-full pl-5 pr-5 py-4 bg-gray-50 border border-black rounded-sm focus:outline-none focus:bg-white focus:shadow-[4px_4px_0px_0px_#000000] transition-all font-bold text-base placeholder:text-black/10 resize-none h-16"
                placeholder="Votre message ici..."></textarea>
        </div>
        <button @click="sendMessage"
            class="bg-black text-white h-16 w-16 flex items-center justify-center border border-black rounded-sm hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] hover:bg-[#FF8E72] hover:text-black transition-all active:translate-x-0 active:translate-y-0 active:shadow-none flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path d="M3.478 2.404a.75.75 0 0 0-.926.941l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.404Z" />
            </svg>
        </button>
    </div>
</div>
```

