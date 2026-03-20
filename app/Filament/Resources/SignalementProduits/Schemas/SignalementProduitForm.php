<?php

namespace App\Filament\Resources\SignalementProduits\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SignalementProduitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('produit_id')
                    ->required()
                    ->numeric(),
                TextInput::make('utilisateur_id')
                    ->required()
                    ->numeric(),
                TextInput::make('type_signalement')
                    ->required(),
                Textarea::make('details')
                    ->columnSpanFull(),
                Toggle::make('est_traite')
                    ->required(),
            ]);
    }
}
