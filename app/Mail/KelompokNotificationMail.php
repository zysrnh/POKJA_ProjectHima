<?php

namespace App\Mail;

use App\Models\Pendaftaran;
use App\Models\Pengaturan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KelompokNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Pendaftaran $penerima;
    public ?Pengaturan $pengaturan;
    public string $subjectLine;
    public string $badgeText;
    public string $badgeColor;
    public string $heading;
    public string $messageText;
    public string $namaKelompok;
    public ?string $namaAktor;
    public string $ctaUrl;
    public string $ctaText;

    /**
     * Buat instance email notifikasi aktivitas kelompok mahasiswa.
     */
    public function __construct(
        Pendaftaran $penerima,
        ?Pengaturan $pengaturan,
        string $subjectLine,
        string $badgeText,
        string $badgeColor,
        string $heading,
        string $messageText,
        string $namaKelompok,
        ?string $namaAktor,
        string $ctaUrl,
        string $ctaText = 'Buka Menu Kelompok'
    ) {
        $this->penerima = $penerima;
        $this->pengaturan = $pengaturan;
        $this->subjectLine = $subjectLine;
        $this->badgeText = $badgeText;
        $this->badgeColor = $badgeColor;
        $this->heading = $heading;
        $this->messageText = $messageText;
        $this->namaKelompok = $namaKelompok;
        $this->namaAktor = $namaAktor;
        $this->ctaUrl = $ctaUrl;
        $this->ctaText = $ctaText;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $namaAcara = $this->pengaturan->nama_acara ?? 'POKJA HIMA IF 2026';
        $fromEmail = config('mail.from.address') ?? 'registration@pokja.hmif-lpkia.my.id';
        $fromName = config('mail.from.name') ?? 'POKJA HIMA IF';

        return new Envelope(
            from: new Address($fromEmail, $fromName),
            replyTo: [new Address($fromEmail, $fromName)],
            subject: $this->subjectLine . ' - ' . $namaAcara,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.kelompok_notification',
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

