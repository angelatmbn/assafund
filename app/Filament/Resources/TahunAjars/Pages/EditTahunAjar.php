<?php

namespace App\Filament\Resources\TahunAjars\Pages;

use App\Filament\Resources\TahunAjars\TahunAjarResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTahunAjar extends EditRecord
{
    protected static string $resource = TahunAjarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
