<?php

namespace App\Filament\Resources\KomponenBiayaDaftars;

use App\Filament\Resources\KomponenBiayaDaftars\Pages\CreateKomponenBiayaDaftar;
use App\Filament\Resources\KomponenBiayaDaftars\Pages\EditKomponenBiayaDaftar;
use App\Filament\Resources\KomponenBiayaDaftars\Pages\ListKomponenBiayaDaftars;
use App\Filament\Resources\KomponenBiayaDaftars\Schemas\KomponenBiayaDaftarForm;
use App\Filament\Resources\KomponenBiayaDaftars\Tables\KomponenBiayaDaftarsTable;
use App\Models\KomponenBiayaDaftar;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables;


//model

class KomponenBiayaDaftarResource extends Resource
{
    protected static ?string $model = KomponenBiayaDaftar::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-briefcase';  // Icon untuk jabatan
    protected static UnitEnum|string|null $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Komponen Biaya Daftar';

    public static function form(Schema $schema): Schema
    {
        return $schema
         ->schema([
            TextInput::make('id_komponen')
                ->label('ID Komponen Daftar')
                ->default(fn () => komponenBiayaDaftar::getIdKomponen()) // Ambil default dari method generateNoFaktur
                ->readonly()
                ->disabled()
                ->columnSpanFull(),

            TextInput::make('nama_komponen')
                ->label('Nama Komponen')
                ->placeholder('Contoh: Pendaftaran, Buku, Baju, Outing Class')
                ->required()
                ->maxLength(100),

            TextInput::make('nominal')
                ->label('Nominal')
                ->numeric()
                ->prefix('Rp')
                ->required()
                ->minValue(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_komponen')->label('Kode Komponen'),
                Tables\Columns\TextColumn::make('nama_komponen')->label('Nama Komponen Pendaftaran'),
                Tables\Columns\TextColumn::make('nominal')->label('Nominal')->money('IDR'),
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
            'index' => ListKomponenBiayaDaftars::route('/'),
            'create' => CreateKomponenBiayaDaftar::route('/create'),
            'edit' => EditKomponenBiayaDaftar::route('/{record}/edit'),
        ];
    }
}
