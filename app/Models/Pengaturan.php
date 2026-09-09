<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'pengaturan';

    protected $fillable = [
        'nama_acara',
        'tanggal_acara',
        'jam_acara',
        'lokasi_acara',
        'deskripsi_acara',
        'cp_nama',
        'cp_nomor',
        'link_grup_wa',
    ];
}
