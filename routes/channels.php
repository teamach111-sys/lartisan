<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('messenger.{conversationId}', function ($user, $conversationId) {
    $conversation = \App\Models\Conversation::where('id', (int) $conversationId)->first();

    if (!$conversation) {
        return false;
    }

    return (int) $user->id === (int) $conversation->acheteur_id || 
           (int) $user->id === (int) $conversation->produit->vendeur_id;
});

Broadcast::channel('chat.presence', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
