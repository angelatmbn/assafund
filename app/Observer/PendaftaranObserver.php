<?php

namespace App\Observers;

use App\Models\Pendaftaran;
use App\Services\JurnalGeneratorService;

class PendaftaranObserver
{
    public function created(Pendaftaran $pendaftaran): void
    {
        app(JurnalGeneratorService::class)->fromPendaftaran($pendaftaran);
    }
}
