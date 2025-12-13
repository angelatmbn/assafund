<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Pegawai;

class presensi extends Model
{
    /** @use HasFactory<\Database\Factories\PresensiFactory> */
    use HasFactory;
    protected $table = 'presensi';  // Nama tabel eksplisit
    protected $guarded = [];
    protected $fillable = ['id','id_pegawai', 'tgl_transaksi', 'waktu_masuk', 'waktu_keluar', 'status_presensi'];
    
    public static function getIdPresensi()
        {
            // query kode perusahaan
            $sql = "SELECT IFNULL(MAX(id_presensi), 'P-0000') as id_presensi 
                    FROM presensi";
            $idpresensi = DB::select($sql);

            // cacah hasilnya
            foreach ($idpresensi as $idprs) {
                $kd = $idprs->id_presensi;
            }
            // Mengambil substring tiga digit akhir dari string PR-000
            $noawal = substr($kd,-4);
            $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
            $noakhir = 'PRS-'.str_pad($noakhir,4,"0",STR_PAD_LEFT); //menyambung dengan string PRS-0001
            return $noakhir;

        }

    // Relationship ke pegawai (pastikan model Pegawai ada)
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id');  // Eksplisit: foreign_key, owner_key
    }
    
}
