<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pegawai extends Model
{
    protected $table = 'pegawai';

    protected $fillable = [
        'nip',
        'nama',
        'jabatan_id',
        'gender',
        'gaji_pokok',
    ];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id', 'id');
    }


    // Generate NIP otomatis dengan awalan 'PG' dan 3 digit angka terakhir, mirip pola Barang2
    public static function getNipBaru()
    {
        $sql = "SELECT IFNULL(MAX(nip), '000') as nip FROM pegawai";
        $nipPegawai = DB::select($sql);

        foreach ($nipPegawai as $nip) {
            $kd = $nip->nip;
        }
        $noawal = substr($kd, -3);
        $noakhir = $noawal + 1;
        $noakhir = '0' . str_pad($noakhir, 3, "0", STR_PAD_LEFT);
        return $noakhir;
    }
}
