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
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('images')
                    ->label('Image')
                    ->disk('public')
                    ->circular()
                    ->extraImgAttributes(['loading' => 'lazy'])
                    ->limit(1),
                TextColumn::make('titre')
                    ->label('Titre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('vendeur.name')
                    ->label('Vendeur')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('categorie.nom')
                    ->label('Catégorie')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('prix')
                    ->label('Prix')
                    ->money('MAD')
                    ->sortable(),
                TextColumn::make('ville_produit')
                    ->label('Ville')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('etat_produit')
                    ->label('État')
                    ->badge()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('etat_moderation')
                    ->label('Modération')
                    ->badge()
                    ->colors([
                        'warning' => 'en_attente',
                        'success' => 'valide',
                        'danger' => 'rejete',
                    ])
                    ->searchable(),
                TextColumn::make('sponsor_status')
                    ->label('Sponsor')
                    ->badge()
                    ->colors([
                        'warning' => 'en_attente',
                        'success' => 'approuve',
                    ])
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'none' => 'Non',
                        'en_attente' => 'En attente',
                        'approuve' => 'Approuvé',
                        default => $state
                    })
                    ->searchable(),
                TextColumn::make('sponsored_until')
                    ->label('Fin Sponsor')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('categorie_id')
                    ->label('Catégorie')
                    ->relationship('categorie', 'nom'),
                SelectFilter::make('etat_moderation')
                    ->label('Modération')
                    ->options([
                        'en_attente' => 'En attente',
                        'valide' => 'Validé',
                        'rejete' => 'Rejeté',
                    ]),
                SelectFilter::make('sponsor_status')
                    ->label('Sponsorisé')
                    ->options([
                        'en_attente' => 'En attente',
                        'approuve' => 'Approuvé',
                    ]),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('accepter_sponsor')
                    ->label('Accepter Sponsoring')
                    ->icon('heroicon-o-star')
                    ->color('info')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('duration_hours')
                            ->label('Durée (en heures)')
                            ->numeric()
                            ->default(24)
                            ->required(),
                    ])
                    ->action(function (\App\Models\Produit $record, array $data) {
                        $record->update([
                            'sponsor_status' => 'approuve',
                            'sponsored_until' => now()->addHours((int)$data['duration_hours']),
                        ]);
                        \Filament\Notifications\Notification::make()
                            ->title('Sponsoring approuvé')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (\App\Models\Produit $record) => $record->sponsor_status === 'en_attente'),
                    
                \Filament\Actions\Action::make('rejeter_sponsor')
                    ->label('Rejeter Sponsoring')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (\App\Models\Produit $record) {
                        $record->update([
                            'sponsor_status' => 'none',
                            'sponsored_until' => null,
                        ]);
                    })
                    ->visible(fn (\App\Models\Produit $record) => $record->sponsor_status === 'en_attente'),

                \Filament\Actions\Action::make('accepter')
                    ->label('Accepter')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (\App\Models\Produit $record) => $record->update(['etat_moderation' => 'valide']))
                    ->requiresConfirmation()
                    ->hidden(fn (\App\Models\Produit $record) => $record->etat_moderation === 'valide'),

                \Filament\Actions\Action::make('rejeter')
                    ->label('Rejeter')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(fn (\App\Models\Produit $record) => $record->update(['etat_moderation' => 'rejete']))
                    ->requiresConfirmation()
                    ->hidden(fn (\App\Models\Produit $record) => $record->etat_moderation === 'rejete'),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
