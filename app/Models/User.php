<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Email tunggal yang memiliki hak akses penuh sebagai Super Admin.
     */
    public const SUPER_ADMIN_EMAIL = 'naoo@hmif.my.id';

    /**
     * Mengecek apakah akun ini adalah Super Admin tunggal.
     */
    public function isSuperAdmin(): bool
    {
        return strtolower(trim((string) $this->email)) === self::SUPER_ADMIN_EMAIL;
    }

    /**
     * Mengambil URL foto profil admin dari direktori public/uploads/avatars jika ada.
     */
    public function avatarUrl(): ?string
    {
        if (!$this->id) {
            return null;
        }

        $dir = public_path('uploads/avatars');
        if (!is_dir($dir)) {
            return null;
        }

        $files = glob($dir . DIRECTORY_SEPARATOR . 'admin_' . (int) $this->id . '_*.*');
        if (!empty($files) && is_file($files[0])) {
            return asset('uploads/avatars/' . basename($files[0]));
        }

        return null;
    }

    /**
     * Mengambil 2 huruf inisial nama admin untuk fallback avatar.
     */
    public function initials(): string
    {
        $clean = trim((string) $this->name);
        return $clean !== '' ? strtoupper(substr($clean, 0, 2)) : 'AD';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
