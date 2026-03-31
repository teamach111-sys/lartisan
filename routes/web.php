<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashController;
use App\Http\Controllers\ProduitController;
use App\Models\Produit;
use App\Http\Controllers\MessageController;


use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

route::get('/produit/create', [ProduitController::class, 'create'])->name('produit.create')->middleware('auth');
route::post('/produit/store', [ProduitController::class, 'store'])->name('produit.store')->middleware('auth');

// Display the extensive product page using the unique slug for SEO-friendly URLs.
Route::get('/produit/{produit:slug}', [ProduitController::class, 'show'])->name('produit.show');
// Creates or finds a conversation between the currently logged-in user and the seller, then redirects to the messaging UI.
Route::post('/message/{produit}', [MessageController::class, 'startConversation'])
    ->name('produit.contact')
    ->middleware('auth'); // Only logged-in users can initiate a chat.





Route::get('/message', function () {
    $preloadedConversation = null;
    $convId = request()->query('conversation');
    
    if ($convId) {
        $conversation = \App\Models\Conversation::with(['produit.vendeur', 'acheteur'])->find($convId);
        
        if ($conversation) {
            $userId = auth()->id();
            // Security: only allow if user is part of this conversation
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
Route::middleware('auth')->group(function () {

    // 1. Fetch the master list of all conversations for the logged-in user to populate the left sidebar.
    Route::get('/api/conversations', [MessageController::class, 'index']);
    
    // 2. Fetch the detailed historical array of messages for a single specific conversation.
    Route::get('/api/conversations/{conversation}/messages', [MessageController::class, 'fetchMessages']);
    
    // 3. Send a new message into a specific conversation.
    Route::post('/api/conversations/{conversation}/messages', [MessageController::class, 'sendMessage']);
    
    // 4. Delete a conversation
    Route::delete('/api/conversations/{conversation}', [MessageController::class, 'destroy']);

    // Favorites
    Route::get('/favoris', [DashController::class, 'favoris'])->name('favoris');
    Route::post('/produit/{produit}/favorite', [ProduitController::class, 'toggleFavorite'])->name('produit.favorite');
    Route::post('/produit/{produit}/signaler', [ProduitController::class, 'signaler'])->name('produit.signaler');
    Route::post('/produit/{produit}/sponsoriser', [ProduitController::class, 'demanderSponsor'])->name('produit.sponsoriser');

    // Profile
    Route::get('/profil', [DashController::class, 'profil'])->name('profil');
    Route::post('/profil', [DashController::class, 'updateProfil'])->name('profil.update');

    // Blocking
    Route::post('/api/block/{user}', [\App\Http\Controllers\BlockController::class, 'block']);
    Route::post('/api/unblock/{user}', [\App\Http\Controllers\BlockController::class, 'unblock']);
});

Route::get('/register', [AuthController::class, 'create'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'store'])->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post')->middleware('guest');

// Password Reset
use App\Http\Controllers\PasswordResetController;
Route::get('/mot-de-passe-oublie', [PasswordResetController::class, 'showForgotForm'])->name('password.request')->middleware('guest');
Route::post('/mot-de-passe-oublie', [PasswordResetController::class, 'sendResetLink'])->name('password.email')->middleware('guest');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset')->middleware('guest');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update')->middleware('guest');

route::get('/annonces', [DashController::class, 'annonces'])->name('annonces')->middleware('auth');
Route::delete('/produit/{produit}', [ProduitController::class, 'destroy'])->name('produit.destroy')->middleware('auth');
Route::get('/produit/{produit}/edit', [ProduitController::class, 'edit'])->name('produit.edit')->middleware('auth');
Route::put('/produit/{produit}', [ProduitController::class, 'update'])->name('produit.update')->middleware('auth');

// Storage Proxy for Admin/Filament to bypass CORS on cloud storage
Route::get('/admin-proxy/storage/{path}', function ($path) {
    if (!auth()->check()) abort(403);
    $disk = \Illuminate\Support\Facades\Storage::disk(config('filesystems.default', 'lartisan'));
    if (!$disk->exists($path)) abort(404);
    $file = $disk->get($path);
    $type = $disk->mimeType($path);
    return response($file, 200)->header('Content-Type', $type);
})->where('path', '.*')->name('admin.storage.proxy')->middleware('auth');

// Centre d'Aide (Help Center)
Route::get('/centre-aide', function () {
    return view('centre-aide');
})->name('centre-aide');
