<?php

namespace App\Filament\Resources\Pendaftarans;

use App\Filament\Resources\Pendaftarans\Pages;
use App\Models\Pendaftaran;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use BackedEnum;
use UnitEnum;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select; 

class PendaftaranResource extends Resource
{
    protected static ?string $model = Pendaftaran::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-user';
    protected static UnitEnum|string|null $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Pendaftaran';

    public static function form(Schema $schema): Schema
    {
        return $schema
                            ->schema([
                                // KIRI
                                Select::make('siswa_id')  // Gunakan foreign key, bukan 'siswa'
                                    ->label('Siswa')
                                    ->relationship('siswa', 'nama_lengkap')  // 'siswa' adalah nama relasi, 'nama' adalah field di tabel siswa
                                    ->required()
                                    ->searchable()  // Opsional: Membuat dropdown searchable
                                    ->preload(),    // Opsional: Memuat data awal untuk performa

                                // KANAN
                                TextInput::make('nominal')
                                    ->label('Nominal')
                                    ->numeric()
                                    ->required(),

                                // KIRI
                                TextInput::make('jumlah_bayar')
                                    ->label('Jumlah Bayar')
                                    ->numeric()
                                    ->required(),

                                // KANAN
                                DatePicker::make('tanggal')
                                    ->label('Tanggal')
                                    ->required(),

                                // FULL ROW (kiri)
                                TextInput::make('kelas')
                                    ->label('Kelas')
                                    ->required(),
                            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('siswa')->label('Siswa')->searchable(),
                Tables\Columns\TextColumn::make('nominal')->label('Nominal'),
                Tables\Columns\TextColumn::make('jumlah_bayar')->label('Jumlah Bayar'),
                Tables\Columns\TextColumn::make('kelas')->label('Kelas'),
                Tables\Columns\TextColumn::make('tanggal')->label('Tanggal')->date(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPendaftarans::route('/'),
            'create' => Pages\CreatePendaftaran::route('/create'),
            'edit' => Pages\EditPendaftaran::route('/{record}/edit'),
        ];
    }
}