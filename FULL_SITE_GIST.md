# L'Artisan — Full Site Gist

> **Apply in order, one step at a time.**  
> Covers: Register · Login · Logout · Product Create · Product Display · Messaging (Real-Time).  
> Stack: Laravel 11+, AlpineJS 3, Laravel Reverb, TailwindCSS v4.

---

## STEP 1 — Install Dependencies

```bash
# Core
composer require laravel/reverb
php artisan reverb:install

# Frontend
npm install laravel-echo pusher-js
```

---

## STEP 2 — Database Migrations

> **Apply order matters:** users → categories → produits → conversations → messages

### `create_users_table.php` *(already exists — verify columns)*
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('pfp')->nullable();             // Profile picture path
            $table->string('telephone', 20)->nullable();
            $table->string('ville_utilisateur', 100)->default('Marrakech')->index();
            $table->string('statut_compte')->default('actif');
            $table->string('role')->default('utilisateur');
            $table->timestamp('last_seen_at')->nullable();  // For online status
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
```

### `create_categories_table.php`
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
```

### `create_produits_table.php`
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendeur_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('categorie_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('titre');
            $table->string('slug')->unique()->index();  // SEO-friendly URL key
            $table->text('description')->nullable();
            $table->boolean('telephone_visible')->default(false);
            $table->decimal('prix', 12, 2)->index();
            $table->string('ville_produit', 100)->index();
            $table->json('images')->nullable();         // Array of 5 image paths
            $table->string('etat_produit')->default('neuf');
            $table->string('etat_moderation')->default('en_attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
```

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

```bash
php artisan migrate
```

---

## STEP 3 — Models

### `app/Models/User.php`
```php
<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'pfp',
        'telephone',
        'telephone_visible',
        'ville_utilisateur',
        'last_seen_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'last_seen_at'      => 'datetime',
        ];
    }

    // Relations
    public function produits()
    {
        return $this->hasMany(Produit::class, 'vendeur_id');
    }

    public function favoris()
    {
        return $this->belongsToMany(Produit::class, 'favoris', 'utilisateur_id', 'produit_id')
                    ->withTimestamps();
    }
}
```

### `app/Models/Categorie.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $fillable = ['nom'];

    public function produits()
    {
        return $this->hasMany(Produit::class, 'categorie_id');
    }
}
```

### `app/Models/Produit.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'categorie_id',
        'titre',
        'slug',
        'description',
        'images',
        'prix',
        'ville_produit',
        'etat_produit',
        'vendeur_id',
    ];

    protected $casts = [
        'images' => 'array',
        'prix'   => 'decimal:2',
    ];

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }
}
```

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

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function acheteur()
    {
        return $this->belongsTo(User::class, 'acheteur_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
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

    protected $fillable = [
        'conversation_id',
        'expediteur_id',
        'contenu',
        // 'est_lu' defaults to false in migration
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function expediteur()
    {
        return $this->belongsTo(User::class, 'expediteur_id');
    }
}
```

---

## STEP 4 — Middleware (Online Status)

### `app/Http/Middleware/UpdateLastSeen.php` *(new file)*
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

### `bootstrap/app.php` — register the middleware
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->append(\App\Http\Middleware\UpdateLastSeen::class);
})
```

---

## STEP 5 — Broadcasting Event (Messaging)

### `app/Events/MessageSent.php` *(new file)*
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

## STEP 6 — Channel Authorization

### `routes/channels.php`
```php
<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('messenger.{conversationId}', function ($user, $conversationId) {
    $conversation = \App\Models\Conversation::where('id', (int) $conversationId)->first();

    if (!$conversation) {
        return false;
    }

    // Only the buyer and the product's seller can listen on this channel
    return (int) $user->id === (int) $conversation->acheteur_id ||
           (int) $user->id === (int) $conversation->produit->vendeur_id;
});
```

---

## STEP 7 — Controllers

### `app/Http/Controllers/AuthController.php`
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // --- REGISTER ---

    /** Show the register form */
    public function create()
    {
        return view('auth.register');
    }

    /** Handle register form submission */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|min:8|confirmed', // needs password_confirmation field
            'ville_utilisateur'=> 'required|string',
            'telephone'        => 'nullable|string',
            'pfp'              => 'nullable|image|max:2048',
        ]);

        $user = new User();
        $user->name             = $validated['name'];
        $user->email            = $validated['email'];
        $user->password         = Hash::make($validated['password']);
        $user->ville_utilisateur= $validated['ville_utilisateur'];
        $user->telephone        = $request->telephone;

        if ($request->hasFile('pfp')) {
            $user->pfp = $request->file('pfp')->store('profiles', 'public');
        }

        $user->save();
        Auth::login($user);

        return redirect('/')->with('success', 'Bienvenue parmi nous !');
    }

    // --- LOGIN ---

    /** Show the login form */
    public function showLogin()
    {
        return view('auth.login');
    }

    /** Handle login form submission */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Les identifiants ne correspondent pas à nos enregistrements.',
        ]);
    }

    // --- LOGOUT ---

    /** Log out the user */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Vous avez été déconnecté.');
    }
}
```

