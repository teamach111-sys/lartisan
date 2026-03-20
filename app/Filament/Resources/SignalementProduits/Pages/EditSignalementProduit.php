<?php

namespace App\Filament\Resources\SignalementProduits\Pages;

use App\Filament\Resources\SignalementProduits\SignalementProduitResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSignalementProduit extends EditRecord
{
    protected static string $resource = SignalementProduitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
