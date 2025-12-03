<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranSPP extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_s_p_p_';

    protected $fillable = [
        'nis',
        'bulan',
        'tahun',
        'tanggal_bayar',
        'biaya_pokok',
        'jumlah_bayar',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }
}
