<?php

namespace App\Filament\Resources\TahunAjars;

use App\Filament\Resources\TahunAjars\Pages\CreateTahunAjar;
use App\Filament\Resources\TahunAjars\Pages\EditTahunAjar;
use App\Filament\Resources\TahunAjars\Pages\ListTahunAjars;
use App\Filament\Resources\TahunAjars\Schemas\TahunAjarForm;
use App\Filament\Resources\TahunAjars\Tables\TahunAjarsTable;
use App\Models\TahunAjar;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Form;
use UnitEnum;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;


class TahunAjarResource extends Resource
{
    protected static ?string $model = TahunAjar::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Tahun Ajar';
    protected static UnitEnum|string|null $navigationGroup = 'Master Data';


    public static function form(Schema $schema): Schema
    {
        return $schema
             ->schema([
            TextInput::make('tahun')
                ->label('Tahun Ajaran')
                ->placeholder('1')
                ->required(),

            Select::make('semester')
                ->options([
                    'Ganjil' => 'Ganjil',
                    'Genap' => 'Genap',
                ])
                ->required(),

            /*Toggle::make('is_active')
                ->label('Tahun Ajaran Aktif')
                ->default(false)
                ->reactive()
                ->afterStateUpdated(function ($state, $set, $record) {
                    // Kalau diset aktif, nonaktifkan tahun lain
                    if ($state) {
                        \App\Models\TahunAjar::where('id', '!=', $record?->id)
                            ->update(['is_active' => false]);
                    }
                }),*/

            TextInput::make('biaya_pendaftaran')
                ->label('Biaya Pendaftaran')
                ->prefix('Rp')
                ->numeric()
                ->required()
                ->default(0),
    
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tahun')->label('Tahun Ajaran'),
                TextColumn::make('semester')->label('Semester')
                ->sortable(),
                /*TextColumn::make('is_active')->label('Status')->formatStateUsing(fn ($state) => $state ? 'Aktif' : 'Tidak Aktif')
                ->colors([
                    'success' => fn ($state) => $state == 1,      // Hijau
                    'danger'  => fn ($state) => $state == 0,      // Merah
                ])
                ->icons([
                    'heroicon-o-check-circle' => fn ($state) => $state == 1,
                    'heroicon-o-x-circle' => fn ($state) => $state == 0,
                ])
                ->iconPosition('start'),*/
                TextColumn::make('biaya_pendaftaran')->label('Biaya Pendaftaran')->money('idr', true),
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
            'index' => ListTahunAjars::route('/'),
            'create' => CreateTahunAjar::route('/create'),
            'edit' => EditTahunAjar::route('/{record}/edit'),
        ];
    }
}
