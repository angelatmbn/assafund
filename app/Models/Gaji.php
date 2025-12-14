<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Support\Facades\DB; // Perlu diimpor
use App\Models\Presensi;
use Carbon\Carbon;
use App\Models\Pegawai;
use App\Models\komponenGaji;

class Gaji extends Model
{
    /** @use HasFactory<\Database\Factories\GajiFactory> */
    use HasFactory;
    protected $table = 'gaji';

     // Relationship model
    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'id');  // Eksplisit: foreign_key, owner_key
    }

        public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id');  // Eksplisit: foreign_key, owner_key
    }

    public function komponenGaji()
{
    return $this->belongsToMany(KomponenGaji::class, 'gaji_komponen')
                ->withPivot('nominal')
                ->withTimestamps();
}


    // Pastikan 'no_faktur' bisa diisi
    protected $guarded = []; 
    
     // Tentukan kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'id_pegawai',
        'no_faktur',
        'tahun_gaji',
        'bulan_gaji',
        'tanggal_gaji',
        'tunjangan_total',
        'jumlah_hadir',
        'total_gaji',
    ];

    /**
     * Metode boot() untuk mengaktifkan Model Events.
     */
    protected static function boot()
    {
        parent::boot();

        // Event 'creating' dipanggil sebelum data disimpan.
        static::creating(function ($gaji) {
            // Panggil fungsi untuk membuat Nomor Faktur baru
            $gaji->no_faktur = self::generateNoFaktur();
        });

        // Use the 'creating' event to set tgl_gaji as well, or separate it
        static::creating(function ($gaji) { // <-- Make sure $gaji is here!
            // Lines 57-61 (tgl_gaji logic)
            if (empty($gaji->tgl_gaji)) {
                $gaji->tgl_gaji = now();
            }
        });
    }

    /**
     * Logika untuk menghasilkan Nomor Faktur baru (PGJ-0001, PGJ-0002, dst.)
     */
    public static function generateNoFaktur()
    {
        // 1. Ambil No Faktur terakhir dari database
        $lastGaji = self::query()
                       ->latest('id') // Ambil data terakhir berdasarkan ID
                       ->first();

        $prefix = 'PGJ-'; // Prefix yang diinginkan
        $nextNumber = 1;

        if ($lastGaji && $lastGaji->no_faktur) {
            // 2. Ekstrak bagian angka dari No Faktur terakhir (misalnya, dari PGJ-0012, ambil 12)
            $lastNumber = (int) substr($lastGaji->no_faktur, 4); // Ambil dari karakter ke-5 (setelah 'PGJ-')
            $nextNumber = $lastNumber + 1;
        }

        // 3. Format angka menjadi string 4 digit (0001, 0002, dst.)
        $paddedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // 4. Gabungkan Prefix dan Angka
        return $prefix . $paddedNumber;
    }

    public static function hitungJumlahHadir($pegawaiId, $tahun, $bulan)
    {
        // Konversi bulan dari teks → angka
    $bulanAngka = [
        'Januari' => 1,
        'Februari' => 2,
        'Maret' => 3,
        'April' => 4,
        'Mei' => 5,
        'Juni' => 6,
        'Juli' => 7,
        'Agustus' => 8,
        'September' => 9,
        'Oktober' => 10,
        'November' => 11,
        'Desember' => 12,
    ];
    $bulan = $bulanAngka[$bulan] ?? null;
        // PENTING: Gunakan Model Presensi::class
        return Presensi::query()
            ->where('id_pegawai', $pegawaiId)
            ->whereYear('tgl_presensi', $tahun)
            ->whereMonth('tgl_presensi', $bulan)
            ->where('status_presensi', 'hadir') 
            ->count();
    }

     public static function hitungTotalGaji(float $gajiPokok, int $jumlahHadir, float $tunjanganPokok, ): float
    {
        $hariKerjaPerBulan = 25; // Asumsi hari kerja standar
        
        // 1. Hitung Gaji Harian
        if ($hariKerjaPerBulan > 0) {
            $gajiHarian = $gajiPokok / $hariKerjaPerBulan;
        } else {
            $gajiHarian = 0;
        }

        // 2. Hitung Gaji Berdasarkan Kehadiran
        $gajiBerbasisHadir = $gajiHarian * $jumlahHadir;
        
        // 3. Hitung Total Akhir
        $totalGaji = $gajiBerbasisHadir + $tunjanganPokok;
        
        // Pastikan total gaji tidak minus
        return max(0, $totalGaji); 
    }
    
    /**
     * Hitung Jumlah Hadir, Gaji Pokok (dari Jabatan Pegawai), dan Total Gaji.
     * Digunakan untuk mengisi field Gaji Pokok, Jumlah Hadir, dan Total Gaji di form Filament.
     * * @param int|null $pegawaiId
     * @param int|null $tahun
     * @param int|null $bulan
     * @return array [jumlahHadir, gajiPokokDariJabatan]
     */
    public static function hitungTotalGajiDanHadir($pegawaiId, $tahun, $bulan): array
    {
        // 1. Cek validitas input
        if (empty($pegawaiId) || empty($tahun) || empty($bulan)) {
            // Jika ada input yang kosong, kembalikan 0 untuk semua nilai
            return [
                'jumlah_hadir' => 0, 
                'gaji_pokok' => 0.0,
            ];
        }

        // 2. Hitung Jumlah Hadir
        $jumlahHadir = self::hitungJumlahHadir($pegawaiId, $tahun, $bulan);

        // 3. Ambil Gaji Pokok dari Jabatan Pegawai
        // Asumsi: Model Pegawai memiliki relasi 'jabatan' yang memiliki kolom 'gaji' (gaji_pokok)
        $pegawai = Pegawai::with('jabatan')->find($pegawaiId);
        
        // Jika pegawai tidak ditemukan atau tidak memiliki jabatan, gaji pokok dianggap 0
        $gajiPokokDariJabatan = $pegawai?->jabatan?->gaji ?? 0.0;
        
        // 4. Hitung Total Gaji Berdasarkan Hadir (Sederhana)
        // Catatan: Ini adalah logika yang disederhanakan dari query Anda.
        // Formula: Jumlah Hadir * Gaji Pokok Harian (Diasumsikan Gaji Pokok dari Jabatan adalah gaji bulanan, 
        // dan Gaji Pokok Harian = Gaji Bulanan / 25 hari kerja)
        
        // Untuk tujuan mengisi field Gaji Pokok, kita kembalikan saja nilai Gaji Pokok dari Jabatan.
        // Perhitungan total gaji yang kompleks akan ditangani oleh updateTotalGaji di Resource.
        
        // Kita kembalikan Gaji Pokok dan Jumlah Hadir untuk diisi otomatis di form
        return [
            'jumlah_hadir' => $jumlahHadir, 
            'gaji_pokok' => (float) $gajiPokokDariJabatan,
        ];
    }
    
}
