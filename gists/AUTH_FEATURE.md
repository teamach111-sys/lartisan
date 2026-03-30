---
contents:
  - id: 1
    label: database/migrations/xxxx_xx_xx_xxxxxx_create_users_table.php
    language: php
  - id: 2
    label: app/Models/User.php
    language: php
  - id: 3
    label: app/Http/Controllers/AuthController.php
    language: php
  - id: 4
    label: routes/web.php
    language: php
  - id: 5
    label: resources/views/auth/register.blade.php
    language: blade
  - id: 6
    label: resources/views/auth/login.blade.php
    language: blade
createdAt: 1774641546580
description: null
folderId: null
id: 1774641546580
isDeleted: 0
isFavorites: 0
name: AUTH_FEATURE
tags: []
updatedAt: 1774641546580
---

## Fragment: database/migrations/xxxx_xx_xx_xxxxxx_create_users_table.php
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('pfp')->nullable();             // Profile picture path
            $table->string('telephone', 20)->nullable();
            $table->string('ville_utilisateur', 100)->default('Marrakech')->index();
            $table->string('statut_compte')->default('actif');
            $table->string('role')->default('utilisateur');
            $table->timestamp('last_seen_at')->nullable();  // For online status
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
```

## Fragment: app/Models/User.php
```php
<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Helpers\ImageHelper;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

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
        'display_phone',
        'ville_utilisateur',
        'last_seen_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen_at' => 'datetime',
            'display_phone' => 'boolean',
        ];
    }

    public function getPfpUrlAttribute(): string
    {
        return ImageHelper::getUrl($this->pfp);
    }

    public function produits()
    {
        return $this->hasMany(Produit::class, 'vendeur_id');
    }

    public function favoris()
    {
        return $this->belongsToMany(Produit::class, 'favoris', 'utilisateur_id', 'produit_id')->withTimestamps();
    }
}
```

## Fragment: app/Http/Controllers/AuthController.php
```php
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
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'ville_utilisateur' => 'required|string',
            'telephone' => 'required|string',
            'pfp' => 'nullable|image|max:2048',
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->ville_utilisateur = $validated['ville_utilisateur'];
        $user->telephone = $validated['telephone'];

        if ($request->hasFile('pfp')) {
            $user->pfp = ImageHelper::compressAndStore($request->file('pfp'), 'avatars');
        }

        $user->save();
        Auth::login($user);
        return redirect('/')->with('success', 'Bienvenue parmi nous !');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Les identifiants ne correspondent pas à nos enregistrements.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Vous avez été déconnecté.');
    }
}
```

## Fragment: routes/web.php
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/register', [AuthController::class, 'create'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'store'])->middleware('guest');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post')->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
```

## Fragment: resources/views/auth/register.blade.php
```blade
<form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <!-- Input fields here -->
    <button type="submit">Inscription</button>
</form>
```

## Fragment: resources/views/auth/login.blade.php
```blade
<form action="{{ route('login') }}" method="POST">
    @csrf
    <input type="email" name="email">
    <input type="password" name="password">
    <input type="checkbox" name="remember" value="1">
    <button type="submit">Connexion</button>
</form>
```

