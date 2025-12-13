<?php

namespace App\Filament\Resources\KomponenBiayaDaftars\Pages;

use App\Filament\Resources\KomponenBiayaDaftars\KomponenBiayaDaftarResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKomponenBiayaDaftar extends EditRecord
{
    protected static string $resource = KomponenBiayaDaftarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
