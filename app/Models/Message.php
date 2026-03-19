<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /** @use HasFactory<\Database\Factories\MessageFactory> */
    use HasFactory;
    protected $fillable = [
    'conversation_id', 
    'expediteur_id', 
    'contenu'
    // 'est_lu' est retiré : il est 'false' par défaut
];

// Relations
public function conversation() {
    return $this->belongsTo(Conversation::class);
}

public function expediteur() {
    return $this->belongsTo(User::class, 'expediteur_id');
}
}
