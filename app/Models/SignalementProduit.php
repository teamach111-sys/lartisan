<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignalementProduit extends Model
{
    /** @use HasFactory<\Database\Factories\SignalementProduitFactory> */
    use HasFactory;
    protected $fillable = [
    'produit_id', 
    'raison' 
    // 'utilisateur_id' sera injecté via auth()->id() dans le contrôleur
];

public function produit() {
    return $this->belongsTo(Produit::class);
}
}
