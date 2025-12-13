<?php

namespace App\Filament\Resources\KomponenBiayaDaftars\Pages;

use App\Filament\Resources\KomponenBiayaDaftars\KomponenBiayaDaftarResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKomponenBiayaDaftars extends ListRecords
{
    protected static string $resource = KomponenBiayaDaftarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
