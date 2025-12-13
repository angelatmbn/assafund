<?php

namespace App\Filament\Resources\Pendaftarans\Pages;

use App\Filament\Resources\Pendaftarans\PendaftaranResource;
use App\Models\Pendaftaran;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreatePendaftaran extends CreateRecord
{
    protected static string $resource = PendaftaranResource::class;

    protected function handleRecordCreation(array $data): Pendaftaran
    {
        $last = null;

        foreach ($data['items'] as $item) {
            $last = Pendaftaran::create([
                'siswa'          => $item['siswa'] ?? null,
                'komponen_biaya' => $item['komponen_biaya'] ?? null,
                'nominal'        => $item['nominal'] ?? 0,
                'kelas'          => $item['kelas'] ?? null,
                'tanggal'        => $item['tanggal'] ?? null,
            ]);
        }

        // sekadar memenuhi tipe kembalian, redirect-nya sudah kita override
        return $last ?? new Pendaftaran();
    }

        public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full; // atau '7xl' / 'full'
    }

    protected function getRedirectUrl(): string
{
    // Setelah submit, kembali ke list pendaftaran
    return $this->getResource()::getUrl('index');
}
}