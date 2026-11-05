<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftarans';

    protected $fillable = [
        'nama',
        'nim',
        'email',
        'kelas',
        'no_telp',
        'status_kehadiran',
    ];

    public function presensis()
    {
        return $this->hasMany(Presensi::class, 'pendaftaran_id');
    }
}
