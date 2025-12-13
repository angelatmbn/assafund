<?php

namespace App\Filament\Resources\Jabatan;

use UnitEnum;
use BackedEnum;

use App\Filament\Resources\Jabatan\Pages;
use App\Models\Jabatan;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;

class JabatanResource extends Resource
{
    protected static ?string $model = Jabatan::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-briefcase';  // Icon untuk jabatan
    protected static UnitEnum|string|null $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Jabatan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('id_jabatan')
                    ->label('ID Jabatan')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->numeric(),  // Karena di migration Anda integer

                Forms\Components\TextInput::make('nama_jabatan')
                    ->label('Nama Jabatan')
                    ->required(),

                Forms\Components\TextInput::make('gaji_pokok')
                    ->label('Gaji Pokok')
                    ->required()
                    ->numeric()  // Asumsikan gaji adalah angka
                    ->prefix('Rp')  // Opsional: Tambah prefix untuk mata uang
            ]);
    }

        public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_jabatan')->label('ID Jabatan'),
                Tables\Columns\TextColumn::make('nama_jabatan')->label('Nama Jabatan'),
                Tables\Columns\TextColumn::make('gaji_pokok')->label('Gaji Pokok')->money('IDR'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJabatan::route('/'),
            'create' => Pages\CreateJabatan::route('/create'),
            'edit' => Pages\EditJabatan::route('/{record}/edit'),
        ];
    }
}