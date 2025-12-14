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
        'siswa',          // berisi NIS atau id siswa
        'nominal',
        'komponen_biaya', // berisi id_komponen seperti KMP-001
        'Kelas',
        'tanggal',
    ];

    public function siswaRef()
    {
        // kolom pendaftaran.siswa menyimpan ID siswa (1,2,3..)
        return $this->belongsTo(Siswa::class, 'siswa', 'id');
    }

    public function komponenRef()
    {
        // pendaftaran.komponen_biaya menyimpan id_komponen (KMP-001),
        // hubungkan ke komponen_biaya_daftar.id_komponen
        return $this->belongsTo(KomponenBiayaDaftar::class, 'komponen_biaya', 'id_komponen');
    }
}
