<?php

namespace App\Filament\Resources\KomponenGS\Pages;

use App\Filament\Resources\KomponenGS\KomponenGResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKomponenG extends EditRecord
{
    protected static string $resource = KomponenGResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
