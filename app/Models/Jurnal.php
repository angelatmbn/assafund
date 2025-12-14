<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    protected $table = 'jurnal';

    protected $fillable = [
        'tgl',
        'no_referensi',
        'deskripsi',
    ];

    public function details()
    {
        return $this->hasMany(JurnalDetail::class, 'jurnal_id');
    }
}
