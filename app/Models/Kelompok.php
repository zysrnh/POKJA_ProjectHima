<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompoks';

    protected $fillable = [
        'nama_kelompok',
        'kode_invite',
        'ketua_id',
        'judul_proposal',
        'nilai_proposal',
        'status_proposal',
        'revisi_terakhir_at',
    ];

    protected $casts = [
        'nilai_proposal' => 'integer',
        'revisi_terakhir_at' => 'datetime',
    ];

    /**
     * Mahasiswa yang menjadi ketua kelompok.
     */
    public function ketua()
    {
        return $this->belongsTo(Pendaftaran::class, 'ketua_id');
    }

    /**
     * Daftar anggota yang tergabung dalam kelompok ini (termasuk ketua).
     */
    public function members()
    {
        return $this->hasMany(KelompokMember::class, 'kelompok_id');
    }

    /**
     * Daftar undangan yang dikirim dari kelompok ini.
     */
    public function invitations()
    {
        return $this->hasMany(KelompokInvitation::class, 'kelompok_id');
    }
}

