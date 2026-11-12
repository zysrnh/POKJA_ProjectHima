<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokInvitation extends Model
{
    use HasFactory;

    protected $table = 'kelompok_invitations';

    protected $fillable = [
        'kelompok_id',
        'pendaftaran_id',
        'invited_by',
        'status',
        'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id');
    }

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function inviter()
    {
        return $this->belongsTo(Pendaftaran::class, 'invited_by');
    }
}
