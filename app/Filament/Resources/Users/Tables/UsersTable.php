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
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('pfp')
                    ->label('Avatar')
                    ->disk(config('filesystems.default'))
                    ->circular()
                    ->defaultImageUrl(fn ($record) => $record->pfp_url),
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('telephone')
                    ->label('Téléphone')
                    ->searchable(),
                TextColumn::make('ville_utilisateur')
                    ->label('Ville')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->label('Rôle')
                    ->badge()
                    ->colors([
                        'danger' => 'admin',
                        'info' => 'utilisateur',
                    ])
                    ->searchable(),
                TextColumn::make('statut_compte')
                    ->label('Statut')
                    ->badge()
                    ->colors([
                        'success' => 'actif',
                        'warning' => 'suspendu',
                        'danger' => 'banni',
                    ])
                    ->searchable(),
                TextColumn::make('last_seen_at')
                    ->label('Dernière connexion')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options([
                        'admin' => 'Administrateur',
                        'utilisateur' => 'Utilisateur',
                    ]),
                SelectFilter::make('statut_compte')
                    ->options([
                        'actif' => 'Actif',
                        'suspendu' => 'Suspendu',
                        'banni' => 'Banni',
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
