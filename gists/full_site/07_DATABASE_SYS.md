---
contents:
  - id: 1
    label: migrations/create_users_table.php
    language: php
  - id: 2
    label: migrations/create_produits_table.php
    language: php
  - id: 3
    label: migrations/create_realtime_messaging_tables.php
    language: php
  - id: 4
    label: migrations/create_favoris_table.php
    language: php
  - id: 5
    label: database/seeders/DatabaseSeeder.php
    language: php
createdAt: 1774833000000
description: Complete database schema including users, products, real-time messaging, and relationship management.
folderId: null
id: 1774833000000
isDeleted: 0
isFavorites: 0
name: 07_DATABASE_SYS
tags: []
updatedAt: 1774833000000
---

## Fragment: migrations/create_users_table.php
# This migration creates the core users table with enhancements for artisanal roles, profile pictures, local geolocation (villes), and unique telephone validation.
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->string('pfp')->nullable(); 
    $table->string('telephone', 20)->unique()->nullable(); 
    $table->string('ville_utilisateur', 100)->default('Marrakech')->index(); 
    $table->boolean('display_phone')->default(false);
    $table->string('role')->default('utilisateur'); 
    $table->timestamp('last_seen_at')->nullable();
    $table->rememberToken();
    $table->timestamps();
});
```

## Fragment: migrations/create_produits_table.php
# This migration defines the artisanal product structure, including category relationships, moderation flags, and the sponsorship system.
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
    $table->string('etat_moderation')->default('en_attente'); 
    $table->string('sponsor_status')->default('none'); 
    $table->dateTime('sponsored_until')->nullable();
    $table->timestamps();
});
```

## Fragment: migrations/create_realtime_messaging_tables.php
# These migrations establish the real-time messaging architecture, linking buyers and sellers through product-specific conversations.
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

## Fragment: migrations/create_favoris_table.php
# This migration implements the polymorphic-like many-to-many relationship for user wishlists.
```php
Schema::create('favoris', function (Blueprint $table) {
    $table->foreignId('utilisateur_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('produit_id')->constrained('produits')->cascadeOnDelete();
    $table->primary(['utilisateur_id', 'produit_id']);
    $table->timestamps();
});
```

## Fragment: database/seeders/DatabaseSeeder.php
# This file is used to provide initial data or development records for testing the platform's features.
```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Example: Create Admin User
        User::factory()->create([
            'name' => 'Admin Artisan',
            'email' => 'admin@lartisan.ma',
            'role' => 'admin',
        ]);
        
        // Add categories, cities, and initial products here...
    }
}
```
