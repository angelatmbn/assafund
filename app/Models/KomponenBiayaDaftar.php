<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomponenBiayaDaftar extends Model
{
    use HasFactory;

    protected $table = 'komponen_biaya_daftar';

    protected $fillable = [
        'id_komponen',
        'nama_komponen',
        'nominal',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id_komponen = self::getIdKomponen();
        });
    }

    public static function getIdKomponen()
    {
        $last = self::orderBy('id_komponen', 'desc')->first();

        if (! $last) {
            return 'KMP-001';
        }

        $num = (int) substr($last->id_komponen, -3);

        return 'KMP-' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
    }

        public function Pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class,
        );
    }
}