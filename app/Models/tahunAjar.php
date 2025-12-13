<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tahunAjar extends Model
{
    /** @use HasFactory<\Database\Factories\TahunAjarFactory> */
    use HasFactory;
        protected $table = 'tahun_ajar'; // Nama tabel eksplisit
        protected $guarded = [];
        protected $fillable = [
            'tahun',
            'semester',
            'is_active',
            'biaya_pendaftaran',  
        ];                                                                      
}
