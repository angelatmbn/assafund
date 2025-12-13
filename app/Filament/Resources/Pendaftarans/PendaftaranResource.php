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
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\KomponenBiayaDaftar;
use Filament\Forms\Components\Repeater;

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
            Repeater::make('items')
                ->label('Daftar Pendaftaran')
                ->columnSpan('full')   // kalau dibungkus Grid/Section
                ->columns(1)           // satu kolom per baris
                ->schema([
                    Select::make('siswa')
                        ->label('Siswa')
                        ->relationship('siswa', 'nama_lengkap')
                        ->required()
                        ->searchable()
                        ->preload(),

                    Select::make('komponen_biaya')
                        ->label('Komponen Biaya')
                        ->options(
                            KomponenBiayaDaftar::pluck('nama_komponen', 'id_komponen')->toArray()
                        )
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $nominal = KomponenBiayaDaftar::where('id_komponen', $state)->value('nominal');
                            $set('nominal', $nominal ?? 0);
                        }),

                    TextInput::make('nominal')
                        ->label('Nominal')
                        ->numeric()
                        ->prefix('Rp')
                        ->disabled()
                        ->dehydrated(true),     // tapi nilainya tetap dikirim ke array $data['items'],
                    
                    DatePicker::make('tanggal')
                        ->label('Tanggal')
                        ->required(),

                    TextInput::make('kelas')
                        ->label('Kelas')
                        ->required(),
                ])
                ->columns(2)
                ->minItems(1),
        ]);
}

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('siswa.nama_lengkap')
                    ->label('Siswa')
                    ->searchable(),

                Tables\Columns\TextColumn::make('KomponenBiayaDaftar.nama_komponen')
                    ->label('Komponen Biaya'),

                Tables\Columns\TextColumn::make('KomponenBiayaDaftar.nominal')
                    ->label('Nominal')
                    ->money('IDR'),

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