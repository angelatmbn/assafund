<?php

namespace App\Filament\Resources\Kebutuhans;

use App\Filament\Resources\Kebutuhans\Pages\CreateKebutuhan;
use App\Filament\Resources\Kebutuhans\Pages\EditKebutuhan;
use App\Filament\Resources\Kebutuhans\Pages\ListKebutuhans;
use App\Filament\Resources\Kebutuhans\Schemas\KebutuhanForm;
use App\Filament\Resources\Kebutuhans\Tables\KebutuhansTable;
use App\Models\Kebutuhan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Carbon\Carbon;
use UnitEnum;
use Illuminate\Support\Facades\Auth;



class KebutuhanResource extends Resource
{
    protected static ?string $model = Kebutuhan::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document';  // Icon untuk jabatan
    protected static UnitEnum|string|null $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'List Kebutuhan';


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKebutuhans::route('/'),
            'create' => CreateKebutuhan::route('/create'),
            'edit' => EditKebutuhan::route('/{record}/edit'),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['total_nominal'] = collect($data['items'])->sum('total_harga');
        return $data;
    }

    public static function form(Schema $schema): Schema
    {
    return $schema->components(
        KebutuhanForm::schema()
    );
    }

    public static function table(Table $table): Table
    {
        return KebutuhansTable::configure($table);
    }

}
