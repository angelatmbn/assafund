<?php

namespace App\Filament\Resources\Presensis\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class PresensiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_presensi')
                    ->required(),
                TextInput::make('id_pegawai')
                    ->required()
                    ->numeric(),
                DatePicker::make('tgl_presensi')
                    ->required(),
                TimePicker::make('waktu_masuk'),
                TimePicker::make('waktu_keluar'),
                Select::make('status_presensi')
                    ->options(['hadir' => 'Hadir', 'sakit' => 'Sakit', 'izin' => 'Izin', 'alfa' => 'Alfa'])
                    ->default('hadir')
                    ->required(),
            ]);
    }
}
