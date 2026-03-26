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
        return $table
            ->columns([
                ImageColumn::make('images')
                    ->label('Image')
                    ->disk('public')
                    ->circular()
                    ->limit(1),
                TextColumn::make('titre')
                    ->label('Titre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('vendeur.name')
                    ->label('Vendeur')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('categorie.nom')
                    ->label('Catégorie')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('prix')
                    ->label('Prix')
                    ->money('MAD')
                    ->sortable(),
                TextColumn::make('ville_produit')
                    ->label('Ville')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('etat_produit')
                    ->label('État')
                    ->badge()
                    ->searchable(),
                TextColumn::make('etat_moderation')
                    ->label('Modération')
                    ->badge()
                    ->colors([
                        'warning' => 'en_attente',
                        'success' => 'valide',
                        'danger' => 'rejete',
                    ])
                    ->searchable(),
                IconColumn::make('telephone_visible')
                    ->label('Tel. visible')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('categorie_id')
                    ->label('Catégorie')
                    ->relationship('categorie', 'nom'),
                SelectFilter::make('etat_moderation')
                    ->options([
                        'en_attente' => 'En attente',
                        'valide' => 'Validé',
                        'rejete' => 'Rejeté',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
