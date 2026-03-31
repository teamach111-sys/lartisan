<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom complet')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Adresse email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('telephone')
                            ->label('Téléphone')
                            ->tel()
                            ->maxLength(20),
                        TextInput::make('ville_utilisateur')
                            ->label('Ville')
                            ->required()
                            ->default('Marrakech'),
                        TextInput::make('password')
                            ->label('Mot de passe')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255),
                        Select::make('role')
                            ->label('Rôle')
                            ->options([
                                'admin' => 'Administrateur',
                                'utilisateur' => 'Utilisateur',
                            ])
                            ->required()
                            ->default('utilisateur'),
                        Select::make('statut_compte')
                            ->label('Statut du compte')
                            ->options([
                                'actif' => 'Actif',
                                'suspendu' => 'Suspendu',
                                'banni' => 'Banni',
                            ])
                            ->required()
                            ->default('actif'),
                        FileUpload::make('pfp')
                            ->label('Photo de profil')
                            ->disk(config('filesystems.default', 'public'))
                            ->visibility('public')
                            ->getUploadedFileUrlUsing(fn($file) => \App\Helpers\ImageHelper::getProxyUrl($file))
                            ->directory('avatars')
                            ->image()
                            ->avatar(),
                        DateTimePicker::make('last_seen_at')
                            ->label('Dernière connexion')
                            ->disabled(),
                        DateTimePicker::make('email_verified_at')
                            ->label('Email vérifié le'),
                    ]),
            ]);
    }
}
