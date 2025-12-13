<?php

namespace App\Filament\Resources\Gajis;

use App\Filament\Resources\Gajis\Pages\CreateGaji;
use App\Filament\Resources\Gajis\Pages\EditGaji;
use App\Filament\Resources\Gajis\Pages\ListGajis;
use App\Filament\Resources\Gajis\Schemas\GajiForm;
use App\Filament\Resources\Gajis\Tables\GajisTable;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\CheckboxList;


//Model
use App\Models\Pegawai;
use App\Models\Gaji;
use App\Models\komponenGaji;


// untuk form dan table
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;

class GajiResource extends Resource
{
    protected static ?string $model = Gaji::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Penggajian';
    protected static UnitEnum|string|null $navigationGroup = 'Transaksi';

    public static function form(Schema $schema): Schema
    {
        return $schema
           ->schema([
                TextInput::make('no_faktur')
                    ->label('No Faktur')
                    ->default(fn () => gaji::generateNoFaktur()) // Ambil default dari method generateNoFaktur
                    ->required()
                    ->readonly(), // Membuat field menjadi read-only

                //direlasikan ke tabel pegawai
                Select::make('id_pegawai')
                    ->label('Nama Pegawai')
                    ->relationship('pegawai', 'nama')
                    ->searchable() // Menambahkan fitur pencarian
                    ->preload() // Memuat opsi lebih awal untuk pengalaman yang lebih cepat
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($set, $get) {
                        $pegawaiId = $get('id_pegawai');
                        $tahun = $get('tahun_gaji');
                        $bulan = $get('bulan_gaji');

                        if ($pegawaiId && $tahun && $bulan) {
                            $jumlah = Gaji::hitungJumlahHadir($pegawaiId, $tahun, $bulan);
                            $set('jumlah_hadir', $jumlah);

                            $gajiPokok = Pegawai::find($pegawaiId)?->jabatan?->gaji ?? 0;
                            $set('total_gaji', $jumlah * $gajiPokok);
                        }
                    })
                , 
                TextInput::make('tahun_gaji')
                    ->label('Tahun')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $pegawaiId = $get('id_pegawai');
                        $tahun = $state;
                        $bulan = $get('bulan_gaji');

                        if ($pegawaiId && $tahun && $bulan) {
                            $set('jumlah_hadir', Gaji::hitungJumlahHadir($pegawaiId, $tahun, $bulan));
                        }
                    })
                    ->required(),

                Select::make('bulan_gaji')
                    ->label('Bulan')
                    ->options([
                        'Januari' => 'Januari',
                        'Februari' => 'Februari',
                        'Maret' => 'Maret',
                        'April' => 'April',
                        'Mei' => 'Mei',
                        'Juni' => 'Juni',
                        'Juli' => 'Juli',
                        'Agustus' => 'Agustus',
                        'September' => 'September',
                        'Oktober' => 'Oktober',
                        'November' => 'November',
                        'Desember' => 'Desember',
                    ])
                    ->reactive()
                    ->afterStateUpdated(fn($set, $get) => self::updateGajiOtomatis($set, $get))
                    ->required(),

                DatePicker::make('tgl_gaji')
                    ->label('Tanggal Gaji')
                    ->default(now())
                    ->disabled(),

                TextInput::make('jumlah_hadir')
                    ->label('Jumlah Hadir')
                    ->readOnly()
                    ->reactive()
                    ->afterStateUpdated(fn($set, $get) => self::updateGajiOtomatis($set, $get))
                    ->dehydrated(true),