### `app/Http/Controllers/ProduitController.php`
```php
<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProduitController extends Controller
{
    /** Home page — list all products */
    public function index()
    {
        $produits = Produit::latest()->get();
        return view('home', compact('produits'));
    }

    /** Single product page (slug-based for SEO) */
    public function show(Produit $produit)
    {
        $produit->load('vendeur');

        $relatedProducts = Produit::where('categorie_id', $produit->categorie_id)
            ->where('id', '!=', $produit->id)
            ->limit(4)
            ->get();

        return view('produit.show', compact('produit', 'relatedProducts'));
    }

    /** Show create product form */
    public function create()
    {
        $categories = Categorie::all();
        return view('produit.create', compact('categories'));
    }

    /** Store a new product */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'        => 'required|string|max:255',
            'prix'         => 'required|numeric|min:0',
            'description'  => 'nullable|string|max:1000',
            'categorie'    => 'required|exists:categories,id',
            'ville_produit'=> 'required|string|max:255',
            'etat_produit' => 'required|string|max:255',
            'images'       => 'required|array|size:5',
            'images.*'     => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Store 5 images
        $paths = [];
        foreach ($request->file('images') as $file) {
            $paths[] = $file->store('produits', 'public');
        }

        // Generate unique slug
        $slug = Str::slug($request->titre);
        $originalSlug = $slug;
        $count = 1;
        while (Produit::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        Produit::create([
            'titre'        => $validated['titre'],
            'prix'         => $validated['prix'],
            'description'  => $validated['description'],
            'ville_produit'=> $validated['ville_produit'],
            'etat_produit' => $validated['etat_produit'],
            'images'       => $paths,
            'slug'         => $slug,
            'vendeur_id'   => auth()->id(),
            'categorie_id' => (int) $request->categorie,
        ]);

        return redirect()->route('produit.create')->with('success', 'Produit créé avec succès!');
    }
}
```

### `app/Http/Controllers/MessageController.php`
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
            return back()->with('error', 'Vous ne pouvez pas vous contacter vous-même.');
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

        $buyerConversations = Conversation::with(['produit.vendeur', 'acheteur', 'messages' => function ($q) {
            $q->orderBy('created_at', 'desc');
        }])
        ->where('acheteur_id', $userId)
        ->get();

        $sellerConversations = Conversation::with(['produit.vendeur', 'acheteur', 'messages' => function ($q) {
            $q->orderBy('created_at', 'desc');
        }])
        ->whereHas('produit', function ($query) use ($userId) {
            $query->where('vendeur_id', $userId);
        })
        ->where('acheteur_id', '!=', $userId)
        ->whereHas('messages') // Seller only sees after first message
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
     * Fetch message history and mark all incoming messages as read.
     */
    public function fetchMessages(Conversation $conversation)
    {
        if ($conversation->acheteur_id !== auth()->id() &&
            $conversation->produit->vendeur_id !== auth()->id()) {
            abort(403);
        }

        // Bulk mark as read
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

---

## STEP 8 — Routes

> **Route order is critical in Laravel.**  
> Static segments (`/produit/create`) MUST come **before** wildcard routes (`/produit/{slug}`).  
> Auth/guest middleware is applied correctly per route.

### `routes/web.php` — full file
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashController;
use App\Http\Controllers\ProduitController;
use App\Models\Produit;
use App\Http\Controllers\MessageController;

// ─── PUBLIC ROUTES ──────────────────────────────────────────────────────────

// Home — list all products
Route::get('/', function () {
    $produits = Produit::latest()->get();
    return view('home', ['produits' => $produits]);
})->name('home');

// ─── AUTH ROUTES (GUEST ONLY) ────────────────────────────────────────────────

Route::get('/register', [AuthController::class, 'create'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'store'])->middleware('guest');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post')->middleware('guest');

// ─── AUTH ROUTES (LOGGED-IN ONLY) ───────────────────────────────────────────

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── PRODUCT ROUTES ──────────────────────────────────────────────────────────

// IMPORTANT: /produit/create MUST come before /produit/{produit:slug}
// otherwise Laravel would try to find a product with slug "create"
Route::get('/produit/create', [ProduitController::class, 'create'])
    ->name('produit.create')
    ->middleware('auth');

Route::post('/produit/store', [ProduitController::class, 'store'])
    ->name('produit.store')
    ->middleware('auth');

// Slug-based product page (public)
Route::get('/produit/{produit:slug}', [ProduitController::class, 'show'])
    ->name('produit.show');

// Start a conversation from the product page (POST from Contact button)
// MUST be after the static /produit/create to avoid conflicts
Route::post('/message/{produit}', [MessageController::class, 'startConversation'])
    ->name('produit.contact')
    ->middleware('auth');

// ─── MESSAGING ROUTES ────────────────────────────────────────────────────────

// Messaging page (SPA shell)
Route::get('/message', function () {
    return view('message', ['auth_user' => auth()->user()]);
})->name('message')->middleware('auth');

// Messaging API (all protected, grouped for clarity)
Route::middleware('auth')->group(function () {
    // 1. Fetch all conversations for the sidebar
    Route::get('/api/conversations', [MessageController::class, 'index']);

    // 2. Fetch message history for one conversation
    Route::get('/api/conversations/{conversation}/messages', [MessageController::class, 'fetchMessages']);

    // 3. Send a message into a conversation
    Route::post('/api/conversations/{conversation}/messages', [MessageController::class, 'sendMessage']);
});

// ─── DASHBOARD / ANNONCES ────────────────────────────────────────────────────

Route::get('/annonces', [DashController::class, 'annonces'])->name('annonces')->middleware('auth');
```

---

## STEP 9 — Frontend JS (Echo Config)

### `resources/js/echo.js` *(new file)*
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

### `resources/js/app.js` — add this line
```js
import './echo';
```

---

## STEP 10 — Views

### `resources/views/message.blade.php`
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

                    // Auto-open conversation if URL has ?conversation=X
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

                    // Subscribe to real-time updates
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

                    // Optimistic bubble — shows message instantly before server response
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

### `resources/views/partials/chat-content.blade.php`
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

### `resources/views/components/layout.blade.php`
```blade
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Lartisan | Acceuil</title>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="favicon_io (3)/android-chrome-512x512.png">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    @font-face {
      font-family: 'mabrypro';
      src: url('{{ asset('fonts/MabryPro-Regular.ttf') }}') format('truetype');
      font-display: swap;
    }

    html,
    body {
      font-family: 'mabrypro', ui-sans-serif, system-ui, sans-serif;
    }
  </style>
</head>

<body class="w-full bg-[#F4F4F0]">
  <div class="bg-[#F4F4F0] max-w-[1800px] mx-3 lg:mx-14">
    <nav class="lg:flex gap-5 lg:h-29 h-auto items-center justify-between py-1  mt-1  ">
      <div class="flex justify-between items-center ">
        <img class="lg:h-full h-auto max-h-20  shrink-0 " src="{{ asset('imgs/logo.svg') }}" alt="">
       @auth
     <div class="lg:hidden">
      <a href="{{ route('annonces') }}">
        <img class="h-10 w-10 object-cover rounded-[50px] hover:border hover:border-[#fb663f] cursor-pointer" src="{{ asset('storage/' . auth()->user()->pfp ?? 'default.svg') }}">
      </a>
     </div>
     @endauth

      </div>
      
      <div class="relative lg:flex-grow h-12 my-auto flex justify-between gap-3">
        <input placeholder="Rechercher"
          class=" pl-9 border border-black  my-auto rounded-sm w-full h-full bg-white outline-[0rem] shadow-none focus:shadow-[0_0_0_1px_#fb663f]"
          type="text">
        <svg class="absolute top-[28%] left-3 size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
          stroke-width="2" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <button onclick="togglesidebar()" class="lg:hidden relative w-auto h-full flex flex-col transition-all duration-200  border rounded-sm bg-white
        hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] hover:cursor-pointer ">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"
            class="size-5 w-auto h-full  p-3  ">
            <path d="M3 5H21V7H3z"></path>
            <path d="M5.5 11H18.5V13H5.5z"></path>
            <path d="M8 17H16V19H8z"></path>
          </svg>



        </button>




      </div>




      @guest
      <button onclick="window.location.href='{{ route('register') }}'" class="rounded-sm bg-[#F4F4F0] p-1  hidden lg:block lg:w-30 border border-black h-12 my-auto cursor-pointer 
    transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    ">
        Inscription
