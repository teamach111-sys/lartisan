<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignalementProduit extends Model
{
    use HasFactory;

    protected $table = 'signalement_produits';

    protected $fillable = [
        'produit_id',
        'produit_nom',
        'utilisateur_id',
        'type_signalement',
        'details',
        'est_traite',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
}
