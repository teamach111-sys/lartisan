---
description: L'Artisan Marketplace - Admin Panel & Moderation System
---

# Admin & Moderation

This module implements the administrative backend using Filament, providing tools for product moderation, user management, and site-wide configuration.

## 1. Moderation Resources
### `app/Filament/Resources/ProduitResource.php`
Allows admins to review, approve, or reject listings before they go public.

```php
// Table Columns: Titre, Vendeur, Categorie, Prix, Etat Moderation
Tables\Columns\TextColumn::make('titre')->searchable(),
Tables\Columns\SelectColumn::make('etat_moderation')
    ->options(['en_attente', 'approuve', 'rejete']),

// Sponsoring Action (Quick Approval)
Tables\Actions\Action::make('Sponsoriser')
    ->action(fn (Produit $record) => $record->update(['is_sponsored' => true, 'sponsored_until' => now()->addDays(7)]));
```

### `app/Filament/Resources/SignalementProduitResource.php`
A centralized queue for handling user-flagged content.

```php
// Fields: Produit, Utilisateur, Type Signalement, Details
Tables\Columns\TextColumn::make('produit.titre'),
Tables\Columns\TextColumn::make('type_signalement')->badge(),
Tables\Columns\TextColumn::make('details')->limit(20),
```

## 2. User & System Management
### `app/Filament/Resources/UserResource.php`
Enables banning/activating accounts and changing site roles.

```php
// Table: Name, Email, Role, Account Status
Tables\Columns\TextColumn::make('role')->badge(),
Tables\Columns\SelectColumn::make('statut_compte')->options(['actif', 'suspendu']),
```

## 3. Global Site Settings
### `app/Filament/Resources/SiteSettingResource.php`
A specialized resource for app-wide configuration like maintenance mode or announcement banners.

```php
Forms\Components\Toggle::make('maintenance_mode'),
Forms\Components\TextInput::make('announcement_text'),
```

## 4. Dashboard Widgets
### `app/Filament/Widgets/StatsOverview.php`
Real-time stats on products, users, and the messaging queue.

```php
protected function getStats(): array
{
    return [
        Stat::make('Produits en Attente', Produit::where('etat_moderation', 'en_attente')->count()),
        Stat::make('Signalements Récents', SignalementProduit::where('created_at', '>', now()->subDay())->count()),
    ];
}
```
