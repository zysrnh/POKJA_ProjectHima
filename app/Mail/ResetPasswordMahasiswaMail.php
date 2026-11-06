<?php

namespace App\Mail;

use App\Models\Pendaftaran;
use App\Models\Pengaturan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMahasiswaMail extends Mailable
{
    use Queueable, SerializesModels;

    public Pendaftaran $mahasiswa;
    public ?Pengaturan $pengaturan;
    public string $resetUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Pendaftaran $mahasiswa, ?Pengaturan $pengaturan, string $resetUrl)
    {
        $this->mahasiswa = $mahasiswa;
        $this->pengaturan = $pengaturan;
        $this->resetUrl = $resetUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $namaAcara = $this->pengaturan->nama_acara ?? 'POKJA HIMA IF 2026';

        return new Envelope(
            subject: 'Permintaan Reset Password - ' . $namaAcara,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reset_password',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
