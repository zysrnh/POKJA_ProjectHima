<?php

namespace App\Mail;

use App\Models\Pendaftaran;
use App\Models\Pengaturan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class TiketPendaftaranMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pendaftaran;
    public $pengaturan;
    public $qrBinary;

    /**
     * Create a new message instance.
     */
    public function __construct(Pendaftaran $pendaftaran, Pengaturan $pengaturan)
    {
        $this->pendaftaran = $pendaftaran;
        $this->pengaturan = $pengaturan;

        // Ambil binary image QR Code pendaftaran (berisi token aktivasi login mahasiswa)
        try {
            $qrData = route('mahasiswa.aktivasi', ['token' => $pendaftaran->qr_token]);
            $response = Http::timeout(5)->get('https://api.qrserver.com/v1/create-qr-code/?size=250x250&margin=4&data=' . urlencode($qrData));
            $this->qrBinary = $response->successful() ? $response->body() : null;
        } catch (\Throwable $e) {
            $this->qrBinary = null;
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'QR Code Pendaftaran & Presensi - ' . ($this->pengaturan->nama_acara ?? 'POKJA HIMA IF 2026'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tiket_pendaftaran',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
