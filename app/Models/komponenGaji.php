<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class komponenGaji extends Model
{
    /** @use HasFactory<\Database\Factories\KomponenGajiFactory> */
    use HasFactory;
    protected $table = 'komponen_gaji'; // Nama tabel eksplisit
    protected $guarded = [];
    protected $fillable = [
        'id_komponenG',
        'nama_komponenG',
        'nominalG',  
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id_komponenG = self::getIdKomponenG();
        });
    }

    public static function getIdKomponenG()
{
    // Ambil ID terakhir (contoh: KMP-005)
    $last = self::orderBy('id_komponenG', 'desc')->first();

    if (!$last) {
        // Jika belum ada data
        return 'KMPG-001';
    }

    // Ambil 3 digit terakhir dari ID sebelumnya
    $num = (int) substr($last->id_komponenG, -3);

    // Tambah 1 untuk ID baru
    $newNum = $num + 1;

    return 'KMPG-' . str_pad($newNum, 3, '0', STR_PAD_LEFT);
}

//relasi
    public function gaji()
    {
        return $this->belongsToMany(Gaji::class, 'gaji_komponen')
                    ->withPivot('nominal')
                    ->withTimestamps();
    }

}
