<?php

namespace App\Filament\Resources\Kebutuhans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DatePicker;


class KebutuhanForm
{
    public static function schema(): array
    {
        return [

            DatePicker::make('tanggal')->required(),

            TextInput::make('keterangan'),

            Repeater::make('items')
                ->relationship('items')
                ->schema([
            TextInput::make('nama_barang')->required(),

            TextInput::make('jumlah')
                ->numeric()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $set('total_harga', ($state ?? 0) * ($get('harga_satuan') ?? 0));
                }),

            TextInput::make('harga_satuan')
                ->numeric()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $set('total_harga', ($get('jumlah') ?? 0) * ($state ?? 0));
                }),

            TextInput::make('total_harga')
                ->numeric()
                ->disabled(),
             ])
                ->columns(4)
                ->reactive()
                ->dehydrated()
                ->afterStateUpdated(function ($state, callable $set) {
                    $total = collect($state)->sum(
                        fn ($item) => ($item['jumlah'] ?? 0) * ($item['harga_satuan'] ?? 0)
                    );

                    $set('total_nominal', $total);
    }),


            TextInput::make('total_nominal')
                ->disabled()
                ->numeric(),

            Hidden::make('created_by')->default(fn () => auth()->id()),
        ];
    }
}

