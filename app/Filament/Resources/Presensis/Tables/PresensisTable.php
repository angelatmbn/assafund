<?php

namespace App\Filament\Resources\Presensis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PresensisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_presensi')
                    ->searchable(),
                TextColumn::make('id_pegawai')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tgl_presensi')
                    ->date()
                    ->sortable(),
                TextColumn::make('waktu_masuk')
                    ->time()
                    ->sortable(),
                TextColumn::make('waktu_keluar')
                    ->time()
                    ->sortable(),
                TextColumn::make('status_presensi')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
