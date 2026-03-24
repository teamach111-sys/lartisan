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
    if ($conversation->acheteur_id !== auth()->id() && $conversation->produit->vendeur_id !== auth()->id()) {
        abort(403);
    }

    $request->validate([
        'contenu' => 'required|string',
    ]);

    $message = $conversation->messages()->create([
        'expediteur_id' => auth()->id(),
        'contenu' => $request->contenu,
    ]);

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
    
    // Execute a massive relationship map fetching the messages ordered by timing.
    $conversations = Conversation::with(['produit.vendeur', 'acheteur', 'messages' => function($q) {
        $q->orderBy('created_at', 'desc');
    }])
    ->where('acheteur_id', $userId)
    ->orWhereHas('produit', function ($query) use ($userId) {
        $query->where('vendeur_id', $userId);
    })
    ->get()
    ->map(function ($conversation) use ($userId) {
        // Automatically determine if the user on the other side of the screen is the buyer or seller relative to you.
        $partner = $userId === $conversation->acheteur_id 
            ? $conversation->produit->vendeur 
            : $conversation->acheteur;

        $latestMessage = $conversation->messages->first();

        // Calculate unread message badge count
        $unreadCount = $conversation->messages()
            ->where('est_lu', false)
            ->where('expediteur_id', '!=', $userId)
            ->count();

        // Return a perfectly formatted associative array that the javascript will easily digest.
        return [
            'id' => $conversation->id,
            'produit_id' => $conversation->produit_id,
            'produit_nom' => $conversation->produit->titre ?? 'Produit',
            'produit_slug' => $conversation->produit->slug ?? '', // Needed for URL linking
            'partner_name' => $partner->name ?? 'Inconnu',
            'partner_pfp' => $partner->pfp ? asset('storage/' . $partner->pfp) : 'https://ui-avatars.com/api/?name=' . urlencode($partner->name ?? 'U'),
            'auth_pfp' => auth()->user()->pfp ? asset('storage/' . auth()->user()->pfp) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name ?? 'U'),
            'latest_message' => $latestMessage ? $latestMessage->contenu : 'Nouvelle conversation',
            'latest_time' => $latestMessage ? $latestMessage->created_at->format('H:i') : '',
            'unread_count' => $unreadCount
        ];
    });

    return response()->json($conversations);
}

public function fetchMessages(Conversation $conversation)
{
    // Strict Database security check - Ensures an attacker cannot API fetch a conversation belonging to two other people!
    if ($conversation->acheteur_id !== auth()->id() && $conversation->produit->vendeur_id !== auth()->id()) {
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

    return response()->json($messages);
}
}
