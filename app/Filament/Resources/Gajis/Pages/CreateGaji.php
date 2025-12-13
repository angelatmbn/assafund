<?php

namespace App\Filament\Resources\Gajis\Pages;

use App\Filament\Resources\Gajis\GajiResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGaji extends CreateRecord
{
    protected static string $resource = GajiResource::class;
    protected function afterCreate(): void
{
    $gaji = $this->record;
    $pivot = $this->data['pivot_nominal'] ?? [];

    // Simpan pivot dengan nominal
    foreach ($pivot as $komponen_id => $row) {
        $gaji->komponenGaji()->attach($komponen_id, [
            'nominal' => $row['nominal'] ?? 0
        ]);
    }
}


}


