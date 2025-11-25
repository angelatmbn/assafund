<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pegawai extends Model
{
    use HasFactory;
    protected $table = 'pegawai';  // Nama tabel eksplisit
    protected $guarded = [];
    protected $fillable = ['nip', 'nama', 'jabatan_id', 'gender'];
    // Relationship ke Jabatan (pastikan model Jabatan ada)
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id', 'id');  // Eksplisit: foreign_key, owner_key
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
