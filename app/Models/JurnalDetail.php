<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalDetail extends Model
{
    protected $table = 'jurnal_detail';

    protected $fillable = [
        'jurnal_id',
        'no_akun',
        'deskripsi',
        'debit',
        'credit',
    ];

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class, 'jurnal_id');
    }

    public function akun()
{
    return $this->belongsTo(Coa::class, 'no_akun', 'no_akun');
}

}
