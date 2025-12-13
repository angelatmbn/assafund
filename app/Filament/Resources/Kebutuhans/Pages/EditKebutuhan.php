<?php

namespace App\Filament\Resources\Kebutuhans\Pages;

use App\Filament\Resources\Kebutuhans\KebutuhanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKebutuhan extends EditRecord
{
    protected static string $resource = KebutuhanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
