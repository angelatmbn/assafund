<?php

namespace App\Filament\Resources\Kebutuhans\Pages;

use App\Filament\Resources\Kebutuhans\KebutuhanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKebutuhans extends ListRecords
{
    protected static string $resource = KebutuhanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
