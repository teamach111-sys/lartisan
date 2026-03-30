---
contents:
  - id: 1
    label: app/Models/User.php
    language: php
  - id: 2
    label: app/Filament/Resources/Categories/CategorieResource.php
    language: php
  - id: 3
    label: app/Filament/Resources/Categories/Schemas/CategorieForm.php
    language: php
  - id: 4
    label: app/Filament/Resources/Categories/Tables/CategoriesTable.php
    language: php
  - id: 5
    label: app/Filament/Resources/Users/UserResource.php
    language: php
  - id: 6
    label: app/Filament/Resources/Users/Schemas/UserForm.php
    language: php
  - id: 7
    label: app/Filament/Resources/Users/Tables/UsersTable.php
    language: php
  - id: 8
    label: app/Filament/Resources/Produits/ProduitResource.php
    language: php
  - id: 9
    label: app/Filament/Resources/Produits/Schemas/ProduitForm.php
    language: php
  - id: 10
    label: app/Filament/Resources/Produits/Tables/ProduitsTable.php
    language: php
  - id: 11
    label: app/Filament/Resources/SignalementProduits/SignalementProduitResource.php
    language: php
  - id: 12
    label: app/Filament/Resources/SignalementProduits/Schemas/SignalementProduitForm.php
    language: php
  - id: 13
    label: app/Filament/Resources/SignalementProduits/Tables/SignalementProduitsTable.php
    language: php
  - id: 14
    label: app/Filament/Widgets/StatsOverview.php
    language: php
createdAt: 1774641546582
description: null
folderId: null
id: 1774641546582
isDeleted: 0
isFavorites: 0
name: FILAMENT
tags: []
updatedAt: 1774641546582
---

## Fragment: app/Models/User.php
```php
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }
}
```

## Fragment: app/Filament/Resources/Categories/CategorieResource.php
```php
<?php
namespace App\Filament\Resources\Categories;

use App\Models\Categorie;
use App\Filament\Resources\Categories\Pages;
use App\Filament\Resources\Categories\Schemas\CategorieForm;
use App\Filament\Resources\Categories\Tables\CategoriesTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class CategorieResource extends Resource
{
    protected static ?string $model = Categorie::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Gestion Boutique';
    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema { return CategorieForm::configure($schema); }
    public static function table(Table $table): Table { return CategoriesTable::configure($table); }
    public static function getPages(): array {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategorie::route('/create'),
            'edit' => Pages\EditCategorie::route('/{record}/edit'),
        ];
    }
}
```

## Fragment: app/Filament/Resources/Categories/Schemas/CategorieForm.php
```php
<?php
namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategorieForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nom')->required(),
        ]);
    }
}
```

## Fragment: app/Filament/Resources/Categories/Tables/CategoriesTable.php
```php
<?php
namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('nom')->label('Nom')->searchable()->sortable(),
            TextColumn::make('produits_count')->label('Nombre de produits')->counts('produits')->sortable(),
            TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ])
        ->recordActions([EditAction::make()])
        ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
```

## Fragment: app/Filament/Resources/Users/UserResource.php
```php
<?php
namespace App\Filament\Resources\Users;

use App\Models\User;
use App\Filament\Resources\Users\Pages;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Système';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema { return UserForm::configure($schema); }
    public static function table(Table $table): Table { return UsersTable::configure($table); }
    public static function getPages(): array {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
```

## Fragment: app/Filament/Resources/Users/Schemas/UserForm.php
```php
<?php
namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                TextInput::make('name')->label('Nom complet')->required()->maxLength(255),
                TextInput::make('email')->label('Adresse email')->email()->required()->unique(ignoreRecord: true)->maxLength(255),
                TextInput::make('telephone')->label('Téléphone')->tel()->maxLength(20),
                TextInput::make('ville_utilisateur')->label('Ville')->required()->default('Marrakech'),
                TextInput::make('password')
                    ->label('Mot de passe')->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->maxLength(255),
                Select::make('role')->label('Rôle')->options([
                    'admin' => 'Administrateur',
                    'utilisateur' => 'Utilisateur',
                ])->required()->default('utilisateur'),
                Select::make('statut_compte')->label('Statut du compte')->options([
                    'actif' => 'Actif',
                    'suspendu' => 'Suspendu',
                    'banni' => 'Banni',
                ])->required()->default('actif'),
                FileUpload::make('pfp')->label('Photo de profil')->image()->directory('user-pfps')->avatar(),
                DateTimePicker::make('last_seen_at')->label('Dernière connexion')->disabled(),
                DateTimePicker::make('email_verified_at')->label('Email vérifié le'),
            ]),
        ]);
    }
}
```

