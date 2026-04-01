---
description: L'Artisan Marketplace - Eloquent Models & Business Logic
---

# Eloquent Models & Logic

This module contains the domain models and their relationships, including custom accessors for image handling and messaging logic.

## 1. User Model
### `app/Models/User.php`
Handles authentication, artisanal roles, and profile picture resolution via the specialized `ImageHelper`.

```php
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'pfp', 'telephone', 'telephone_visible', 'ville_utilisateur', 'last_seen_at'];

    // Accessor for Image resolution (Local or S3/R2)
    public function getPfpUrlAttribute()
    {
        return \App\Helpers\ImageHelper::getUrl($this->pfp);
    }

    // Relationships
    public function produits() { return $this->hasMany(Produit::class, 'vendeur_id'); }
    public function favoris() { return $this->belongsToMany(Produit::class, 'favoris', 'utilisateur_id', 'produit_id')->withTimestamps(); }
    public function blockedUsers() { return $this->belongsToMany(User::class, 'blocked_users', 'blocker_id', 'blocked_id'); }
}
```

## 2. Produit Model
### `app/Models/Produit.php`
Includes support for JSON image arrays, sponsorship status, and slug-based routing.

```php
class Produit extends Model
{
    use HasFactory;
    
    protected $fillable = ['categorie_id', 'titre', 'slug', 'description', 'images', 'prix', 'ville_produit', 'telephone_visible', 'etat_produit', 'etat_moderation', 'vendeur_id', 'sponsor_status', 'sponsored_until'];

    protected $casts = [
        'images' => 'array',
        'prix' => 'decimal:2',
        'sponsored_until' => 'datetime'
    ];

    public function vendeur() { return $this->belongsTo(User::class, 'vendeur_id'); }
    public function categorie() { return $this->belongsTo(Categorie::class, 'categorie_id'); }
}
```

## 3. Messaging Models
### `app/Models/Conversation.php` & `Message.php`
Establish the real-time chat architecture linking buyers and sellers.

```php
// Conversation.php
class Conversation extends Model
{
    protected $fillable = ['produit_id', 'acheteur_id'];

    public function produit() { return $this->belongsTo(Produit::class); }
    public function acheteur() { return $this->belongsTo(User::class, 'acheteur_id'); }
    public function messages() { return $this->hasMany(Message::class); }
}

// Message.php
class Message extends Model
{
    protected $fillable = ['conversation_id', 'expediteur_id', 'contenu'];

    public function conversation() { return $this->belongsTo(Conversation::class); }
    public function expediteur() { return $this->belongsTo(User::class, 'expediteur_id'); }
}
```

## 4. Supporting Models
### `app/Models/Categorie.php` & `Ville.php`
Core lookup tables for marketplace organization.

```php
// Categorie.php
class Categorie extends Model
{
    protected $fillable = ['nom', 'slug'];
    public function produits() { return $this->hasMany(Produit::class, 'categorie_id'); }
}

// Ville.php
class Ville extends Model
{
    protected $fillable = ['nom'];
}
```
