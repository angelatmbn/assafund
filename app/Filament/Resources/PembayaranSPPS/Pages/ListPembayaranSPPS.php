<?php

namespace App\Filament\Resources\PembayaranSPPS\Pages;

use App\Filament\Resources\PembayaranSPPS\PembayaranSPPResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPembayaranSPPS extends ListRecords
{
    protected static string $resource = PembayaranSPPResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
