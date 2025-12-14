<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatans';

    protected $fillable = [
        'nama_jabatan',
        'gaji_pokok',
    ];

    // Satu jabatan punya banyak pegawai
    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'id_jabatan', 'id');
    }
}
