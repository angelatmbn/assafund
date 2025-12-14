<?php

namespace App\Filament\Resources\Pengeluarans;

use App\Filament\Resources\Pengeluarans\Pages\CreatePengeluaran;
use App\Filament\Resources\Pengeluarans\Pages\EditPengeluaran;
use App\Filament\Resources\Pengeluarans\Pages\ListPengeluarans;
use App\Filament\Resources\Pengeluarans\Schemas\PengeluaranForm;
use App\Filament\Resources\Pengeluarans\Tables\PengeluaransTable;
use App\Models\Pengeluaran;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;    
use App\Models\KategoriPengeluaran;
use App\Models\Kebutuhan;
use App\Models\Gaji;
use Filament\Schemas\Components\Grid;


class PengeluaranResource extends Resource
{
    protected static ?string $model = Pengeluaran::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-m-banknotes';
    protected static UnitEnum|string|null $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Pengeluaran';

    public static function form(Schema $schema): Schema
    {
        return $schema
        ->schema([
        DatePicker::make('tanggal')
                    ->required()
                    ->default(now()),

                Select::make('kategori_pengeluaran_id')
                    ->label('Kategori')
                    ->relationship('kategoriPengeluaran', 'nama')
                    ->required(),

                Select::make('sumber_type')
                    ->label('Sumber Pengeluaran')
                    ->options([
                        'kebutuhan' => 'List Kebutuhan',
                        'operasional' => 'Operasional',
                    ])
                    ->live()
                    ->required()
                    ->default('operasional')
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Reset semua field saat ganti sumber type
                        $set('sumber_id', null);
                        $set('nama_pengeluaran', '');
                        $set('jumlah', 0);
                        $set('harga_satuan', 0);
                        $set('total', 0);
                    }),
                
                // Dropdown untuk pilih kebutuhan
                Select::make('sumber_id')
                    ->label('Pilih Kebutuhan')
                    ->options(function () {
                        return Kebutuhan::query()
                            ->with('items')
                            ->orderBy('tanggal', 'desc')
                            ->get()
                            ->mapWithKeys(function ($kebutuhan) {
                                $totalHarga = $kebutuhan->items->sum('total_harga');
                                return [
                                    $kebutuhan->id => sprintf(
                                        '%s - %s (Rp %s)',
                                        $kebutuhan->nomor_list,
                                        $kebutuhan->judul,
                                        number_format($totalHarga, 0, ',', '.')
                                    )
                                ];
                            });
                    })
                    ->searchable()
                    ->live()
                    ->visible(fn (callable $get) => $get('sumber_type') === 'kebutuhan')
                    ->required(fn (callable $get) => $get('sumber_type') === 'kebutuhan')
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if (!$state || $get('sumber_type') !== 'kebutuhan') {
                            return;
                        }
                        
                        $kebutuhan = Kebutuhan::with('items')->find($state);
                        
                        if ($kebutuhan) {
                            $totalHarga = $kebutuhan->items->sum('total_harga');
                            $totalJumlah = $kebutuhan->items->sum('jumlah');
                            
                            $set('nama_pengeluaran', $kebutuhan->judul);
                            $set('jumlah', $totalJumlah);
                            $set('harga_satuan', $totalJumlah > 0 ? $totalHarga / $totalJumlah : 0);
                            $set('total', $totalHarga);
                        }
                    })
                    ->placeholder('Pilih dari daftar kebutuhan'),
                
                TextInput::make('nama_pengeluaran')
                    ->label('Nama Pengeluaran')
                    ->required()
                    ->maxLength(255)
                    ->readOnly(fn (callable $get) => $get('sumber_type') === 'kebutuhan')
                    // HAPUS disabled(), hanya pakai readOnly saja
                    ->dehydrated() // Pastikan selalu dehydrated
                    ->placeholder(fn (callable $get) => 
                        $get('sumber_type') === 'operasional' 
                            ? 'Masukkan nama pengeluaran' 
                            : 'Otomatis dari kebutuhan'
                    )
                    ->columnSpanFull(),
        
                TextInput::make('total')
    ->label('Total')
    ->numeric()
    ->prefix('Rp')
    ->required()
    ->default(0)

    // 🔑 READONLY HANYA JIKA SUMBER = KEBUTUHAN
    ->readOnly(fn (callable $get) => $get('sumber_type') === 'kebutuhan')

    // 🔑 TETAP DISIMPAN KE DB
    ->dehydrated(fn () => true)

    ->helperText(fn (callable $get) =>
        $get('sumber_type') === 'kebutuhan'
            ? 'Total dari semua item kebutuhan'
            : 'Isi total pengeluaran manual'
                ),

                    

        Textarea::make('catatan'),
    ]);
    }

    public static function table(Table $table): Table
    {
         return $table
            ->columns([
                TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),

                TextColumn::make('kategoriPengeluaran.nama')
                    ->label('Kategori'),

                TextColumn::make('nama_pengeluaran')
                    ->label('Nama Pengeluaran'),

                TextColumn::make('total')
                    ->numeric()
                    ->money('IDR', true)
                    ->alignRight()
                    ->sortable(),

                TextColumn::make('sumber_type')
                    ->label('Sumber'),
            ])
            ->defaultSort('tanggal', 'desc');
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
            'index' => ListPengeluarans::route('/'),
            'create' => CreatePengeluaran::route('/create'),
            'edit' => EditPengeluaran::route('/{record}/edit'),
        ];
    }

    public static function mutateFormDataBeforeCreate(array $data): array
{
    if ($data['sumber_type'] === 'kebutuhan') {
        $k = \App\Models\Kebutuhan::find($data['sumber_id']);

        if ($k) {
            $data['nama_pengeluaran'] = $k->judul; // atau kolom lain sesuai kebutuhan
            $data['jumlah'] = 1;
            $data['harga_satuan'] = $k->total_harga;
            $data['total'] = $k->total_harga;
        }
    }

    return $data;
}
}
