<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\Action;
use App\Filament\Resources\Users\Exporters\UserExporter;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Tables\Actions\ViewAction;



class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                BadgeColumn::make('user_group')
                    ->color(fn ($state) => match ($state) {
                        'admin' => 'warning',
                        'pegawai' => 'success',
                        default => 'success',
                    }),
                Tables\Columns\TextColumn::make('created_at')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\ViewAction::make(),
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            // tombol tambahan
            ->headerActions([
                // tombol tambahan export csv dan excel
                ExportAction::make()->exporter(UserExporter::class)->color('success'),
                // tombol tambahan export pdf
                // ✅ Tombol Unduh PDF
                Action::make('downloadPdf')
                ->label('Unduh PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    $users = User::all();

                    $pdf = Pdf::loadView('pdf.users', ['users' => $users]);

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'user-list.pdf'
                    );
                })
            ])
            ->bulkActions([
               BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                // tambahan untuk export excel
                ExportBulkAction::make()->exporter(UserExporter::class)
            ]);
    }
}
