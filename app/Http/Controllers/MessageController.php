<?php

namespace App\Http\Controllers;
use App\Models\Conversation;
use App\Models\Produit;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function startConversation(Produit $produit)
{
    // A seller cannot chat with themselves to buy their own product.
    if ($produit->vendeur_id === auth()->id()) {
        return back()->with('error', 'You cannot contact yourself.');
    }

    // `firstOrCreate` will query the database for an existing chat between this buyer for this specific product. 
    // If it doesn't exist, it handles creating it simultaneously.
    $conversation = Conversation::firstOrCreate([
        'produit_id' => $produit->id,
        'acheteur_id' => auth()->id(),
    ]);

    // Redirect the user into the universal messaging center, passing along the newly acquired conversation ID as a URL parameter so the Javascript can auto-open it.
    return redirect()->route('message', ['conversation' => $conversation->id]);
}

public function sendMessage(Request $request, Conversation $conversation)
{
    // Security check: must be part of the conversation
    if ($conversation->acheteur_id !== auth()->id() && $conversation->produit?->vendeur_id !== auth()->id()) {
        abort(403);
    }

    $partner = auth()->id() === $conversation->acheteur_id
        ? $conversation->produit?->vendeur
        : $conversation->acheteur;

    if (auth()->user()->isBlockedBy($partner->id) || auth()->user()->hasBlocked($partner->id)) {
        return response()->json(['message' => 'Vous ne pouvez pas envoyer de messages à cet utilisateur car l\'un de vous a bloqué l\'autre.'], 403);
    }

    $request->validate([
        'contenu' => 'required|string',
    ]);

    $message = $conversation->messages()->create([
        'expediteur_id' => auth()->id(),
        'contenu' => $request->contenu,
    ]);

    broadcast(new \App\Events\MessageSent($message))->toOthers();
    \Illuminate\Support\Facades\Log::info('Message broadcasting triggered', ['id' => $message->id]);

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

    // Buyer: always sees their own conversations (they initiated them).
    $buyerConversations = Conversation::with(['produit.vendeur', 'acheteur', 'messages' => function($q) {
        $q->orderBy('created_at', 'desc');
    }])
    ->where('acheteur_id', $userId)
    ->get();

    // Seller: only sees conversations where at least one message has been sent.
    $sellerConversations = Conversation::with(['produit.vendeur', 'acheteur', 'messages' => function($q) {
        $q->orderBy('created_at', 'desc');
    }])
    ->whereHas('produit', function ($query) use ($userId) {
        $query->where('vendeur_id', $userId);
    })
    ->where('acheteur_id', '!=', $userId) // Avoid duplicates if viewing own products
    ->whereHas('messages') // Only show if at least one message exists
    ->get();

    $conversations = $buyerConversations->merge($sellerConversations)
    ->map(function ($conversation) use ($userId) {
        // Safe relational extraction ensuring we gracefully handle deleted products or users
        $partner = $userId === $conversation->acheteur_id
            ? $conversation->produit?->vendeur
            : $conversation->acheteur;

        $latestMessage = $conversation->messages->first();

        $unreadCount = $conversation->messages()
            ->where('est_lu', false)
            ->where('expediteur_id', '!=', $userId)
            ->count();

        // Extremely safe timestamp fetch to avoid 'getTimestamp on null' errors inside Cloud DB
        $sortTime = 0;
        if ($latestMessage && $latestMessage->created_at) {
            $sortTime = $latestMessage->created_at->timestamp;
        } elseif ($conversation->created_at) {
            $sortTime = $conversation->created_at->timestamp;
        } else {
            $sortTime = time(); 
        }

        return [
            'id'           => $conversation->id,
            'produit_id'   => $conversation->produit_id,
            'produit_nom'  => $conversation->produit?->titre ?? 'Produit Supprimé',
            'produit_slug' => $conversation->produit?->slug ?? '',
            'partner_id'   => $partner?->id ?? null,
            'partner_name' => $partner?->name ?? 'Utilisateur Inconnu',
            'partner_pfp'  => $partner?->pfp ? asset('storage/' . $partner->pfp) : 'https://ui-avatars.com/api/?name=' . urlencode($partner?->name ?? 'U'),
            'auth_pfp'     => auth()->user()->pfp ? asset('storage/' . auth()->user()->pfp) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name ?? 'U'),
            'latest_message' => $latestMessage ? $latestMessage->contenu : 'Nouvelle conversation',
            'latest_time'    => ($latestMessage && $latestMessage->created_at) ? $latestMessage->created_at->format('H:i') : '',
            'unread_count'   => $unreadCount,
            'is_online'      => $partner && $partner->last_seen_at && ($partner->last_seen_at instanceof \Carbon\Carbon ? $partner->last_seen_at : \Carbon\Carbon::parse($partner->last_seen_at))->gt(now()->subMinutes(5)),
            'is_blocked'     => auth()->user()->hasBlocked($partner?->id ?? 0),
            'blocked_by'     => auth()->user()->isBlockedBy($partner?->id ?? 0),
            'sort_time'      => $sortTime,
        ];
    })
    ->sortByDesc('sort_time')
    ->values(); // Crucial: Re-indexes the collection so it converts to a JSON Array, not a JSON Object

    return response()->json($conversations);
}

public function fetchMessages(Conversation $conversation)
{
    // Strict Database security check - Ensures an attacker cannot API fetch a conversation belonging to two other people!
    // Safely evaluates via null-safe operators in case the product was deleted
    if ($conversation->acheteur_id !== auth()->id() && $conversation->produit?->vendeur_id !== auth()->id()) {
        abort(403);
    }

    // Forceful bulk update: if they are fetching the chat history, it means they are looking at it on screen. Mark all incoming unread chats as read identically to Whatsapp!
    $conversation->messages()
        ->where('expediteur_id', '!=', auth()->id())
        ->where('est_lu', false)
        ->update(['est_lu' => true]);

    // Output all messages in chronological sequential order.
    $messages = $conversation->messages()->orderBy('created_at', 'asc')->get()
        ->map(function ($msg) {
            return [
                'id' => $msg->id,
                'expediteur_id' => $msg->expediteur_id,
                'contenu' => $msg->contenu,
                'file_path' => $msg->file_path, // Extremely crucial for images
                'time' => $msg->created_at->format('H:i')
            ];
        });

    $partner = auth()->id() === $conversation->acheteur_id
        ? $conversation->produit->vendeur
        : $conversation->acheteur;

    return response()->json([
        'messages' => $messages,
        'partner_id' => $partner?->id ?? null,
        'is_blocked' => auth()->user()->hasBlocked($partner?->id ?? 0),
        'blocked_by' => auth()->user()->isBlockedBy($partner?->id ?? 0)
    ]);
}

public function destroy(Conversation $conversation)
{
    // Strict Database security check
    if ($conversation->acheteur_id !== auth()->id() && $conversation->produit?->vendeur_id !== auth()->id()) {
        abort(403);
    }

    $conversation->delete();

    return response()->json(['success' => true]);
}
}
