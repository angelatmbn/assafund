<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kebutuhan extends Model
{
    use HasFactory;

    protected $table = 'kebutuhan';

    protected $fillable = [
        'tanggal',
        'total_nominal',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    public function items()
    {
        return $this->hasMany(KebutuhanItem::class, 'kebutuhan_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