## Fragment: app/Filament/Resources/Users/Tables/UsersTable.php
```php
<?php
namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('pfp')->label('Photo')->circular(),
            TextColumn::make('name')->label('Nom')->searchable()->sortable(),
            TextColumn::make('email')->label('Email')->searchable()->sortable(),
            TextColumn::make('telephone')->label('Téléphone')->searchable(),
            TextColumn::make('ville_utilisateur')->label('Ville')->searchable()->sortable(),
            TextColumn::make('role')->label('Rôle')->badge()->colors(['danger' => 'admin', 'info' => 'utilisateur'])->searchable(),
            TextColumn::make('statut_compte')->label('Statut')->badge()->colors(['success' => 'actif', 'warning' => 'suspendu', 'danger' => 'banni'])->searchable(),
            TextColumn::make('last_seen_at')->label('Dernière connexion')->dateTime()->sortable(),
        ])
        ->filters([
            SelectFilter::make('role')->options(['admin' => 'Administrateur', 'utilisateur' => 'Utilisateur']),
            SelectFilter::make('statut_compte')->options(['actif' => 'Actif', 'suspendu' => 'Suspendu', 'banni' => 'Banni']),
        ])
        ->recordActions([EditAction::make()])
        ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
```

## Fragment: app/Filament/Resources/Produits/ProduitResource.php
```php
<?php
namespace App\Filament\Resources\Produits;

use App\Models\Produit;
use App\Filament\Resources\Produits\Pages;
use App\Filament\Resources\Produits\Schemas\ProduitForm;
use App\Filament\Resources\Produits\Tables\ProduitsTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ProduitResource extends Resource
{
    protected static ?string $model = Produit::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Gestion Boutique';
    protected static ?string $recordTitleAttribute = 'titre';

    public static function form(Schema $schema): Schema { return ProduitForm::configure($schema); }
    public static function table(Table $table): Table { return ProduitsTable::configure($table); }
    public static function getPages(): array {
        return [
            'index' => Pages\ListProduits::route('/'),
            'create' => Pages\CreateProduit::route('/create'),
            'edit' => Pages\EditProduit::route('/{record}/edit'),
        ];
    }
}
```

## Fragment: app/Filament/Resources/Produits/Schemas/ProduitForm.php
```php
<?php
namespace App\Filament\Resources\Produits\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProduitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Détails du Produit')->schema([
                Grid::make(2)->schema([
                    TextInput::make('titre')->label('Titre du produit')->required()->maxLength(255),
                    TextInput::make('slug')->label('Slug')->required()->unique(ignoreRecord: true)->maxLength(255),
                    Select::make('vendeur_id')->label('Vendeur')->relationship('vendeur', 'name')->required()->searchable()->preload(),
                    Select::make('categorie_id')->label('Catégorie')->relationship('categorie', 'nom')->searchable()->preload(),
                    TextInput::make('prix')->label('Prix')->numeric()->prefix('MAD')->required(),
                    TextInput::make('ville_produit')->label('Ville')->required()->default('Marrakech'),
                ]),
                RichEditor::make('description')->label('Description')->columnSpanFull(),
            ]),
            Section::make('Images et État')->schema([
                FileUpload::make('images')->label('Images du produit')->multiple()->directory('produits')->reorderable()->image()->columnSpanFull(),
                Grid::make(2)->schema([
                    Select::make('etat_produit')->label('État du produit')->options([
                        'neuf' => 'Neuf',
                        'occasion_bon' => 'Occasion (Bon état)',
                        'occasion_moyen' => 'Occasion (État moyen)',
                    ])->required()->default('neuf'),
                    Select::make('etat_moderation')->label('Statut Modération')->options([
                        'en_attente' => 'En attente',
                        'valide' => 'Validé',
                        'rejete' => 'Rejeté',
                    ])->required()->default('en_attente'),
                    Toggle::make('telephone_visible')->label('Afficher le téléphone')->required(),
                ]),
            ]),
        ]);
    }
}
```

