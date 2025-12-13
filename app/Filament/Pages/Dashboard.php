<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Actions\HeaderAction;

class Dashboard extends BaseDashboard
{
    public function getHeading(): string
    {
        return 'Dashboard';
    }

    public function getSubheading(): ?string
    {
        return 'Ringkasan Keuangan Sekolah Dasar';
    }
}
