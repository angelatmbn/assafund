<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Pendaftaran;
use App\Models\PembayaranSPP;
use App\Models\Gaji;
use App\Observers\PendaftaranObserver;
use App\Observers\PembayaranSPPObserver;
use App\Observers\GajiObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pendaftaran::observe(PendaftaranObserver::class);
        // PembayaranSPP::observe(PembayaranSPPObserver::class);
        // Gaji::observe(GajiObserver::class);
    }
}
