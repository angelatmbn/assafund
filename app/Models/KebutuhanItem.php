<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KebutuhanItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'kebutuhan_id',
        'nama_barang',
        'jumlah',
        'harga_satuan',
        'total_harga',
    ];

    public function kebutuhan()
    {
        return $this->belongsTo(Kebutuhan::class);
    }
}
