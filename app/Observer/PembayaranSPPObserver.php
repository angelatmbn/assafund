<?php

namespace App\Observers;

use App\Models\PembayaranSPP;
use App\Services\JurnalGeneratorService;

class PembayaranSPPObserver
{
    public function created(PembayaranSPP $pembayaran): void
    {
        app(JurnalGeneratorService::class)->fromPembayaranSPP($pembayaran);
    }
}
