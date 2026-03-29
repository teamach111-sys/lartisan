<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ImageHelper;

class AuthController extends Controller
{
    public function create()
    {
        $villes = \App\Models\Ville::all();
        return view('auth.register', compact('villes'));
    }

public function store(Request $request)
    {
        // Validation stricte
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed', // Vérifie password_confirmation
            'ville_utilisateur' => 'required|exists:villes,nom',
            'telephone' => 'nullable|string|unique:users,telephone',
            'pfp' => 'nullable|image|max:2048', // 2MB max
        ], [
            'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé par un autre compte.'
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
            $user->pfp = ImageHelper::compressAndStore($request->file('pfp'), 'profiles');
        }

        $user->save();

        // Connecter l'utilisateur immédiatement
        Auth::login($user);

        // Redirection vers l'accueil avec un message flash
        return redirect('/')->with('success', 'Bienvenue parmi nous !');
    }

    public function logout(Request $request)
    {
        // Déconnecte l'utilisateur du garde (guard) actuel
        Auth::logout();

        // Invalide la session de l'utilisateur pour effacer les données
        $request->session()->invalidate();

        // Régénère le jeton CSRF pour éviter les attaques après déconnexion
        $request->session()->regenerateToken();

        // Redirige vers la page d'accueil ou de connexion
        return redirect('/')->with('success', 'Vous avez été déconnecté.');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        if (Auth::attempt($credentials, $request->remember)) {
            request()->session()->regenerate();
            return redirect()->intended('/');
        }
        
        return back()->withErrors([
            'email' => 'Les identifiants ne correspondent pas à nos enregistrements.',
        ]);
    }
    public function showlogin()
    {
        return view('auth.login');
    }
}