## Fragment: app/Filament/Resources/Produits/Tables/ProduitsTable.php
```php
<?php
namespace App\Filament\Resources\Produits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProduitsTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('images')->label('Image')->circular()->limit(1),
            TextColumn::make('titre')->label('Titre')->searchable()->sortable(),
            TextColumn::make('vendeur.name')->label('Vendeur')->searchable()->sortable(),
            TextColumn::make('categorie.nom')->label('Catégorie')->searchable()->sortable(),
            TextColumn::make('prix')->label('Prix')->money('MAD')->sortable(),
            TextColumn::make('ville_produit')->label('Ville')->searchable()->sortable(),
            TextColumn::make('etat_produit')->label('État')->badge()->searchable(),
            TextColumn::make('etat_moderation')->label('Modération')->badge()->colors(['warning' => 'en_attente', 'success' => 'valide', 'danger' => 'rejete'])->searchable(),
            IconColumn::make('telephone_visible')->label('Tel. visible')->boolean(),
        ])
        ->filters([
            SelectFilter::make('categorie_id')->label('Catégorie')->relationship('categorie', 'nom'),
            SelectFilter::make('etat_moderation')->options(['en_attente' => 'En attente', 'valide' => 'Validé', 'rejete' => 'Rejeté']),
        ])
        ->recordActions([EditAction::make()])
        ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
```

## Fragment: app/Filament/Resources/SignalementProduits/SignalementProduitResource.php
```php
<?php
namespace App\Filament\Resources\SignalementProduits;

use App\Models\SignalementProduit;
use App\Filament\Resources\SignalementProduits\Pages;
use App\Filament\Resources\SignalementProduits\Schemas\SignalementProduitForm;
use App\Filament\Resources\SignalementProduits\Tables\SignalementProduitsTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SignalementProduitResource extends Resource
{
    protected static ?string $model = SignalementProduit::class;
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationGroup = 'Gestion Boutique';
    protected static ?string $recordTitleAttribute = 'type_signalement';

    public static function form(Schema $schema): Schema { return SignalementProduitForm::configure($schema); }
    public static function table(Table $table): Table { return SignalementProduitsTable::configure($table); }
    public static function getPages(): array {
        return [
            'index' => Pages\ListSignalementProduits::route('/'),
            'create' => Pages\CreateSignalementProduit::route('/create'),
            'edit' => Pages\EditSignalementProduit::route('/{record}/edit'),
        ];
    }
}
```

## Fragment: app/Filament/Resources/SignalementProduits/Schemas/SignalementProduitForm.php
```php
<?php
namespace App\Filament\Resources\SignalementProduits\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SignalementProduitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Détails du Signalement')->schema([
                Grid::make(2)->schema([
                    Select::make('produit_id')->label('Produit signalé')->relationship('produit', 'titre')->required()->searchable()->preload(),
                    Select::make('utilisateur_id')->label('Signallé par')->relationship('utilisateur', 'name')->required()->searchable()->preload(),
                    Select::make('type_signalement')->label('Type de signalement')->options([
                        'contrefacon' => 'Contrefaçon',
                        'inapproprie' => 'Contenu inapproprié',
                        'arnaque' => 'Tentative d\'arnaque',
                        'autre' => 'Autre',
                    ])->required(),
                    Toggle::make('est_traite')->label('Signalement traité')->required(),
                ]),
                Textarea::make('details')->label('Détails supplémentaires')->columnSpanFull(),
            ]),
        ]);
    }
}
```

## Fragment: app/Filament/Resources/SignalementProduits/Tables/SignalementProduitsTable.php
```php
<?php
namespace App\Filament\Resources\SignalementProduits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SignalementProduitsTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('produit.titre')->label('Produit')->searchable()->sortable(),
            TextColumn::make('utilisateur.name')->label('Signalé par')->searchable()->sortable(),
            TextColumn::make('type_signalement')->label('Type')->badge()->searchable(),
            IconColumn::make('est_traite')->label('Traité')->boolean()->sortable(),
            TextColumn::make('created_at')->label('Signalé le')->dateTime()->sortable(),
        ])
        ->filters([
            SelectFilter::make('est_traite')->label('Statut traitement')->options(['1' => 'Traité', '0' => 'Non traité']),
        ])
        ->recordActions([EditAction::make()])
        ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
```

## Fragment: app/Filament/Widgets/StatsOverview.php
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
        $newUsersToday = User::whereDate('created_at', '=', Carbon::today(), 'and')->count('*');
        $totalProduits = Produit::count('*');
        $pendingReports = SignalementProduit::where('est_traite', '=', false, 'and')->count('*');

        return [
            Stat::make('Nouveaux Utilisateurs', $newUsersToday)
                ->description("Inscriptions d'aujourd'hui")
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
            Stat::make('Total Produits', $totalProduits)
                ->description('Articles en ligne')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),
            Stat::make('Signalements en attente', $pendingReports)
                ->description('À traiter d\'urgence')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($pendingReports > 0 ? 'danger' : 'success'),
        ];
    }
}
```

