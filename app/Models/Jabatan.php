<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatans'; // Nama tabel eksplisit

    protected $guarded = [];

    protected $fillable = ['id_jabatan', 'nama_jabatan', 'gaji_pokok'];

    // Generate NIP otomatis dengan awalan 'PG' dan 3 digit angka terakhir, mirip pola Barang2
    public static function getIdJabatanBaru()
    {
        $sql = "SELECT IFNULL(MAX(id_jabatan), '000') as id_jabatan FROM jabatan";
        $idJabatan = DB::select($sql);

        foreach ($idJabatan as $id_jabatan) {
            $kd = $id_jabatan->id_jabatan;
        }
        $noawal = substr($kd, -3);
        $noakhir = $noawal + 1;
        $noakhir = '0' . str_pad($noakhir, 3, "0", STR_PAD_LEFT);
        return $noakhir;
    }
}
