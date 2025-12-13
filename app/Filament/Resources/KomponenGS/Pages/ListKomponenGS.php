<?php

namespace App\Filament\Resources\KomponenGS\Pages;

use App\Filament\Resources\KomponenGS\KomponenGResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKomponenGS extends ListRecords
{
    protected static string $resource = KomponenGResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
