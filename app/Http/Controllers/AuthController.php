<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

public function store(Request $request)
    {
        // Validation stricte
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed', // Vérifie password_confirmation
            'ville_utilisateur' => 'required|string',
            'telephone' => 'nullable|string',
            'pfp' => 'nullable|image|max:2048', // 2MB max
        ]);

        // Création de l'objet User
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->ville_utilisateur = $validated['ville_utilisateur'];
        $user->telephone = $request->telephone;

        // Stockage de la photo si présente
        if ($request->hasFile('pfp')) {
            $user->pfp = $request->file('pfp')->store('profiles', 'public');
        }

        $user->save();

        // Connecter l'utilisateur immédiatement
        Auth::login($user);

        // Redirection vers l'accueil avec un message flash
        return redirect('/')->with('success', 'Bienvenue parmi nous !');
    }
}
