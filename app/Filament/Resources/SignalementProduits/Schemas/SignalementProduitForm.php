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
        return $schema
            ->components([
                Section::make('Détails du Signalement')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('produit_id')
                                    ->label('Produit signalé')
                                    ->relationship('produit', 'titre')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Select::make('utilisateur_id')
                                    ->label('Signallé par')
                                    ->relationship('utilisateur', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Select::make('type_signalement')
                                    ->label('Type de signalement')
                                    ->options([
                                        'contrefacon' => 'Contrefaçon',
                                        'inapproprie' => 'Contenu inapproprié',
                                        'arnaque' => 'Tentative d\'arnaque',
                                        'autre' => 'Autre',
                                    ])
                                    ->required(),
                                Toggle::make('est_traite')
                                    ->label('Signalement traité')
                                    ->required(),
                            ]),
                        Textarea::make('details')
                            ->label('Détails supplémentaires')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
