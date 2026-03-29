<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ImageHelper;

class DashController extends Controller
{
     public function annonces(Request $request) {
        $filter = $request->query('status', 'tous');
        $query = Produit::where('vendeur_id', auth()->id())->latest();
        
        if ($filter !== 'tous') {
            if ($filter === 'sponsorise') {
                $query->where('sponsor_status', 'approuve');
            } else {
                $query->where('etat_moderation', $filter);
            }
        }
        
        $userProduits = $query->get();
        return view('annonces', compact('userProduits', 'filter'));
    }

    public function favoris(Request $request) {
        $sort = $request->query('sort', 'latest');
        $query = auth()->user()->favoris();
        
        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }
        
        $items = $query->get();
        return view('favoris', compact('items', 'sort'));
    }

    public function profil()
    {
        return view('profil');
    }

    public function updateProfil(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string|max:20|unique:users,telephone,' . $user->id,
            'ville_utilisateur' => 'required|exists:villes,nom',
            'display_phone' => 'nullable|boolean',
            'pfp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé par un autre compte.'
        ]);

        // Basic Profile Info
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->telephone = $validated['telephone'];
        $user->ville_utilisateur = $validated['ville_utilisateur'];
        $user->display_phone = $request->has('display_phone');

        // Handle Password
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        // Handle PFP (Profile Picture)
        if ($request->hasFile('pfp')) {
            // Delete old PFP if exists and it's not the default
            if ($user->pfp && $user->pfp !== 'default.svg') {
                Storage::disk('public')->delete($user->pfp);
            }
            $user->pfp = ImageHelper::compressAndStore($request->file('pfp'), 'pfps');
        }

        $user->save();

        return back()->with('success', 'Profil mis à jour avec succès !');
    }
}
