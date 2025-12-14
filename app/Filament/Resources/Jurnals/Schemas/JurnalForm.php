<?php

namespace App\Filament\Resources\Jurnals\Schemas;

use Filament\Schema\Schema;
use Filament\Forms\Form;
use Filament\Forms\Components;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;;
use App\Models\Coa as Coas;
use Filament\Resources\Resource;


class JurnalForm
{
    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Deskripsi Jurnal')
                ->schema([
                    DatePicker::make('tgl')
                        ->label('Tanggal')
                        ->default(now())
                        ->required(),

                    TextInput::make('no_referensi')
                        ->label('No Referensi')
                        ->maxLength(100),

                    Textarea::make('deskripsi')
                        ->label('Deskripsi'),
                ])
                ->columns(2)
                ->collapsible(),

            Section::make('Detail Jurnal')
                ->schema([
                    Repeater::make('jurnaldetail')
                        ->relationship('jurnaldetail')
                        ->label('Detail Jurnal')
                        ->schema([
                            Select::make('coa_id')
                                ->label('Akun')
                                ->options(Coa::pluck('nama_akun', 'id'))
                                ->searchable()
                                ->required(),

                            TextInput::make('debit')
                                ->numeric()
                                ->default(0)
                                ->label('Debit'),

                            TextInput::make('credit')
                                ->numeric()
                                ->default(0)
                                ->label('Kredit'),

                            Textarea::make('deskripsi')
                                ->rows(2)
                                ->label('Keterangan'),
                        ])
                        ->columns(2)
                        ->minItems(1),
                ])
                ->collapsible(),
        ]);
    }
}
