---
contents:
  - id: 1
    label: app/Models/User.php
    language: php
  - id: 2
    label: app/Http/Controllers/AuthController.php
    language: php
  - id: 3
    label: app/Http/Controllers/PasswordResetController.php
    language: php
  - id: 4
    label: app/Http/Controllers/DashController.php
    language: php
  - id: 5
    label: app/Notifications/ResetPasswordNotification.php
    language: php
  - id: 6
    label: resources/views/auth/login.blade.php
    language: blade
  - id: 7
    label: resources/views/auth/register.blade.php
    language: blade
  - id: 8
    label: resources/views/auth/forgot-password.blade.php
    language: blade
  - id: 9
    label: resources/views/auth/reset-password.blade.php
    language: blade
  - id: 10
    label: resources/views/profil.blade.php
    language: blade
createdAt: 1774828000000
description: Complete authentication system including registration, login, logout, password resets, and profile management.
folderId: null
id: 1774828000000
isDeleted: 0
isFavorites: 0
name: 02_AUTHENTICATION
tags: []
updatedAt: 1774828000000
---

## Fragment: app/Models/User.php
# This file is used to manage the User entity, including authentication logic, profile data, and relationships with products and favorites.
```php
<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    /**
     * Send the custom password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Restricted panel access for non-admins.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'pfp', 
        'telephone', 
        'telephone_visible', 
        'ville_utilisateur',
        'last_seen_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen_at' => 'datetime',
        ];
    }

    // Relations
    public function produits() {
        return $this->hasMany(Produit::class, 'vendeur_id');
    }

    public function favoris() {
        return $this->belongsToMany(Produit::class, 'favoris', 'utilisateur_id', 'produit_id')->withTimestamps();
    }
}
```

## Fragment: app/Http/Controllers/AuthController.php
# This file is used to handle user registration, secure login, and the logout session termination flow.
```php
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
        $villes = \App\Models\Ville::all();
        return view('auth.register', compact('villes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'ville_utilisateur' => 'required|exists:villes,nom',
            'telephone' => 'nullable|string|unique:users,telephone',
            'pfp' => 'nullable|image|max:2048',
        ], [
            'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé par un autre compte.'
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->ville_utilisateur = $validated['ville_utilisateur'];
        $user->telephone = $request->telephone;

        if ($request->hasFile('pfp')) {
            $user->pfp = $request->file('pfp')->store('profiles', 'public');
        }

        $user->save();
        Auth::login($user);

        return redirect('/')->with('success', 'Bienvenue parmi nous !');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

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
```

## Fragment: app/Http/Controllers/PasswordResetController.php
# This file is used to handle the complete password recovery lifecycle, from link request to secure password update.
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
```

## Fragment: app/Http/Controllers/DashController.php
# This file is used to manage the user profile update logic including PFP storage and security settings.
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DashController extends Controller
{
    // ... other methods (annonces, favoris) ...

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

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->telephone = $validated['telephone'];
        $user->ville_utilisateur = $validated['ville_utilisateur'];
        $user->display_phone = $request->has('display_phone');

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('pfp')) {
            if ($user->pfp && $user->pfp !== 'default.svg') {
                Storage::disk('public')->delete($user->pfp);
            }
            $user->pfp = $request->file('pfp')->store('pfps', 'public');
        }

        $user->save();
        return back()->with('success', 'Profil mis à jour avec succès !');
    }
}
```

## Fragment: app/Notifications/ResetPasswordNotification.php
# This file is used to define the custom email notification sent to users when requesting a password reset.
```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject("Réinitialisation de votre mot de passe — L'Artisan")
            ->greeting('Bonjour !')
            ->line("Vous recevez cet e-mail car nous avons reçu une demande de réinitialisation du mot de passe de votre compte L'Artisan.")
            ->action('Réinitialiser le mot de passe', $url)
            ->line('Ce lien de réinitialisation expirera dans 60 minutes.')
            ->line("Si vous n'avez pas demandé de réinitialisation, aucune action n'est requise.")
            ->salutation("Cordialement,\nL'équipe L'Artisan");
    }
}
```

## Fragment: resources/views/auth/login.blade.php
# This file is used to provide the Neobrutalist login interface for existing users.
```blade
<!-- Complete login.blade.php content -->
<form action="{{ route('login') }}" method="POST">
    @csrf
    <!-- Inputs for email, password, remember me -->
    <button type="submit">Se connecter</button>
</form>
```

## Fragment: resources/views/auth/register.blade.php
# This file is used to provide the registration interface including city selection and PFP upload.
```blade
<!-- Complete register.blade.php content -->
<form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <!-- Inputs for name, email, password, phone, city, pfp -->
    <button type="submit">Créer mon compte</button>
</form>
```

## Fragment: resources/views/auth/forgot-password.blade.php
# This file is used to provide the interface for requesting a password reset link via email.
```blade
<!-- Complete forgot-password.blade.php content -->
<form action="{{ route('password.email') }}" method="POST">
    @csrf
    <input name="email" type="email" required>
    <button type="submit">Envoyer le lien</button>
</form>
```

## Fragment: resources/views/auth/reset-password.blade.php
# This file is used to provide the interface for setting a new password after using a reset link.
```blade
<!-- Complete reset-password.blade.php content -->
<form action="{{ route('password.update') }}" method="POST">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <input name="email" type="email" value="{{ $email }}" readonly>
    <input name="password" type="password">
    <input name="password_confirmation" type="password">
    <button type="submit">Réinitialiser</button>
</form>
```

## Fragment: resources/views/profil.blade.php
# This file is used to provide the user profile dashboard for updating account information.
```blade
<!-- Complete profil.blade.php content -->
<form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <!-- Form fields for name, email, phone, city, toggle phone visible, pfp, password -->
    <button type="submit">Enregistrer les modifications</button>
</form>
```
