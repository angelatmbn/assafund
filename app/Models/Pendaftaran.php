<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KomponenBiayaDaftar;
use App\Models\Siswa;

class Pendaftaran extends Model
{
    protected $table = 'pendaftaran';

    protected $fillable = [
        'siswa',
        'komponen_biaya', // isi: id_komponen
        'nominal',
        'jumlah_bayar',
        'kelas',
        'tanggal',
    ];

    public function KomponenBiayaDaftar()
    {
        return $this->belongsTo(
            KomponenBiayaDaftar::class,
            'komponen_biaya',   // FK di pendaftaran
            'id_komponen',      // kolom unik di master
        );
    }

        public function siswa()
    {
        // kolom di tabel pendaftaran: siswa_id (bukan 'siswa')
        return $this->belongsTo(Siswa::class);
    }
}