<?php

namespace App\Filament\Resources\SignalementProduits\Pages;

use App\Filament\Resources\SignalementProduits\SignalementProduitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSignalementProduits extends ListRecords
{
    protected static string $resource = SignalementProduitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
