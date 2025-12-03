<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    // Jika nama tabel bukan jamak, tambahkan properti $table
    protected $table = 'pendaftaran';

    // Kolom yang dapat diisi (fillable)
    protected $fillable = [
        'siswa',
        'nominal',
        'jumlah_bayar',
        'kelas',
        'tanggal'
    ];

    // Kalau kolom 'tanggal' mau otomatis di-cast ke Carbon
    protected $casts = [
        'tanggal' => 'date',
    ];

    public function siswa()
{
    return $this->belongsTo(Siswa::class);  // Asumsi foreign key adalah 'siswa_id'
}

}