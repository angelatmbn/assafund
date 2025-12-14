<?php

namespace App\Filament\Resources\Jurnals;

use App\Filament\Resources\Jurnals\Pages\CreateJurnal;
use App\Filament\Resources\Jurnals\Pages\EditJurnal;
use App\Filament\Resources\Jurnals\Pages\ListJurnals;
use App\Filament\Resources\Jurnals\Schemas\JurnalForm;
use App\Filament\Resources\Jurnals\Tables\JurnalsTable;
use App\Models\Jurnal;
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
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Repeater;
use App\Models\Coa ;
use Filament\Forms\Components as Forms;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class JurnalResource extends Resource
{
    protected static ?string $model = Jurnal::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-briefcase';  // Icon untuk jabatan
    protected static UnitEnum|string|null $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Jurnal';

    public static function form(Schema $schema): Schema
{
    return $schema
    ->components([
            Section::make('Deskripsi Jurnal')
            ->columnSpanFull()
                ->schema([
                    DatePicker::make('tgl')
                        ->label('Tanggal')
                        ->default(now())
                        ->required(),

                    Forms\TextInput::make('no_referensi')
                        ->label('No Referensi')
                        ->maxLength(100),

                    Forms\Textarea::make('deskripsi')
                        ->label('Deskripsi'),
                ])
                ->columns(3)
                ->collapsible(),

            Section::make('Detail Jurnal')
                ->columnSpanFull()
                ->schema([
                    Repeater::make('jurnaldetail')
                        ->relationship('jurnaldetail')
                        ->label('Detail Jurnal')
                        ->schema([
                            Select::make('no_akun')
                                ->label('Akun')
                                ->options(Coa::pluck('nama_akun', 'id'))
                                ->searchable()
                                ->required(),

                    Forms\TextInput::make('debit')
                                ->numeric()
                                ->default(0)
                                ->prefix('Rp ')
                                ->required()
                                ->label('Debit'),

                    Forms\TextInput::make('credit')
                                ->numeric()
                                ->default(0)
                                ->prefix('Rp ')
                                ->required()
                                ->label('Kredit'),

                    Forms\Textarea::make('deskripsi')
                                ->rows(2)
                                ->label('Keterangan'),
                        ])
                        ->columns(2)
                        ->minItems(1),
                ])
                ->collapsible(),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
        Tables\Columns\TextColumn::make('tgl')->date(),
        Tables\Columns\TextColumn::make('no_referensi')->label('Ref'),
        Tables\Columns\TextColumn::make('deskripsi')->limit(30),
        Tables\Columns\TextColumn::make('total_debit')
            ->label('Total Debit')
            ->alignEnd()
            ->getStateUsing(function ($record) {
                return $record->jurnaldetail()->sum('debit');
            })
            ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.'))
            ->money('IDR', true) // opsional, biar format Rp
,
        Tables\Columns\TextColumn::make('total_kredit')
            ->label('Total Kredit')
            ->alignEnd()
            ->getStateUsing(function ($record) {
                return $record->jurnaldetail()->sum('credit');
            })
            ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.'))
            ->money('IDR', true), // opsional, biar format Rp
        ])
         ->filters([
            Filter::make('tgl')
                ->form([
                    DatePicker::make('dari')
                        ->label('Dari Tanggal'),
                    DatePicker::make('sampai')
                        ->label('Sampai Tanggal'),
                ])
                ->query(function (Builder $query, array $data) {
                    return $query
                        ->when(
                            $data['dari'],
                            fn ($q) => $q->whereDate('tgl', '>=', $data['dari'])
                        )
                        ->when(
                            $data['sampai'],
                            fn ($q) => $q->whereDate('tgl', '<=', $data['sampai'])
                        );
                }),
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
            'index' => ListJurnals::route('/'),
            'create' => CreateJurnal::route('/create'),
            'edit' => EditJurnal::route('/{record}/edit'),
        ];
    }
}
