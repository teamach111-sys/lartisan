---
contents:
  - id: 1
    label: app/Providers/Filament/AdminPanelProvider.php
    language: php
  - id: 2
    label: app/Filament/Resources/Users/UserResource.php
    language: php
  - id: 3
    label: app/Filament/Resources/Categories/CategorieResource.php
    language: php
  - id: 4
    label: app/Filament/Resources/Villes/VilleResource.php
    language: php
  - id: 5
    label: app/Filament/Widgets/StatsOverview.php
    language: php
createdAt: 1774832000000
description: Admin dashboard configuration using Filament PHP, including user management, category setup, and live statistics widgets.
folderId: null
id: 1774832000000
isDeleted: 0
isFavorites: 0
name: 06_ADMIN_PANEL
tags: []
updatedAt: 1774832000000
---

## Fragment: app/Providers/Filament/AdminPanelProvider.php
# This file is used to configure the global Admin Panel settings, including authentication, theme colors, and registered widgets.
```php
<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Filament\Widgets\StatsOverview;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->widgets([
                StatsOverview::class,
                \Filament\Widgets\AccountWidget::class,
            ])
            ->authMiddleware([
                \Filament\Http\Middleware\Authenticate::class,
            ]);
    }
}
```

## Fragment: app/Filament/Resources/Users/UserResource.php
# This file is used to manage system users, allowing administrators to view, edit, or delete accounts and manage roles.
```php
<?php

namespace App\Filament\Resources\Users;

use App\Models\User;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Système';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('role')->badge(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ]);
    }
}
```

## Fragment: app/Filament/Resources/Categories/CategorieResource.php
# This file is used to manage product categories, ensuring the marketplace taxonomy is correctly structured.
```php
<?php

namespace App\Filament\Resources\Categories;

use App\Models\Categorie;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class CategorieResource extends Resource
{
    protected static ?string $model = Categorie::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Gestion Boutique';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')->sortable()->searchable(),
                TextColumn::make('slug'),
                TextColumn::make('produits_count')->counts('produits'),
            ]);
    }
}
```

## Fragment: app/Filament/Resources/Villes/VilleResource.php
# This file is used to manage the list of supported cities for product geolocation and user profiles.
```php
<?php

namespace App\Filament\Resources\Villes;

use App\Models\Ville;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class VilleResource extends Resource
{
    protected static ?string $model = Ville::class;
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup = 'Système';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')->sortable()->searchable(),
            ]);
    }
}
```

## Fragment: app/Filament/Widgets/StatsOverview.php
# This file is used to display high-level business metrics on the admin home page, such as new user registrations and pending reports.
```php
<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Produit;
use App\Models\SignalementProduit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('New Users (Today)', User::whereDate('created_at', Carbon::today())->count())
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
            Stat::make('Total Products', Produit::count())
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),
            Stat::make('Pending Reports', SignalementProduit::where('est_traite', false)->count())
                ->color('danger'),
        ];
    }
}
```
