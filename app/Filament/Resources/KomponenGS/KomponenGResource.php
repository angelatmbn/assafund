<?php

namespace App\Filament\Resources\KomponenGS;

use App\Filament\Resources\KomponenGS\Pages\CreateKomponenG;
use App\Filament\Resources\KomponenGS\Pages\EditKomponenG;
use App\Filament\Resources\KomponenGS\Pages\ListKomponenGS;
use App\Filament\Resources\KomponenGS\Schemas\KomponenGForm;
use App\Filament\Resources\KomponenGS\Tables\KomponenGSTable;
use App\Models\KomponenG;
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
use App\Models\komponenGaji;

class KomponenGResource extends Resource
{
    protected static ?string $model = KomponenGaji::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-briefcase';  // Icon untuk jabatan
    protected static UnitEnum|string|null $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Komponen Gaji';

    public static function form(Schema $schema): Schema
    {
        return $schema
         ->schema([
            TextInput::make('id_komponenG')
                ->label('ID Komponen Gaji')
                ->default(fn () => komponenGaji::getIdKomponenG()) // Ambil default dari method generateNoFaktur
                ->readonly()
                ->disabled()
                ->columnSpanFull(),

            TextInput::make('nama_komponenG')
                ->label('Nama Komponen Gaji')
                ->placeholder('Contoh: Tunjangan, Bonus, dll.')
                ->required()
                ->maxLength(100),

            TextInput::make('nominalG')
                ->label('Nominal Gaji')
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
                Tables\Columns\TextColumn::make('id_komponenG')->label('Kode Komponen Gaji') ->searchable(),
                Tables\Columns\TextColumn::make('nama_komponenG')->label('Nama Komponen Gaji'),
                Tables\Columns\TextColumn::make('nominalG')->label('Nominal Gaji')->money('IDR'),
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
            'index' => ListKomponenGS::route('/'),
            'create' => CreateKomponenG::route('/create'),
            'edit' => EditKomponenG::route('/{record}/edit'),
        ];
    }
}
