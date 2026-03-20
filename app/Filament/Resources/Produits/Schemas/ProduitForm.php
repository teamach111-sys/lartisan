<?php

namespace App\Filament\Resources\Produits\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProduitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('vendeur_id')
                    ->required()
                    ->numeric(),
                TextInput::make('categorie_id')
                    ->numeric(),
                TextInput::make('titre')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Toggle::make('telephone_visible')
                    ->required(),
                TextInput::make('prix')
                    ->required()
                    ->numeric(),
                TextInput::make('ville_produit')
                    ->required(),
                Textarea::make('images')
                    ->columnSpanFull(),
                TextInput::make('etat_produit')
                    ->required()
                    ->default('neuf'),
                TextInput::make('etat_moderation')
                    ->required()
                    ->default('en_attente'),
            ]);
    }
}
