<?php

namespace App\Filament\Resources\SignalementProduits;

use App\Filament\Resources\SignalementProduits\Pages\CreateSignalementProduit;
use App\Filament\Resources\SignalementProduits\Pages\EditSignalementProduit;
use App\Filament\Resources\SignalementProduits\Pages\ListSignalementProduits;
use App\Filament\Resources\SignalementProduits\Schemas\SignalementProduitForm;
use App\Filament\Resources\SignalementProduits\Tables\SignalementProduitsTable;
use App\Models\SignalementProduit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SignalementProduitResource extends Resource
{
    protected static ?string $model = SignalementProduit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return SignalementProduitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SignalementProduitsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSignalementProduits::route('/'),
            'create' => CreateSignalementProduit::route('/create'),
            'edit' => EditSignalementProduit::route('/{record}/edit'),
        ];
    }
}
