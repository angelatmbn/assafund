<?php

namespace App\Filament\Resources\TahunAjars\Pages;

use App\Filament\Resources\TahunAjars\TahunAjarResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTahunAjars extends ListRecords
{
    protected static string $resource = TahunAjarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
