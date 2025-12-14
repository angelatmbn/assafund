<?php

// app/Providers/Filament/GuruPanelProvider.php
namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Http\Middleware\Authenticate;   // penting
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Middleware\DispatchServingFilamentEvent as SupportDispatch;

class GuruPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('guru')
            ->path('guru')
            ->brandName('Keuangan SD')
            ->authGuard('web')          // <-- pakai guard web yang sama
            ->colors([
                'primary' => '#00933F',
            ])
            // ->login()                   // form login tetap tersedia kalau akses langsung /guru/login
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,    // cek Auth::user() dari guard 'web'
            ])
            ->resources([
                \App\Filament\Resources\Siswas\SiswaResource::class,
                \App\Filament\Resources\Presensis\PresensiResource::class,
            ]);
    }
}
