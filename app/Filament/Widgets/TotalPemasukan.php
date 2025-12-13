<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalPemasukan extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';
    protected function getStats(): array
    {
        $totalSpp = 0;
        $totalDaftar = 0;

        return [
            Stat::make('Total Pemasukan', 'Rp ' . number_format($totalSpp + $totalDaftar, 0, ',', '.'))
                ->description('SPP + Pendaftaran')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('success'),

            Stat::make('Detail Pengeluaran', ' ')
                ->description("Gaji Pegawai: Rp 0, Operasional: Rp 0")
                ->extraAttributes(['class' => 'flex flex-col items-start']),
                    ];
    }
}