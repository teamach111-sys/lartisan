---
contents:
  - id: 1
    label: app/Models/SignalementProduit.php
    language: php
  - id: 2
    label: app/Filament/Resources/Produits/ProduitResource.php
    language: php
  - id: 3
    label: app/Filament/Resources/Produits/Tables/ProduitsTable.php
    language: php
  - id: 4
    label: app/Filament/Resources/SignalementProduits/SignalementProduitResource.php
    language: php
  - id: 5
    label: app/Filament/Resources/SignalementProduits/Tables/SignalementProduitsTable.php
    language: php
createdAt: 1774831000000
description: Moderation system for verifying product listings, handling user reports (flags), and approving sponsorship requests via Filament PHP.
folderId: null
id: 1774831000000
isDeleted: 0
isFavorites: 0
name: 05_MODERATION_SYSTEM
tags: []
updatedAt: 1774831000000
---

## Fragment: app/Models/SignalementProduit.php
# This file is used to store data about user-reported products, including the reason and the processing status.
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignalementProduit extends Model
{
    use HasFactory;
    protected $table = 'signalement_produits';
    protected $fillable = [
        'produit_id',
        'produit_nom',
        'utilisateur_id',
        'type_signalement',
        'details',
        'est_traite',
    ];

    public function produit() {
        return $this->belongsTo(Produit::class);
    }

    public function utilisateur() {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
}
```

## Fragment: app/Filament/Resources/Produits/ProduitResource.php
# This file is used as the Filament resource entry for managing products, including custom navigation grouping.
```php
<?php

namespace App\Filament\Resources\Produits;

use App\Models\Produit;
use Filament\Resources\Resource;

class ProduitResource extends Resource
{
    protected static ?string $model = Produit::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Gestion Boutique';

    // ... form, table, and pages registration ...
}
```

## Fragment: app/Filament/Resources/Produits/Tables/ProduitsTable.php
# This file is used to define the administrative table for products, including custom moderation actions (Approve/Reject) and Sponsorship approval.
```php
<?php

namespace App\Filament\Resources\Produits\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class ProduitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images')->disk('public')->limit(1),
                TextColumn::make('titre')->searchable(),
                TextColumn::make('etat_moderation')->badge()->colors([
                    'warning' => 'en_attente',
                    'success' => 'valide',
                    'danger' => 'rejete',
                ]),
                TextColumn::make('sponsor_status')->badge(),
            ])
            ->recordActions([
                // Approval Logic
                \Filament\Actions\Action::make('accepter')
                    ->label('Accepter')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn ($record) => $record->update(['etat_moderation' => 'valide'])),

                // Sponsorship Approval
                \Filament\Actions\Action::make('accepter_sponsor')
                    ->label('Accepter Sponsoring')
                    ->icon('heroicon-o-star')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('duration_hours')->numeric()->default(24)->required(),
                    ])
                    ->action(fn ($record, $data) => $record->update([
                        'sponsor_status' => 'approuve',
                        'sponsored_until' => now()->addHours((int)$data['duration_hours']),
                    ])),
                
                \Filament\Actions\EditAction::make(),
            ]);
    }
}
```

## Fragment: app/Filament/Resources/SignalementProduits/SignalementProduitResource.php
# This file is used as the Filament resource entry for handling user-submitted flags and reports.
```php
<?php

namespace App\Filament\Resources\SignalementProduits;

use App\Models\SignalementProduit;
use Filament\Resources\Resource;

class SignalementProduitResource extends Resource
{
    protected static ?string $model = SignalementProduit::class;
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationGroup = 'Gestion Boutique';
}
```

## Fragment: app/Filament/Resources/SignalementProduits/Tables/SignalementProduitsTable.php
# This file is used to define the administrative moderation interface for reports, allowing admins to delete reported products or dismiss flags.
```php
<?php

namespace App\Filament\Resources\SignalementProduits\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;

class SignalementProduitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('produit_nom')->label('Produit'),
                \Filament\Tables\Columns\TextColumn::make('type_signalement')->badge(),
                \Filament\Tables\Columns\IconColumn::make('est_traite')->boolean(),
            ])
            ->recordActions([
                // Delete Reported Product
                Action::make('accept')
                    ->label('Approuver (Supprimer)')
                    ->icon('heroicon-o-check')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        if ($record->produit) { $record->produit->delete(); }
                        $record->update(['est_traite' => true]);
                    }),

                // Dismiss Report
                Action::make('reject')
                    ->label('Rejeter')
                    ->icon('heroicon-o-x-mark')
                    ->color('warning')
                    ->action(fn ($record) => $record->update(['est_traite' => true])),
            ]);
    }
}
```
