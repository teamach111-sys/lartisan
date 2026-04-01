---
description: L'Artisan Marketplace - Database Schema & Seeders
---

# Database Schema & Seeders

This module provides the complete database structure, optimized for MySQL with proper indexing for search and moderation.

## 1. Core Users Table
### `database/migrations/0001_01_01_000000_create_users_table.php`
Includes custom fields for artisanal roles, profile pictures, and contact information.

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->string('pfp')->nullable(); 
    $table->string('telephone', 20)->unique()->nullable(); 
    $table->boolean('telephone_visible')->default(true);
    $table->string('ville_utilisateur', 100)->default('Marrakech')->index(); 
    $table->string('statut_compte')->default('actif'); 
    $table->string('role')->default('utilisateur'); 
    $table->timestamp('last_seen_at')->nullable();
    $table->timestamp('email_verified_at')->nullable();
    $table->rememberToken();
    $table->timestamps();
});
```

## 2. Market Engine: Products & Categories
### `database/migrations/2026_02_11_112818_create_produits_table.php`
The core marketplace table with JSON image storage and moderation status.

```php
Schema::create('produits', function (Blueprint $table) {
    $table->id();
    $table->foreignId('vendeur_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('categorie_id')->nullable()->constrained('categories')->nullOnDelete();
    $table->string('titre');
    $table->string('slug')->unique()->index();
    $table->text('description')->nullable();
    $table->decimal('prix', 12, 2)->index();
    $table->string('ville_produit', 100)->index();
    $table->json('images')->nullable();
    $table->string('etat_produit')->default('neuf'); 
    $table->string('etat_moderation')->default('en_attente'); 
    $table->boolean('is_sponsored')->default(false);
    $table->timestamp('sponsored_until')->nullable();
    $table->timestamps();
});
```

### `database/migrations/2026_02_11_112808_create_categories_table.php`
```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('nom')->unique();
    $table->string('slug')->unique();
    $table->string('icon')->nullable(); 
    $table->timestamps();
});
```

## 3. Real-time Messaging Subsystem
Establishing the relationship between buyers, sellers, and product-specific threads.

```php
// Conversations Table
Schema::create('conversations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('produit_id')->constrained('produits')->cascadeOnDelete();
    $table->foreignId('acheteur_id')->constrained('users')->cascadeOnDelete();
    $table->timestamp('last_message_at')->nullable()->index(); 
    $table->unique(['produit_id', 'acheteur_id']);
    $table->timestamps();
});

// Messages Table
Schema::create('messages', function (Blueprint $table) {
    $table->id();
    $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();
    $table->foreignId('expediteur_id')->constrained('users');
    $table->text('contenu');
    $table->boolean('est_lu')->default(false)->index(); 
    $table->timestamps();
});
```

## 4. Moderation & Trust Systems
### `database/migrations/..._create_blocked_users_table.php`
```php
Schema::create('blocked_users', function (Blueprint $table) {
    $table->id();
    $table->foreignId('blocker_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('blocked_id')->constrained('users')->cascadeOnDelete();
    $table->unique(['blocker_id', 'blocked_id']);
    $table->timestamps();
});
```

## 5. Deployment Seeds
### `database/seeders/DatabaseSeeder.php`
Provides the initial admin account and system categories.

```php
public function run(): void
{
    // Admin User
    \App\Models\User::factory()->create([
        'name' => 'Admin Artisan',
        'email' => 'admin@lartisan.ma',
        'role' => 'admin',
    ]);

    // Root Seeders
    $this->call([
        VilleSeeder::class,
    ]);
}
```
