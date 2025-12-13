<?php

namespace App\Filament\Resources\Pegawais;

use UnitEnum;
use BackedEnum;
use App\Filament\Resources\Pegawais\Pages;
use App\Models\Pegawai;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-user';
    protected static UnitEnum|string|null $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Pegawai';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('nip')
                    ->label('NIP')
                    ->required()
                    ->default(fn () => Pegawai::getNipBaru())
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('nama')
                    ->label('Nama Lengkap')
                    ->required(),

                // Di dalam public static function form(Schema $schema): Schema
                Forms\Components\Select::make('jabatan_id')
                    ->label('Jabatan')
                    ->relationship('jabatan', 'nama_jabatan')  // Mengambil dari model Jabatan, menampilkan nama_jabatan
                    ->required()
                    ->preload()  // Memuat opsi saat form dibuka (lebih cepat)
                    ->searchable()  // Opsional: Tambah pencarian jika jabatan banyak
->reactive()
    ->afterStateUpdated(function ($state, callable $set) {
        $gaji = \App\Models\Jabatan::find($state)?->gaji_pokok;
        $set('gaji_pokok', $gaji ?? 0);
    }),
                Forms\Components\Radio::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Pria' => 'Pria',
                        'Wanita' => 'Wanita',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('gaji_pokok')
    ->label('Gaji Pokok')
    ->numeric()
    ->readOnly()   // tidak boleh diedit
                    ->prefix('Rp')  // Opsional: Tambah prefix untuk mata uang
 ->dehydrated(true)
    ->default(0),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nip')->label('NIP'),
                Tables\Columns\TextColumn::make('nama')->label('Nama Lengkap'),
                Tables\Columns\TextColumn::make('jabatan.nama_jabatan')->label('Jabatan'),
                Tables\Columns\TextColumn::make('gender')->label('Jenis Kelamin'),
                Tables\Columns\TextColumn::make('gaji_pokok')->label('Gaji Pokok')->money('idr', true),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'edit' => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }
}