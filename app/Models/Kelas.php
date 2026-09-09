<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke pendaftaran yang menggunakan kelas ini
     */
    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'kelas', 'nama_kelas');
    }
}
