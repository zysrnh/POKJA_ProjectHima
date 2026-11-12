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
        'foto',
        'nilai_proposal',
        'status_proposal',
        'revisi_terakhir_at',
    ];

    protected $casts = [
        'nilai_proposal' => 'integer',
        'revisi_terakhir_at' => 'datetime',
    ];

    /**
     * Ambil URL foto profil kelompok jika tersedia di disk.
     */
    public function fotoUrl(): ?string
    {
        $filename = basename((string) $this->foto);
        if (!empty($filename) && is_file(public_path('uploads/kelompok/' . $filename))) {
            return route('kelompok.foto.serve', ['filename' => $filename]);
        }

        return null;
    }

    /**
     * Ambil 2 huruf inisial kelompok untuk avatar fallback.
     */
    public function initials(): string
    {
        $cleanWords = preg_split('/\s+/', trim((string) $this->nama_kelompok)) ?: [];
        if (count($cleanWords) >= 2) {
            return strtoupper(substr($cleanWords[0], 0, 1) . substr($cleanWords[1], 0, 1));
        }

        return strtoupper(substr(trim((string) $this->nama_kelompok), 0, 2)) ?: 'KL';
    }

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

