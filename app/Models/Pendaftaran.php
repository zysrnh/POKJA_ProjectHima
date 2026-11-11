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
        'total_point',
    ];

    protected $casts = [
        'total_point' => 'integer',
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

    public function pointHistories()
    {
        return $this->hasMany(PointHistory::class, 'pendaftaran_id');
    }

    /**
     * Get full URL to student's uploaded avatar or null if not set.
     */
    public function avatarUrl(): ?string
    {
        $filename = basename((string) $this->foto);
        if (!empty($filename) && is_file(public_path('uploads/avatars/' . $filename))) {
            return route('avatar.serve', ['filename' => $filename]);
        }

        return null;
    }

    /**
     * Get 2-letter uppercase initials for fallback avatar display.
     */
    public function initials(): string
    {
        return strtoupper(substr(trim((string) $this->nama), 0, 2)) ?: 'MH';
    }
}
