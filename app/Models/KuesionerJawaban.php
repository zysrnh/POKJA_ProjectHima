<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuesionerJawaban extends Model
{
    use HasFactory;

    protected $table = 'kuesioner_jawabans';

    protected $fillable = [
        'pendaftaran_id',
        'pertanyaan_id',
        'jawaban',
    ];

    /**
     * Mahasiswa (pendaftar) yang mengisi jawaban.
     */
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    /**
     * Pertanyaan kuesioner yang dijawab.
     */
    public function pertanyaan()
    {
        return $this->belongsTo(KuesionerPertanyaan::class, 'pertanyaan_id');
    }
}

