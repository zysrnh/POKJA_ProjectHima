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
    ];

    protected $casts = [
        'opsi' => 'array',
        'wajib' => 'boolean',
        'urutan' => 'integer',
        'aktif' => 'boolean',
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
     * Label tipe pertanyaan yang mudah dibaca.
     */
    public function tipeLabel(): string
    {
        return self::TIPE_LABELS[$this->tipe] ?? ucfirst((string) $this->tipe);
    }
}
