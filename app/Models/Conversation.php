<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    /** @use HasFactory<\Database\Factories\ConversationFactory> */
    use HasFactory;
    protected $fillable = ['produit_id', 'acheteur_id'];

// Relations
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
