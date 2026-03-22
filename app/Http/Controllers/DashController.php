<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;

class DashController extends Controller
{
     public function annonces() {
        $userProduits = Produit::where('vendeur_id', auth()->id())
    ->latest()
    ->get();
        return view('annonces', compact('userProduits'));
    }
}
