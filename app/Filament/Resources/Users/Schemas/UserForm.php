<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required(),
                TextInput::make('pfp'),
                TextInput::make('telephone')
                    ->tel(),
                TextInput::make('ville_utilisateur')
                    ->required()
                    ->default('Marrakech'),
                TextInput::make('statut_compte')
                    ->required()
                    ->default('actif'),
                TextInput::make('role')
                    ->required()
                    ->default('utilisateur'),
                DateTimePicker::make('last_seen_at'),
                DateTimePicker::make('email_verified_at'),
            ]);
    }
}
