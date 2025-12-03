<?php

namespace App\Filament\Resources\PembayaranSPPS;

use App\Filament\Resources\PembayaranSPPS\Pages\CreatePembayaranSPP;
use App\Filament\Resources\PembayaranSPPS\Pages\EditPembayaranSPP;
use App\Filament\Resources\PembayaranSPPS\Pages\ListPembayaranSPPS;
use App\Filament\Resources\PembayaranSPPS\Schemas\PembayaranSPPForm;
use App\Filament\Resources\PembayaranSPPS\Tables\PembayaranSPPSTable;
use App\Models\PembayaranSPP;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select; 
use Filament\Tables\Columns\TextColumn;

class PembayaranSPPResource extends Resource
{
    protected static ?string $model = PembayaranSPP::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static UnitEnum|string|null $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Pembayaran SPP';

    public static function form(Schema $schema): Schema
    {
       return $schema
                        ->schema([
                            Select::make('nis')
                                ->label('Siswa')
                                ->relationship('siswa', 'nama_lengkap')
                                ->required()
                                ->searchable()
                                ->preload(),
                            Select::make('bulan')
                                ->label('Bulan')
                                ->options([
                                    'Januari' => 'Januari',
                                    'Februari' => 'Februari',
                                    'Maret' => 'Maret',
                                    'April' => 'April',
                                    'Mei' => 'Mei',
                                    'Juni' => 'Juni',
                                    'Juli' => 'Juli',
                                    'Agustus' => 'Agustus',
                                    'September' => 'September',
                                    'Oktober' => 'Oktober',
                                    'November' => 'November',
                                    'Desember' => 'Desember',
                                ])
                                ->required(),

                            TextInput::make('tahun')
                                ->label('Tahun')
                                ->numeric()
                                ->required(),

                            DatePicker::make('tanggal_bayar')
                                ->label('Tanggal Bayar')
                                ->required(),

                            TextInput::make('biaya_pokok')
                                ->label('Biaya Pokok SPP')
                                ->numeric()
                                ->required()
                                ->columnSpanFull(),
                                ]);
    }

 public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siswa.nama_lengkap')->label('Siswa')->searchable(),  // Gunakan TextColumn langsung, dan akses via relasi
                TextColumn::make('bulan')->label('Bulan'),
                TextColumn::make('tahun')->label('Tahun'),
                TextColumn::make('tanggal_bayar')->label('Tanggal Bayar')->date(),
                TextColumn::make('biaya_pokok')->label('Biaya Pokok'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPembayaranSPPS::route('/'),
            'create' => CreatePembayaranSPP::route('/create'),
            'edit' => EditPembayaranSPP::route('/{record}/edit'),
        ];
    }
}
