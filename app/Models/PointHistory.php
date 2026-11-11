<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointHistory extends Model
{
    use HasFactory;

    protected $table = 'point_histories';

    protected $fillable = [
        'pendaftaran_id',
        'tipe',
        'kategori',
        'jumlah',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'jumlah' => 'integer',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }
}

