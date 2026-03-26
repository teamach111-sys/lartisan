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
        return $table
            ->columns([
                TextColumn::make('produit.titre')
                    ->label('Produit')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('utilisateur.name')
                    ->label('Signalé par')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type_signalement')
                    ->label('Type')
                    ->badge()
                    ->searchable(),
                IconColumn::make('est_traite')
                    ->label('Traité')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Signalé le')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('est_traite')
                    ->label('Statut traitement')
                    ->options([
                        '1' => 'Traité',
                        '0' => 'Non traité',
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
