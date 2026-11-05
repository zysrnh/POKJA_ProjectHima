<?php

namespace App\Mail;

use App\Models\Pendaftaran;
use App\Models\Pengaturan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TiketPendaftaranMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pendaftaran;
    public $pengaturan;
    public $qrCodeUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Pendaftaran $pendaftaran, Pengaturan $pengaturan)
    {
        $this->pendaftaran = $pendaftaran;
        $this->pengaturan = $pengaturan;
        
        // QR Code API yang kompatibel dengan seluruh Email Client (Gmail, Apple Mail, Outlook)
        $qrData = $pendaftaran->nim;
        $this->qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&margin=8&data=' . urlencode($qrData);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tiket & QR Code Pendaftaran ' . ($this->pengaturan->nama_acara ?? 'POKJA HIMA IF 2026') . ' - ' . $this->pendaftaran->nama,
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

