<?php

namespace App\Filament\Resources\PembayaranSPPS\Pages;

use App\Filament\Resources\PembayaranSPPS\PembayaranSPPResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPembayaranSPP extends EditRecord
{
    protected static string $resource = PembayaranSPPResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
