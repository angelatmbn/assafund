<?php

namespace App\Filament\Resources\KategoriPengeluarans;

use App\Filament\Resources\KategoriPengeluarans\Pages\CreateKategoriPengeluaran;
use App\Filament\Resources\KategoriPengeluarans\Pages\EditKategoriPengeluaran;
use App\Filament\Resources\KategoriPengeluarans\Pages\ListKategoriPengeluarans;
use App\Filament\Resources\KategoriPengeluarans\Schemas\KategoriPengeluaranForm;
use App\Filament\Resources\KategoriPengeluarans\Tables\KategoriPengeluaransTable;
use App\Models\KategoriPengeluaran;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Form;
use UnitEnum;
use Filament\Tables;
use Filament\Forms\Components as Forms;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class KategoriPengeluaranResource extends Resource
{
    protected static ?string $model = KategoriPengeluaran::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-m-rectangle-stack';  // Icon untuk jabatan
    protected static UnitEnum|string|null $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Kategori Pengeluaran';

    public static function form(Schema $schema): Schema
    {
        return $schema
        ->schema([
                TextInput::make('nama')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('Contoh: Operasional, Gaji, Peralatan'),

                Textarea::make('keterangan')
                    ->rows(2)
                    ->maxLength(255)
                    ->columnSpanFull(),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('keterangan')
                    ->limit(30),
            ])
            ->defaultSort('nama');    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKategoriPengeluarans::route('/'),
            'create' => CreateKategoriPengeluaran::route('/create'),
            'edit' => EditKategoriPengeluaran::route('/{record}/edit'),
        ];
    }
}