                CheckboxList::make('komponen_gaji')
                ->label('Pilih Komponen Gaji')
                ->relationship('komponenGaji', 'nama_komponenG')
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {

                    // Ambil data komponen berdasarkan ID yang dicentang
                    $komponen = \App\Models\KomponenGaji::whereIn('id', $state)->get();

                    // Buat array untuk repeater otomatis
                    $items = [];
                    foreach ($komponen as $k) {
                        $items[] = [
                            'komponen_gaji_id' => $k->id,
                            'nama' => $k->nama_komponenG,
                            'nominal' => $k->nominalG, // auto ambil
                        ];
                    }

                    $set('detail_komponen', $items);
                }),

            Repeater::make('detail_komponen')
                ->reactive()
                ->label('Detail Komponen (Auto)')
                ->afterStateUpdated(fn($set, $get) => self::updateGajiOtomatis($set, $get)) // Tambahkan ini
                ->columns(2)
                ->schema([
                    TextInput::make('nama')
                        ->label('Komponen')
                        ->disabled(),

                    TextInput::make('nominal')
                        ->label('Nominal')
                        ->numeric(),
                ])
                ->columns(2)
                ->dehydrated(true), 


                TextInput::make('total_gaji')
                    ->label('Total Gaji')
                    ->required()->numeric()
                    ->reactive()
                    ->readOnly()
                    ->afterStateUpdated(fn($set, $get) => self::updateGajiOtomatis($set, $get))
           ]);
    }

    public static function table(Table $table): Table
    {
    return $table
            ->columns([
                //    TextColumn::make('id_presensi'),
                    TextColumn::make('no_faktur')->label('Nomor Faktur'),
                    TextColumn::make('tgl_gaji')->label('Tanggal'),
                    TextColumn::make('pegawai.nama')->label('Nama Pegawai')->searchable(),
                    TextColumn::make('tahun_gaji'),
                    TextColumn::make('bulan_gaji'),
                    TextColumn::make('tgl_gaji'),
                    TextColumn::make('jumlah_hadir'),
                    TextColumn::make('total_gaji')->money('idr', true),
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
            'index' => ListGajis::route('/'),
            'create' => CreateGaji::route('/create'),
            'edit' => EditGaji::route('/{record}/edit'),
        ];
    }

public static function updateGajiOtomatis($set, $get)
{
    $pegawaiId = $get('id_pegawai');
    $tahun = $get('tahun_gaji');
    $bulan = $get('bulan_gaji');

    // Jika input belum lengkap → jangan hitung
    if (!$pegawaiId || !$tahun || !$bulan) {
        return;
    }

    // Ambil ID Gaji saat ini (untuk mengambil data pivot komponen di mode Edit)
    $gajiId = $get('id');

    // -------------------------
    // 1. Hitung jumlah hadir (Kode tidak berubah)
    // -------------------------
    $jumlahHadir = Gaji::hitungJumlahHadir($pegawaiId, $tahun, $bulan);
    $set('jumlah_hadir', $jumlahHadir);

    // -------------------------
    // 2. & 3. Hitung Gaji Pokok Total (Kode tidak berubah)
    // -------------------------
    $pegawai = Pegawai::with('jabatan')->find($pegawaiId);
    $gajiPokok = (float) ($pegawai->gaji_pokok ?? $pegawai->jabatan->gaji ?? 0); // Disimplifikasi sedikit

    if ($gajiPokok === 0) {
        return;
    }

    $hariKerja = 25;
    $gajiPerHari = $gajiPokok / $hariKerja;
    $gajiPokokTotal = $jumlahHadir * $gajiPerHari;

    $set('gaji_pokok_total', round($gajiPokokTotal, 2));


    // -------------------------
    // 4. Jumlahkan SEMUA Komponen (Potongan/Pengurang)
// -------------------------
$totalPotongan = 0;

// A. Ambil dari input Repeater 'detail_komponen' (Prioritas untuk Realtime/Create)
$detailKomponen = $get('detail_komponen');

if (is_array($detailKomponen)) {
    foreach ($detailKomponen as $detail) {
        $nominal = $detail['nominal'] ?? 0;
        
        // --- START MODIFIKASI UNTUK MENGHILANGKAN TITIK DAN KOMA ---
        
        // Konversi ke string untuk memastikan fungsi str_replace bekerja
        $nominalString = (string) $nominal;
        
        // 1. Hilangkan titik (.) yang biasanya digunakan sebagai pemisah ribuan (e.g., 50.000 menjadi 50000)
        $nominalClean = str_replace('.', '', $nominalString); 
        
        // 2. Hilangkan koma (,) seperti yang Anda minta (e.g., 50000,00 menjadi 5000000)
        // PERHATIAN: Jika koma adalah pemisah desimal, ini akan menggeser desimal.
        $nominalClean = str_replace(',', '', $nominalClean);
        
        // 3. Konversi string yang sudah bersih (hanya angka) ke float
        $totalPotongan += floatval($nominalClean);
        
        // --- END MODIFIKASI ---
    }
}

// B. Jika di mode Edit, dan data Repeater belum terupdate, ambil dari database
if ($gajiId && $totalPotongan === 0) {
    // ... (kode pengambilan dari database, tidak perlu diubah)
    $gaji = \App\Models\Gaji::with('komponen')->find($gajiId);

    if ($gaji) {
        foreach ($gaji->komponen as $komp) {
            $nom = $komp->pivot->nominal ?? $komp->nominal ?? 0;
            $totalPotongan += floatval($nom);
        }
    }
}
    // -------------------------
    // 5. Total gaji akhir
    // -------------------------
    // Total Akhir = Gaji Pokok Total - Total Komponen (Potongan)
    $totalAkhir = $gajiPokokTotal - $totalPotongan;

    $set('total_gaji', round($totalAkhir, 2));
}
}