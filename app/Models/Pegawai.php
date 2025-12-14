<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $fillable = [
        'nip',
        'nama',
        'jabatan_id',
        'gender',
        'gaji_pokok',
    ];

    // Satu pegawai punya satu jabatan
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id', 'id');
    }

    // Generate NIP
    public static function getNipBaru()
    {
        $nip = DB::table('pegawai')->max('nip') ?? '000';
        $no  = (int) substr($nip, -3) + 1;
        return str_pad($no, 3, '0', STR_PAD_LEFT);
    }

    // app/Models/Pegawai.php
public function user()
{
    return $this->hasOne(User::class, 'pegawai_id', 'id');
}


}