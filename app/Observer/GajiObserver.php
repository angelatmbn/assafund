<?php

namespace App\Observers;

use App\Models\Gaji;
use App\Services\JurnalGeneratorService;

class GajiObserver
{
    public function created(Gaji $gaji): void
    {
        app(JurnalGeneratorService::class)->fromGaji($gaji);
    }
}
