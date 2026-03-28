<?php

namespace App\Filament\Resources\SignalementProduits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

use Filament\Actions\Action;

class SignalementProduitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('produit_nom')
                    ->label('Produit')
                    ->formatStateUsing(fn (\App\Models\SignalementProduit $record) => $record->produit_id ? $record->produit_nom : $record->produit_nom . ' (Supprimé)')
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
                Action::make('view_product')
                    ->label('Voir Produit')
                    ->icon('heroicon-o-eye')
                    ->url(fn (\App\Models\SignalementProduit $record) => $record->produit ? route('produit.show', $record->produit->slug) : null)
                    ->openUrlInNewTab()
                    ->visible(fn (\App\Models\SignalementProduit $record) => $record->produit !== null),
                
                Action::make('reject')
                    ->label('Rejeter')
                    ->icon('heroicon-o-x-mark')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (\App\Models\SignalementProduit $record) {
                        $record->update(['est_traite' => true]);
                        \Filament\Notifications\Notification::make()
                            ->title('Signalement rejeté. Le produit reste actif.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (\App\Models\SignalementProduit $record) => ! $record->est_traite),

                Action::make('accept')
                    ->label('Approuver (Supprimer)')
                    ->icon('heroicon-o-check')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalDescription('Êtes-vous sûr de vouloir approuver ce signalement ? Le produit signalé sera définitivement supprimé.')
                    ->action(function (\App\Models\SignalementProduit $record) {
                        if ($record->produit) {
                            $record->produit->delete();
                        }
                        $record->update(['est_traite' => true]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Signalement approuvé. Le produit a été supprimé.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (\App\Models\SignalementProduit $record) => ! $record->est_traite),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
