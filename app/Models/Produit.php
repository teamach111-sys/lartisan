<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produit extends Model
{
    use HasFactory;
    
    protected $fillable = [
    'categorie_id', // Choisi via dropdown
    'titre', 
    'slug', 
    'description', 
    'images', 
    'prix', 
    'ville_produit', 
    'telephone_visible',
    'etat_produit',
    'etat_moderation',
    'vendeur_id',
    'sponsor_status',
    'sponsored_until'
];

protected $casts = [
    'images' => 'array',
    'prix' => 'decimal:2',
    'sponsored_until' => 'datetime'
];

// Relations
public function vendeur() {
    return $this->belongsTo(User::class, 'vendeur_id');
}

public function categorie() {
    return $this->belongsTo(Categorie::class, 'categorie_id');
}
}
