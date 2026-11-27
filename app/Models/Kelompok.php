<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

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
     * Generate kode undangan unik dengan prefix PKJ-.
     */
    public static function generateUniqueInviteCode(): string
    {
        do {
            $code = 'PKJ-' . strtoupper(Str::random(6));
        } while (self::where('kode_invite', $code)->exists());

        return $code;
    }

    /**
     * Hapus file foto kelompok dari disk secara aman (pencegahan path traversal via basename).
     */
    public static function deleteFotoFile(?string $fotoFilename): void
    {
        if (empty($fotoFilename)) {
            return;
        }

        $oldPath = public_path('uploads/kelompok/' . basename($fotoFilename));
        if (is_file($oldPath)) {
            @unlink($oldPath);
        }
    }

    /**
     * Validasi binary gambar asli (Anti-Webshell) & simpan foto profil kelompok.
     */
    public static function storeValidatedFoto(UploadedFile $file, self $kelompok): ?string
    {
        $imageInfo = @getimagesize($file->getRealPath());
        $allowedMimeMap = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        if ($imageInfo === false || !isset($imageInfo['mime']) || !array_key_exists($imageInfo['mime'], $allowedMimeMap)) {
            return null;
        }

        $safeExtension = $allowedMimeMap[$imageInfo['mime']];
        $filename = 'kelompok_' . $kelompok->id . '_' . Str::random(12) . '_' . time() . '.' . $safeExtension;
        $destinationPath = public_path('uploads/kelompok');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        self::deleteFotoFile($kelompok->foto);
        $file->move($destinationPath, $filename);

        return $filename;
    }

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

