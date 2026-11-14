<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuesionerPertanyaan extends Model
{
    use HasFactory;

    protected $table = 'kuesioner_pertanyaans';

    protected $fillable = [
        'pertanyaan',
        'tipe',
        'opsi',
        'wajib',
        'urutan',
        'aktif',
        'pertemuan',
    ];

    protected $casts = [
        'opsi' => 'array',
        'wajib' => 'boolean',
        'urutan' => 'integer',
        'aktif' => 'boolean',
        'pertemuan' => 'integer',
    ];

    public const TIPE_LABELS = [
        'teks' => 'Isian Singkat',
        'paragraf' => 'Paragraf / Uraian',
        'pilihan' => 'Pilihan Ganda',
    ];

    /**
     * Daftar jawaban mahasiswa untuk pertanyaan ini.
     */
    public function jawabans()
    {
        return $this->hasMany(KuesionerJawaban::class, 'pertanyaan_id');
    }

    /**
     * Scope pertanyaan yang berlaku untuk pertemuan tertentu (atau semua pertemuan bila null).
     */
    public function scopeForPertemuan($query, int $pertemuan)
    {
        return $query->where(function ($q) use ($pertemuan) {
            $q->whereNull('pertemuan')->orWhere('pertemuan', $pertemuan);
        });
    }

    /**
     * Label tipe pertanyaan yang mudah dibaca.
     */
    public function tipeLabel(): string
    {
        return self::TIPE_LABELS[$this->tipe] ?? ucfirst((string) $this->tipe);
    }
}

