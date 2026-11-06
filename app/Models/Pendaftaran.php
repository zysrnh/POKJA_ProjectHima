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
        'password',
        'foto',
        'qr_token',
        'kelas',
        'no_telp',
        'status_kehadiran',
    ];

    protected $hidden = [
        'password',
    ];

    protected static function booted()
    {
        static::creating(function ($pendaftaran) {
            if (empty($pendaftaran->qr_token)) {
                $pendaftaran->qr_token = 'POKJA-' . strtoupper(bin2hex(random_bytes(10)));
            }
        });
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class, 'pendaftaran_id');
    }
}
