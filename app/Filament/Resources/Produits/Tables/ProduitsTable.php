<?php

namespace App\Filament\Resources\Produits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProduitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vendeur_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('categorie_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('titre')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                IconColumn::make('telephone_visible')
                    ->boolean(),
                TextColumn::make('prix')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ville_produit')
                    ->searchable(),
                TextColumn::make('etat_produit')
                    ->searchable(),
                TextColumn::make('etat_moderation')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
