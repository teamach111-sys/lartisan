---
description: L'Artisan Marketplace - Authentication & User Management
---

# Auth & User Management

This module handles user registration, secure login, password recovery, and profile customization (including city selection and PFP uploads).

## 1. Authentication Controller
### `app/Http/Controllers/AuthController.php`
Handles the core logic for registration (with image compression) and session management.

```php
public function store(Request $request)
{
    $validated = $request->validate([...]);

    $user = new User();
    $user->name = $validated['name'];
    $user->email = $validated['email'];
    $user->password = Hash::make($validated['password']);
    $user->ville_utilisateur = $validated['ville_utilisateur'];

    if ($request->hasFile('pfp')) {
        $user->pfp = ImageHelper::compressAndStore($request->file('pfp'), 'profiles');
    }

    $user->save();
    Auth::login($user);
    return redirect('/')->with('success', 'Bienvenue !');
}
```

## 2. Profile Management
### `app/Http/Controllers/DashController.php@updateProfil`
Allows users to update their personal info, change passwords, and toggle phone number visibility.

```php
public function updateProfil(Request $request)
{
    $user = auth()->user();
    $validated = $request->validate([...]);

    $user->fill($validated);
    $user->display_phone = $request->has('display_phone');

    if ($request->filled('password')) {
        $user->password = Hash::make($validated['password']);
    }

    if ($request->hasFile('pfp')) {
        $user->pfp = ImageHelper::compressAndStore($request->file('pfp'), 'pfps');
    }

    $user->save();
    return back()->with('success', 'Profil mis à jour !');
}
```

## 3. Real-time UX: Last Seen
### `app/Http/Middleware/UpdateLastSeen.php`
A simple middleware that updates the `last_seen_at` timestamp on every request to show "Online" status in the chat.

```php
public function handle(Request $request, Closure $next)
{
    if (auth()->check()) {
        auth()->user()->update(['last_seen_at' => now()]);
    }
    return $next($request);
}
```

## 4. Auth Views
- **Login**: `resources/views/auth/login.blade.php`
- **Register**: `resources/views/auth/register.blade.php`
- **Reset Password**: `resources/views/auth/reset-password.blade.php`
