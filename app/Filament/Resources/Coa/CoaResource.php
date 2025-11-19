<?php

namespace App\Filament\Resources\Coa;

use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use UnitEnum;
use BackedEnum;
use Filament\Tables\Actions;
use Filament\Schemas\Schema;  // Added
use App\Filament\Resources\Coa\Pages;
use App\Filament\Resources\Coa\RelationManagers;
use App\Models\Coa;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CoaResource extends Resource
{
    protected static ?string $model = \App\Models\Coa::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static UnitEnum|string|null $navigationGroup = 'Master Data';

    public static function form(Schema $schema): Schema  // Corrected
    {
        return $schema->schema([
            TextInput::make('header_akun')
                ->required()
                ->placeholder('Masukkan header akun'),

            TextInput::make('no_akun')
                ->required()
                ->placeholder('Masukkan kode akun'),

            TextInput::make('nama_akun')
                ->autocapitalize('words')
                ->label('Nama akun')
                ->required()
                ->placeholder('Masukkan nama akun'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('header_akun'),
                TextColumn::make('no_akun'),
                TextColumn::make('nama_akun')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('header_akun')
                    ->options([
                        1 => 'Aset/Aktiva',
                        2 => 'Utang',
                        3 => 'Modal',
                        4 => 'Pendapatan',
                        5 => 'Beban',
                    ]),
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
            'index' => Pages\ListCoa::route('/'),
            'create' => Pages\CreateCoa::route('/create'),
            'edit' => Pages\EditCoa::route('/{record}/edit'),
        ];
    }
}