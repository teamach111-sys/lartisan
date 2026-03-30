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
        return $schema
            ->components([
                Section::make('Détails du Produit')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('titre')
                                    ->label('Titre du produit')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null)
                                    ->maxLength(255),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                Select::make('vendeur_id')
                                    ->label('Vendeur')
                                    ->relationship('vendeur', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Select::make('categorie_id')
                                    ->label('Catégorie')
                                    ->relationship('categorie', 'nom')
                                    ->searchable()
                                    ->preload(),
                                TextInput::make('prix')
                                    ->label('Prix')
                                    ->numeric()
                                    ->prefix('MAD')
                                    ->required(),
                                TextInput::make('ville_produit')
                                    ->label('Ville')
                                    ->required()
                                    ->default('Marrakech'),
                            ]),
                        RichEditor::make('description')
                            ->label('Description')
                            ->columnSpanFull(),
                    ]),

                Section::make('Images et État')
                    ->schema([
                        FileUpload::make('images')
                            ->label('Images du produit')
                            ->multiple()
                            ->disk('public')
                            ->directory('produits')
                            ->reorderable()
                            ->image()
                            ->imageEditor()
                            ->maxSize(10240) // 10MB limit per image
                            ->panelLayout('grid')
                            ->columnSpanFull(),
                        Grid::make(2)
                            ->schema([
                                Select::make('etat_produit')
                                    ->label('État du produit')
                                    ->options([
                                        'neuf' => 'Neuf',
                                        'occasion_bon' => 'Occasion (Bon état)',
                                        'occasion_moyen' => 'Occasion (État moyen)',
                                    ])
                                    ->required()
                                    ->default('neuf'),
                                Select::make('etat_moderation')
                                    ->label('Statut Modération')
                                    ->options([
                                        'en_attente' => 'En attente',
                                        'valide' => 'Validé',
                                        'rejete' => 'Rejeté',
                                    ])
                                    ->required()
                                    ->default('en_attente'),
                                Toggle::make('telephone_visible')
                                    ->label('Afficher le téléphone')
                                    ->required(),
                            ]),
                    ]),
            ]);
    }
}
