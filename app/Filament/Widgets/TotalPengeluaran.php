<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalPengeluaran extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';
    protected function getStats(): array
    {
        $totalGaji = 0;
        $totalOperasional = 0;

        return [
            Stat::make('Total Pengeluaran', 'Rp ' . number_format($totalGaji + $totalOperasional, 0, ',', '.'))
                ->description('Gaji + Operasional')
                ->descriptionIcon('heroicon-o-arrow-trending-down')
                ->color('danger'),

            Stat::make('Detail Pengeluaran', ' ')
                ->description("Gaji Pegawai: Rp 0; Operasional: Rp 0")
                ->extraAttributes(['class' => 'flex flex-col items-start']),
                    ];
    }
}