</button>
      @else
      <button class="rounded-sm bg-[#F4F4F0] p-1  hidden lg:block lg:w-30 border border-black h-12 my-auto cursor-pointer 
    transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    ">
        Mes favoris
      </button>
      @endguest


      <button onclick="window.location.href='{{ route('register') }}'" class="rounded-sm bg-black text-white p-1  hidden lg:block lg:w-50 border border-black h-12 my-auto cursor-pointer 
    transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    hover:bg-[#FF8E72]
    hover:text-black">
        Déposer une annonce
      </button>

      <div id="sidebar" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="togglesidebar()"></div>
        <div class="absolute top-0 left-0 h-full w-80 bg-white shadow-xl">
          <button onclick="togglesidebar()" class="absolute top-4 -right-7 z-60 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 text-white">
              <path fill-rule="evenodd"
                d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z"
                clip-rule="evenodd" />
            </svg>
          </button>
          <div class="flex justify-between p-4 gap-3">
            @auth
            <button class="rounded-sm bg-[#F4F4F0] p-1 w-30 text-[15px]  border border-black h-12 my-auto cursor-pointer 
    transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    ">
              Mes favoris
            </button>
            @else
            <button class="rounded-sm bg-[#F4F4F0] p-1 w-30 text-[15px]  border border-black h-12 my-auto cursor-pointer 
    transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    ">
              Inscription
            </button>
            @endauth

            <button onclick="window.location.href='{{ route('register') }}'" class="rounded-sm bg-black text-white p-1  w-40 text-[15px] border border-black h-12 my-auto cursor-pointer 
    transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    hover:bg-[#fb663f]
    hover:text-black">
              Déposer une annonce
            </button>


          </div>
          <div class="h-0.5 border-b border-black"></div>
          <div class="flex flex-col pt-0 mt-0">
            <a href=""
              class="p-4 h-15 flex items-center text-black text-[17px] hover:bg-black hover:text-white">Tout</a>
            <a href=""
              class="p-4 h-15 flex items-center text-black text-[17px] hover:bg-black hover:text-white">Mosaique</a>
            <a href=""
              class="p-4 h-15 flex items-center text-black text-[17px] hover:bg-black hover:text-white">Pottery</a>
          </div>


        </div>

      </div>


  </div>
  <script>
    function togglesidebar() {
      document.getElementById('sidebar').classList.toggle('hidden')
    }
  </script>
  </nav>
  <div class=" hidden lg:block lg:h-auto max-w-[1800px] mx-14 lg:flex justify-between">
    <div>
       <div class="flex text-black gap-3 ">

      <a class="rounded-[50px] border border-black bg-white px-3 py-2 w-auto h-auto transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
   
    hover:text-black" href="">Tout</a>

      <a class="border border-transparent hover:rounded-[50px] hover:border-black hover:bg-white px-3 py-2 w-auto h-auto transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    
    hover:text-black" href="">Carrelage</a>
      <a class="border border-transparent hover:rounded-[50px] hover:border-black hover:bg-white px-3 py-2 w-auto h-auto transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    hover:text-black" href="">Marbrerie</a>
      <a class="border border-transparent hover:rounded-[50px] hover:border-black hover:bg-white px-3 py-2 w-auto h-auto transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    hover:text-black" href="">Poterie</a>
      <a class="border border-transparent hover:rounded-[50px] hover:border-black hover:bg-white px-3 py-2 w-auto h-auto transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    hover:text-black" href="">Céramique</a>
      <a class="border border-transparent hover:rounded-[50px] hover:border-black hover:bg-white px-3 py-2 w-auto h-auto transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    hover:text-black" href="">Marqueterie</a>
      <a class="border border-transparent hover:rounded-[50px] hover:border-black hover:bg-white px-3 py-2 w-auto h-auto transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    hover:text-black" href="">Menuiserie</a>

      </div>
    
    </div>
    @auth
     <div>
      <a href="{{ route('annonces') }}">
        <img class="h-10 w-10 object-cover rounded-[50px] hover:border hover:border-[#fb663f] cursor-pointer" src="{{ asset('storage/' . auth()->user()->pfp ?? 'default.svg') }}">
      </a>
     </div>
     @endauth
   
  </div>
  </div>
  <div class="w-full border-t border-black my-6"></div>





  <main class=" max-w-[1800px] mx-3 lg:mx-14">
     {{ $slot }}
  </main>


  </div>
  </div>

  <footer
    class="hidden w-full bg-black text-white h-130 mt-6 flex flex-col lg:flex-row lg:h-100 justify-around items-start lg:items-center">
    <div class=" flex-col flex items-start max-w-[1800px] mx-3 lg:mx-14">
      <img src="{{ asset('imgs/logo.svg') }}" class="filter invert w-auto  h-auto max-h-30 lg:max-h-40 ">
      <h2 class="px-3">© 2026 Marché Artisanal. All rights reserved. </h2>

    </div>
    <div class="flex justify-between flex-col gap-14 w-auto max-w-144 px-3">
      <div class="flex justify-between  max-w-75">
        <div class=" flex flex-col gap-6 ">

          <a href="">Carrelage</a>
          <a href="">Céramique</a>
          <a href="">Carrelage</a>
          <a href="">Céramique</a>



        </div>
        <div class="flex flex-col gap-4">

          <a href="">Carrelage</a>
          <a href="">Céramique</a>



        </div>


      </div>

      <div class="flex justify-around sm:gap-22 sm:pb-4 w-auto ">
        <img class="w-[7%] md:w-[4%] aspect-square filter invert" src="socialicons/x.svg" alt="">
        <img class="w-[9%] md:w-[6%] aspect-square filter invert" src="socialicons/y.svg" alt="">
        <img class="w-[7%] md:w-[4%] aspect-square filter invert" src="socialicons/i.svg" alt="">
        <img class="w-[7%] md:w-[4%] aspect-square filter invert" src="socialicons/f.svg" alt="">
        <img class="w-[7%] md:w-[4%] aspect-square filter invert" src="socialicons/p.svg" alt="">
      </div>



    </div>




   
    

        
  </footer>

</body>

</html>
```

### `resources/views/components/layoutdash.blade.php`
```blade
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon_io (3)/android-chrome-512x512.png">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @font-face {
            font-family: 'mabrypro';
            src: url('{{ asset('fonts/MabryPro-Regular.ttf') }}') format('truetype');
            font-display: swap;
        }

        html,
        body {
            font-family: 'mabrypro', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>

<body class="bg-[#f4f4f0]">
    <nav x-data="{ open: false }" class="relative w-full lg:hidden h-16 bg-black flex justify-between items-center px-7">
        <img class="filter invert h-10" src="{{ asset('imgs/logo.svg') }}" alt="Logo">
        <p class="text-white text-lg">Dashboard</p>
        <button @click="open = !open" class="focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" class="size-6">
                <path fill-rule="evenodd" d="M3 6.75A.75.75 0 0 1 3.75 6h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 6.75ZM3 12a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12Zm0 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
            </svg>
        </button>
        <div x-show="open" x-cloak @click.away="open = false" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="absolute left-0 top-full z-50 w-full bg-black flex-col lg:static lg:flex lg:flex-row lg:w-auto"
            :class="open ? 'flex' : 'hidden'">
            <a href="{{ route('annonces') }}"
                class="gap-4 flex px-6 py-4 border-b border-white/33 hover:bg-white/5 {{ request()->routeIs('annonces') ? 'text-[#FF8E72]' : 'text-white' }}">
                <svg data-slot="icon" fill="none" class="size-5" stroke-width="1.5" stroke="currentColor"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z">
                    </path>
                </svg>
                Mes Annonces
            </a>
            <a href="/annonces" class="gap-4 flex text-white px-6 py-4 border-b border-white/33 hover:bg-white/5">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path
                        d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" />
                </svg>
                Mes Favoris</a>
            <a href="{{ route('message') }}" class="gap-4 flex text-white px-6 py-4 border-b border-white/33 hover:bg-white/5 {{ request()->routeIs('message') ? 'text-[#FF8E72]' : 'text-white' }}">
                <svg data-slot="icon" fill="white" class="size-5" stroke-width="1.5" stroke="currentColor"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z">
                    </path>
                </svg>
                Mon Profil</a>
            <a href="{{ route('home') }}" class="gap-4 flex text-white px-6 py-4 border-b border-white/33 hover:bg-white/5">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
                    <path d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
                </svg>
                Acceuil</a>
            <a href="/logout" class="gap-4 flex text-white px-6 py-4 border-b border-white/33 hover:bg-white/5">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path fill-rule="evenodd" d="M7.5 3.75A1.5 1.5 0 0 0 6 5.25v13.5a1.5 1.5 0 0 0 1.5 1.5h6a1.5 1.5 0 0 0 1.5-1.5V15a.75.75 0 0 1 1.5 0v3.75a3 3 0 0 1-3 3h-6a3 3 0 0 1-3-3V5.25a3 3 0 0 1 3-3h6a3 3 0 0 1 3 3V9A.75.75 0 0 1 15 9V5.25a1.5 1.5 0 0 0-1.5-1.5h-6Zm10.72 4.72a.75.75 0 0 1 1.06 0l3 3a.75.75 0 0 1 0 1.06l-3 3a.75.75 0 1 1-1.06-1.06l1.72-1.72H9a.75.75 0 0 1 0-1.5h10.94l-1.72-1.72a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                </svg>
                Se deconnecter
            </a>
        </div>
    </nav>

    <div class="flex">
        <aside class="hidden lg:block h-screen w-60 bg-black text-white flex flex-col gap-5 fixed ">
            <img class="filter invert p-7 border-black  mr-3" src="{{ asset('imgs/logo.svg') }}" alt="">
            <div class="gap-5 flex flex-col mr-3">
                <div class="bg-white h-[0.5px]"></div>
                <div class="flex items-center gap-4 pl-5 ">
                    <svg data-slot="icon" fill="white" class="size-5" stroke-width="1.5" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z">
                        </path>
                    </svg>
                    <a class="hover:text-[#FF8E72] {{ request()->routeIs('annonces') ? 'text-[#FF8E72]' : 'text-white' }}"
                        href="{{ route('annonces') }}">Mes Annonces</a>
                </div>
                <div class="bg-white h-[0.5px]"></div>
                <!-- Other sidebar links... -->
            </div>
        </aside>

        <div class="pt-6 flex flex-col w-full lg:ml-60">
            <div class="pl-7 gap-2 flex flex-col pr-7 h-33">
                <div class="flex justify-between">
                    <h1 class="text-[23px]">{{ $h1 }}</h1>
                    <button onclick="window.location.href='{{ $btnlocation ?? '' }}'"
                        class="lg:text-[23px] text-[15px] md:hidden  bg-white rounded-sm h-auto p-2 border cursor-pointer transition-all duration-200 hover:bg-[#FF8E72] hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000]">
                        {{ $btnname ?? '' }}
                    </button>
                </div>
                <div class="flex md:flex-row md:justify-between md:items-center flex-col pb-1">
                    <div class="h-15 flex items-center gap-2 overflow-x-auto snap-x snap-mandatory scroll-smooth ">
                        <a href="" class="flex-shrink-0 snap-center border cursor-pointer text-[15px]  rounded-[50px] p-2 transition-all duration-200">{{ $firstc ?? '' }}</a>
                        <a href="" class="flex-shrink-0 snap-center border cursor-pointer text-[15px] hover:border-black border-transparent rounded-[50px] p-2 transition-all duration-200">{{ $secondc ?? '' }}</a>
                    </div>
                    <div>
                        <button onclick="window.location.href='{{ $mobbtnlocation ?? '' }}'"
                            class="text-[15px] hidden md:block mx-auto bg-white rounded-sm h-auto p-2 border cursor-pointer transition-all duration-200 hover:bg-[#FF8E72] hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000]">
                            {{ $mobbtnname ?? '' }}
                        </button>
                    </div>
                </div>
                {{ $topbar ?? '' }}
            </div>
            <div class="bg-black h-[0.5px]"></div>
            <main class="p-7">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
```

### `resources/views/components/layoutdash2.blade.php`
*(Same as layoutdash, but with `h-[calc(100vh-180px)] overflow-hidden` on main container for messaging layout)*
```blade
<!DOCTYPE html>
<!-- Contains same nav as layoutdash but locks body height for the chat SPA -->
<!-- ... -->
        <div class="pt-6 flex flex-col w-full lg:ml-60">
            <div class="pl-7 gap-2 flex flex-col pr-7 h-33">
                <div class="flex justify-between">
                    <h1 class="text-[23px]">{{ $h1 }}</h1>
                </div>
                {{ $topbar }}
            </div>
            <div class="bg-black h-[0.5px]"></div>
            <main class="p-7 h-[calc(100vh-180px)] overflow-hidden">
                {{ $slot }}
            </main>
        </div>
<!-- ... -->
```

### `resources/views/home.blade.php`
```blade
<x-layout>
  <div class="py-2 flex justify-between">
    <p class="text-[26px] lg:text-[26px] ">Annonces Sponsorisé</p>
    <div class="flex items-center gap-2">
      <button class="cursor-pointer" onclick="scrollSlider('left')">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor" class="size-6">
          <path d="m6 12 6 5v-4h6v-2h-6V7z"></path>
        </svg>
      </button>
      <button class="cursor-pointer" onclick="scrollSlider('right')">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor" class="size-6">
          <path d="M6 13h6v4l6-5-6-5v4H6z"></path>
        </svg>
      </button>
    </div>
  </div>

  <section style="scrollbar-width: none; -ms-overflow-style: none;"
    class="py-6 px-1 lg:flex grid grid-flow-col auto-cols-[calc(60%-2rem)] gap-3 overflow-x-auto snap-x snap-mandatory scroll-smooth scrollbar-hide">
    <!-- Sponsored cards go here... -->
  </section>

  <section class="pb-6 pt-11 ">
    <div class="flex lg:justify-between flex-col lg:flex-row lg:items-center pb-4 gap-4">
      <p class="text-[26px] lg:text-[26px] md:text-[26px] ">Dans le marché</p>
      <div class="">
        <a href="" class="border md:text-[20px] bg-white border-black hover:border-black lg:text[20px] cursor-pointer text-[20px] rounded-[50px] p-2 transition-all duration-200">
          Nouvelles Annonces
        </a>
      </div>
    </div>
    <div class="pt-6 flex flex-col items-start lg:flex-row gap-15 ">
      <!-- Sidebar Filters here... -->
      
      <div class="flex flex-col gap-9 w-full">
        <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-3 items-stretch" id="product-grid">
           @foreach ($produits as $produit) 
            <x-produit :produit="$produit" />
           @endforeach
        </div>

        <button id="load-more" class="mx-auto p-4 bg-white border rounded-md transition-all duration-200 cursor-pointer hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000]">
          Load More
        </button>
      </div>
    </div>
  </section>
</x-layout>
```

### `resources/views/auth/register.blade.php`
```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @font-face {
            font-family: 'mabrypro';
            src: url('{{ asset('fonts/MabryPro-Regular.ttf') }}') format('truetype');
            font-display: swap;
        }
        body { font-family: 'mabrypro', sans-serif; }
    </style>
</head>
<body class="bg-[#f4f4f0]">
    <main>
        <div class="flex justify-between">
            <div class="flex flex-col w-full">
                <div class="flex justify-between w-full px-3">
                    <img class="h-25 mt-2" src="{{ asset('imgs/logo.svg') }}" alt="">
                    <a class="mt-3" href="{{ route('login') }}">Connexion</a>
                </div>
                <div>
                    <p class="pl-9 border-b pb-2 text-[26px]">Rejoignez plus de 1000 utilisateurs</p>
                </div>

                <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="flex md:grid md:grid-cols-2 xl:grid-cols-2 p-6 xl:p-18 gap-2">
                    @csrf
                    @if ($errors->any())
                        <div class="col-span-2 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    
                    <div class="flex flex-col">
                        <label class="text-[17px]" for="nom">Nom</label>
                        <input name="name" class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-13 text-[17px]" type="text" id="nom">
                    </div>
                    <!-- (Email, password, password_confirmation, telephone, ville, pfp fields go here omitted for gist brevity) -->
                   
                    <button type="submit" class="transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-[#FF8E72] hover:text-black hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer mt-3 bg-black text-white h-13 rounded-sm col-span-2">
                        Inscription
                    </button>
                </form>
            </div>
            <div class="hidden xl:block">
                <img class="border-l object-cover min-w-192 h-screen" src="{{ asset('storage/822e112a3b444c69f7ef.svg') }}" alt="">
            </div>
        </div>
    </main>
</body>
</html>
```

### `resources/views/auth/login.blade.php`
```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @font-face {
            font-family: 'mabrypro';
            src: url('{{ asset('fonts/MabryPro-Regular.ttf') }}') format('truetype');
            font-display: swap;
        }
        body { font-family: 'mabrypro', sans-serif; }
    </style>
</head>
<body class="bg-[#f4f4f0]">
    <main>
        <div class="flex justify-between">
            <div class="flex flex-col w-full">
                <div class="flex justify-between w-full px-3">
                    <img class="h-25 mt-2" src="{{ asset('imgs/logo.svg') }}" alt="">
                    <a class="mt-3" href="{{ route('register') }}">Inscription</a>
                </div>
                <div>
                    <p class="pl-9 border-b pb-2 text-[26px]">Connexion</p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="flex flex-col p-7 xl:p-18 gap-6">
                    @csrf
                     @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    <div class="flex flex-col">
                        <label class="text-[17px]" for="email">Email</label>
                        <input class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-13 text-[17px]" type="email" id="email" name="email">
                    </div>
                    <div class="flex flex-col">
                        <label class="text-[17px]" for="password">Mot de passe</label>
                        <input class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-13 text-[17px]" type="password" id="password" name="password">
                        <div class="flex gap-1 justify-between mt-2">
                            <div>
                                <input type="checkbox" id="remember" name="remember" value="1">
                                <label class="text-[17px]" for="remember">Se souvenir de moi</label>
                            </div>
                            <a href="">Mot de passe oublié?</a>
                        </div>
                    </div>
                    <button type="submit" class="transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-[#FF8E72] hover:text-black hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer mt-3 bg-black text-white h-13 rounded-sm">
                        Connexion
                    </button>
                </form>
            </div>
            <div class="hidden lg:block">
                <img class="border-l object-cover min-w-192 h-screen" src="{{ asset('storage/login2.svg') }}" alt="">
            </div>
        </div>
    </main>
</body>
</html>
```

### `resources/views/produit/create.blade.php`
```blade
<x-layoutdash>
    <x-slot:title>Annonces</x-slot:title>
    <x-slot:h1>Mes Annonces</x-slot:h1>
    <x-slot:btnlocation>{{ route('annonces') }}</x-slot:btnlocation>
    <x-slot:btnname>Ajouter une annonce</x-slot:btnname>
    <x-slot:firstc>Tous</x-slot:firstc>
    <x-slot:secondc>Actifs</x-slot:secondc>
    <x-slot:mobbtnlocation>{{ route('produit.create') }}</x-slot:mobbtnlocation>
    <x-slot:mobbtnname>Ajouter une annonce</x-slot:mobbtnname>
    <x-slot:topbar></x-slot:topbar>

    <form action="{{ route('produit.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-2">
        @csrf
        @if ($errors->any())
            <div class="col-span-2 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        
        <div class="flex-col flex md:flex-row gap-3">
            <div class="flex flex-col gap-2 flex-1">
                <div class="flex flex-col">
                    <label class="text-[17px]" for="nom">Titre</label>
                    <input name="titre" class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-13 text-[17px] w-full" type="text" id="nom">
                </div>
                <div class="flex flex-col">
                    <label class="text-[17px]" for="description">Description</label>
                    <textarea name="description" class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm text-[17px] w-full" rows="4" id="description"></textarea>
                </div>
                <div class="flex flex-col">
                    <label class="text-[17px]" for="prix">Prix (MAD)</label>
                    <input name="prix" class="focus:text-[17px] focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-13 text-[17px] w-full" type="number" id="prix">
                </div>
                
                <div class="flex gap-2 hidden lg:flex">
                    <button type="submit" class="transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-[#FF8E72] hover:text-black hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer mt-3 bg-black text-white h-13 rounded-sm w-full">Ajouter le produit</button>
                    <button onclick="window.location.href='{{ route('annonces') }}'" type="reset" class="transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-[#FF8E72] hover:text-black hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer mt-3 bg-black text-white h-13 rounded-sm w-full ">Annuler</button>
                </div>
            </div>
                  
            <div class="flex flex-col gap-2 flex-1">
                <div class="flex flex-col">
                    <label class="text-[17px]" for="categorie">Catégorie</label>
                    <select class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-13 text-[17px] w-full" name="categorie" id="categorie">
                        <option value="">Sélectionnez une categorie</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col">
                    <label class="text-[17px]" for="ville_produit">Ville de produit</label>
                    <select class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-13 text-[17px] w-full" name="ville_produit" id="ville_produit">
                        <option value="">Sélectionnez une ville</option>
                        <option value="Marrakech">Marrakech</option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <label class="text-[17px]" for="etat_produit">État du produit</label>
                    <select class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-13 text-[17px] w-full" name="etat_produit" id="etat_produit">
                        <option value="">Sélectionnez une état</option>
                        <option value="premiere_main">Première main</option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <label class="text-[17px] max-w-42 h-13 flex flex-col items-center justify-center hover:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-7" for="photo">5 Photos du produit</label>
                    <input name="images[]" id="photo" class="w-full" type="file" multiple accept="image/*">
                </div>

                <div class="flex gap-2 lg:hidden">
                    <button type="submit" class="transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-[#FF8E72] hover:text-black hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer mt-3 bg-black text-white h-13 rounded-sm w-full">Ajouter le produit</button>
                    <button onclick="window.location.href='{{ route('annonces') }}'" type="reset" class="transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-[#FF8E72] hover:text-black hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer mt-3 bg-black text-white h-13 rounded-sm w-full ">Annuler</button>
                </div>
            </div>
        </div>
    </form>
</x-layoutdash>
```

### `resources/views/produit/show.blade.php`
```blade
<x-layout>
    <div class="flex flex-col lg:w-2/3 w-full mx-auto h-full overflow-y-auto py-9 px-4 sm:px-0">
        {{-- 1. Image Gallery --}}
        <div x-data="{
            index: 0,
            images: {{ json_encode(collect($produit->images)->map(fn($img) => asset('storage/' . $img))->toArray()) }},
            next() { this.index = (this.index + 1) % (this.images.length || 1) },
            prev() { this.index = (this.index - 1 + (this.images.length || 1)) % (this.images.length || 1) }
        }"
            class="relative bg-white shadow-sm overflow-hidden border border-b-0 h-[400px] md:h-[600px]">

            <template x-if="images && images.length > 1">
                <button @click="next()"
                    class="absolute z-10 right-3 top-1/2 -translate-y-1/2 bg-white border rounded-full hover:bg-gray-100 transition-colors p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </template>

            <template x-if="images && images.length > 1">
                <button @click="prev()"
                    class="absolute z-10 left-3 top-1/2 -translate-y-1/2 bg-white border rounded-full hover:bg-gray-100 transition-colors p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>
            </template>

            <div class="absolute z-10 bottom-4 left-1/2 -translate-x-1/2 bg-transparent">
                <div class="flex gap-2">
                    <template x-for="(img, i) in images" :key="i">
                        <button type="button" class="dot-button transition-transform hover:scale-110"
                            :data-image="i + 1" @click="index = i">
                            <svg class="h-5 w-5" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="50" cy="50" r="45" fill="black" />
                                <circle cx="50" cy="50" r="42" :fill="index === i ? 'black' : 'white'"
                                    class="transition-colors duration-200" />
                            </svg>
                        </button>
                    </template>
                </div>
            </div>

            <template x-if="images && images.length > 0">
                <template x-for="(img, i) in images" :key="i">
                    <img x-show="index === i" :src="img" alt="Product View"
                        class="object-cover h-full w-full" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                </template>
            </template>
        </div>

        {{-- 2. Details & Actions --}}
        <div class="flex flex-col md:flex-row border">
            {{-- Left Side: Details --}}
            <div class="flex flex-col w-full md:w-2/3 border-r-0 md:border-r border-b md:border-b-0">
                <div class="bg-white p-5 lg:p-6 border-b">
                    <h1 class="text-2xl font-bold">{{ $produit->titre }}</h1>
                </div>

                <div class="bg-white flex flex-col sm:flex-row border-b">
                    <div class="p-5 lg:p-6 flex items-center border-b sm:border-b-0 sm:border-r">
                        <div class="inline-block bg-black [clip-path:polygon(0%_0%,_100%_0%,_calc(100%-15px)_50%,_100%_100%,_0%_100%)]">
                            <div class="line-clamp-1 bg-[#FF8E72] text-black font-bold text-lg py-1.5 pl-4 pr-12 [clip-path:polygon(0%_0%,_100%_0%,_calc(100%-15px)_50%,_100%_100%,_0%_100%)]">
                                {{ $produit->prix }} DH
                            </div>
                        </div>
                    </div>

                    <div class="p-5 lg:p-6 flex-1 flex items-center">
                        <div class="flex gap-3 items-center">
                            <img class="h-10 w-10 object-cover rounded-full border border-gray-200"
                                src="{{ $produit->vendeur?->pfp ? asset('storage/' . $produit->vendeur->pfp) : asset('imgs/default.svg') }}"
                                alt="">
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Vendeur</span>
                                <p class="line-clamp-1 underline font-medium">{{ $produit->vendeur->name ?? 'Artisan Anonyme' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-5 lg:p-6 grow">
                    <h2 class="text-sm font-bold uppercase text-gray-500 mb-3">Description</h2>
                    <p class="whitespace-pre-line text-gray-800">{{ $produit->description }}</p>
                </div>
            </div>

            {{-- Right Side: Actions --}}
            <div class="bg-white w-full md:w-1/3 p-5 lg:p-6 flex flex-col gap-4">
                <form action="{{ route('produit.contact', $produit->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex justify-center items-center transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer bg-[#ff8e72] text-black border h-14 rounded-sm w-full gap-2 font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" class="size-6">
                            <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
                            <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
                        </svg>
                        Contacter le vendeur
                    </button>
                </form>

                <div class="flex gap-3 items-center">
                    <button type="button" class="flex-1 flex justify-center items-center transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer bg-white text-black border h-14 rounded-sm gap-2 font-bold">
                        Favoris
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layout>
```

### `resources/views/annonces.blade.php`
```blade
<x-layoutdash>
    <x-slot:title>Annonces</x-slot:title>
    <x-slot:h1>Mes Annonces</x-slot:h1>
    <x-slot:btnlocation>{{ route('annonces') }}</x-slot:btnlocation>
    <x-slot:btnname>Ajouter une annonce</x-slot:btnname>
    <x-slot:firstc>Tous</x-slot:firstc>
    <x-slot:secondc>Actifs</x-slot:secondc>
    <x-slot:mobbtnlocation>{{ route('produit.create') }}</x-slot:mobbtnlocation>
    <x-slot:mobbtnname>Ajouter une annonce</x-slot:mobbtnname>
    <x-slot:topbar></x-slot:topbar>

    <div class="grid grid-cols-1 gap-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 w-full">
        @forelse ($userProduits as $produit)
            <div class="mb-4 last:mb-0 w-full h-auto">
                <x-mylistings :produit="$produit" />
            </div>
        @empty
            <div class="flex flex-col items-center justify-center gap-4 bg-white w-full h-64 rounded-md border-2 border-dashed border-gray-300 p-8 text-center">
                <p class="text-gray-600 font-medium">Il n'y a pas d'annonces actuellement.</p>
                <button onclick="window.location.href='{{ route('produit.create') }}'"
                    class="bg-[#FF8E72] rounded-sm h-11 px-6 border border-black cursor-pointer transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] active:translate-x-0 active:translate-y-0 active:shadow-none">
                    Ajouter une annonce
                </button>
            </div>
        @endforelse
    </div>
</x-layoutdash>
```

---

## STEP 11 — .env Variables

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

---

## STEP 12 — Run Locally

```bash
# Terminal 1
php artisan serve

# Terminal 2 — WebSocket server
php artisan reverb:start

# Terminal 3 — Vite dev server
npm run dev
```

---

## Route Order Reference

| Priority | Method | URI | Controller | Middleware |
|----------|--------|-----|------------|------------|
| 1 | GET | `/` | closure | — |
| 2 | GET | `/register` | `AuthController@create` | guest |
| 3 | POST | `/register` | `AuthController@store` | guest |
| 4 | GET | `/login` | `AuthController@showLogin` | guest |
| 5 | POST | `/login` | `AuthController@authenticate` | guest |
| 6 | POST | `/logout` | `AuthController@logout` | auth |
| 7 | GET | `/produit/create` | `ProduitController@create` | auth |
| 8 | POST | `/produit/store` | `ProduitController@store` | auth |
| 9 | GET | `/produit/{produit:slug}` | `ProduitController@show` | — |
| 10 | POST | `/message/{produit}` | `MessageController@startConversation` | auth |
| 11 | GET | `/message` | closure | auth |
| 12 | GET | `/api/conversations` | `MessageController@index` | auth |
| 13 | GET | `/api/conversations/{id}/messages` | `MessageController@fetchMessages` | auth |
| 14 | POST | `/api/conversations/{id}/messages` | `MessageController@sendMessage` | auth |
| 15 | GET | `/annonces` | `DashController@annonces` | auth |

> **Key rule:** `/produit/create` (line 7) must appear before `/produit/{produit:slug}` (line 9) in `web.php` or Laravel will try to find a product with slug `"create"`.
