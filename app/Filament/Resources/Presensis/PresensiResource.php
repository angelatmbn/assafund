<?php

namespace App\Filament\Resources\Presensis;

use App\Filament\Resources\Presensis\Pages\CreatePresensi;
use App\Filament\Resources\Presensis\Pages\EditPresensi;
use App\Filament\Resources\Presensis\Pages\ListPresensis;
use App\Filament\Resources\Presensis\Schemas\PresensiForm;
use App\Filament\Resources\Presensis\Tables\PresensisTable;
use App\Models\Presensi;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Carbon\Carbon;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
// Pastikan ini ada di bagian atas file Anda:
//use Filament\Schemas\Schema; // Ini menggantikan Filament\Forms\Form
// untuk form dan table
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;

// untuk model ke pegawai
use App\Models\Pegawai;

class PresensiResource extends Resource
{
    protected static ?string $model = Presensi::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Presensi';

     // tambahan buat grup masterdata
    protected static UnitEnum|string|null $navigationGroup = 'Transaksi';

    public static function form(Schema $schema): Schema
    {
         return $schema
           ->schema([
             //   TextInput::make('id_presensi')
             //       ->label('ID Presensi')
                //    ->default(fn () => Presensi::getIdPresensi())
               //     ->disabled() // lebih aman daripada readonly()
                //    ->dehydrated(false) // supaya tidak insert ulang ke DB
                //,
                //direlasikan ke tabel pegawai
                Select::make('id_pegawai')
                    ->label('Nama Pegawai')
                    ->relationship('pegawai', 'nama')
                    ->searchable() // Menambahkan fitur pencarian
                    ->preload() // Memuat opsi lebih awal untuk pengalaman yang lebih cepat
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $pgi = Pegawai::find($state);
                            $set('nama', $pgi->name);
                        }
                    })
                , 
                DatePicker::make('tgl_presensi')
                    ->label('Tanggal Presensi')
                    ->default(now())
                    ->disabled()
                    ->dehydrated(false)
                ,
                TimePicker::make('waktu_masuk')
                    ->label('Waktu Masuk')
                    ->default(fn () => Carbon::now()->format('H:i')) // Menggunakan Carbon untuk jam saat ini
                    ->disabled()
                ->dehydrated()
                    ->required(), // Jika Anda ingin memastikan data ini ada, set required()

                TimePicker::make('waktu_keluar')
                    ->label('Waktu Keluar')
                    ->default('23:59') // Langsung set string '23:59'
                    ->disabled()
                ->dehydrated()
                    ->required(), // Jika Anda ingin memastikan data ini ada, set required()
                 // Status Presensi
                TextInput::make('status_presensi')
                    ->label('Status Presensi')
                    ->default('Hadir')
                    ->disabled()
                    ->dehydrated()
                ,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            //    TextColumn::make('id_presensi'),
                TextColumn::make('pegawai.nama')->label('Nama Pegawai')->searchable(),
                TextColumn::make('tgl_presensi')
                ->date('d F Y') 
                ->label('Tanggal Presensi'),
                TextColumn::make('waktu_masuk')->label('Waktu Chack-in'),
                TextColumn::make('waktu_keluar')->label('Waktu Chack-out'),
                TextColumn::make('status_presensi'),
            ])
            ->filters([
                //
            ])
            //->actions([
              //  Tables\Actions\ViewAction::make(),
              //  Tables\Actions\EditAction::make(),
              //  Tables\Actions\DeleteAction::make(),
            //])
            ->bulkActions([
               // Tables\Actions\BulkActionGroup::make([
                //Tables\Actions\DeleteBulkAction::make(),
                //]),
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
            'index' => ListPresensis::route('/'),
            'create' => CreatePresensi::route('/create'),
            'edit' => EditPresensi::route('/{record}/edit'),
        ];
    }
}
