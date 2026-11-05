<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Pendaftaran POKJA HIMA IF</title>
</head>
<body style="margin: 0; padding: 0; background-color: #E8F2FA; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #111827;">

    <!-- Wrapper Table -->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #E8F2FA; padding: 30px 10px;">
        <tr>
            <td align="center">
                
                <!-- Main Card (Neubrutalism Style) -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #FFFFFF; border: 3px solid #1A467C; box-shadow: 6px 6px 0px #1A467C;">
                    
                    <!-- Header Banner -->
                    <tr>
                        <td style="background-color: #1A467C; padding: 22px 24px; border-bottom: 3px solid #0F2A4A; text-align: left;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td>
                                        <span style="display: inline-block; background-color: #CA2C2A; color: #FFFFFF; font-size: 10px; font-weight: 800; padding: 3px 8px; text-transform: uppercase; letter-spacing: 1px; border: 1px solid #FFFFFF; margin-bottom: 6px;">
                                            TIKET RESMI PENDAFTARAN
                                        </span>
                                        <h1 style="margin: 0; color: #FFFFFF; font-size: 18px; font-weight: 900; letter-spacing: 0.5px; text-transform: uppercase;">
                                            {{ $pengaturan->nama_acara ?? 'POKJA HIMA IF 2026' }}
                                        </h1>
                                        <p style="margin: 4px 0 0 0; color: #93C5FD; font-size: 11px; font-weight: 600;">
                                            Himpunan Mahasiswa Informatika - Institut Digital Ekonomi LPKIA
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 26px 24px;">
                            
                            <!-- Greeting -->
                            <p style="margin: 0 0 16px 0; font-size: 14px; line-height: 1.5; color: #1F2937;">
                                Halo <strong>{{ $pendaftaran->nama }}</strong>, pendaftaran Anda untuk acara <strong>{{ $pengaturan->nama_acara ?? 'POKJA HIMA IF' }}</strong> telah berhasil dicatat!
                            </p>

                            <!-- QR Code Section (Ticket Pass) -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F8FBFE; border: 2px dashed #1A467C; margin-bottom: 22px; text-align: center;">
                                <tr>
                                    <td style="padding: 20px 16px;">
                                        <span style="display: inline-block; background-color: #1A467C; color: #FFFFFF; font-size: 10px; font-weight: 800; padding: 3px 10px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px;">
                                            QR CODE PRESENSI KEHADIRAN
                                        </span>
                                        
                                        <!-- QR Code Image -->
                                        <div style="margin: 8px auto 12px auto; display: inline-block; padding: 8px; background-color: #FFFFFF; border: 2px solid #000000; box-shadow: 4px 4px 0px #000000;">
                                            <img src="{{ $qrCodeUrl }}" alt="QR Code Presensi {{ $pendaftaran->nim }}" width="190" height="190" style="display: block; border: 0;" />
                                        </div>

                                        <p style="margin: 0; font-family: 'Courier New', Courier, monospace; font-size: 16px; font-weight: 900; color: #1A467C; letter-spacing: 1.5px;">
                                            NIM: {{ $pendaftaran->nim }}
                                        </p>
                                        <p style="margin: 4px 0 0 0; font-size: 11px; color: #4B5563; font-weight: 600;">
                                            Tunjukkan QR Code ini kepada panitia saat registrasi ulang di lokasi acara.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Data Peserta Box -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border: 2px solid #1A467C; margin-bottom: 20px; font-size: 12px; border-collapse: collapse;">
                                <tr style="background-color: #EEF6FC; border-bottom: 2px solid #1A467C;">
                                    <td colspan="2" style="padding: 8px 12px; font-weight: 900; color: #1A467C; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">
                                        Informasi Pendaftar
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #E5E7EB;">
                                    <td style="padding: 8px 12px; font-weight: 700; color: #4B5563; width: 35%;">Nama Lengkap</td>
                                    <td style="padding: 8px 12px; font-weight: 800; color: #111827;">{{ $pendaftaran->nama }}</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #E5E7EB;">
                                    <td style="padding: 8px 12px; font-weight: 700; color: #4B5563;">NIM</td>
                                    <td style="padding: 8px 12px; font-weight: 800; color: #1A467C; font-family: monospace;">{{ $pendaftaran->nim }}</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #E5E7EB;">
                                    <td style="padding: 8px 12px; font-weight: 700; color: #4B5563;">Email Kampus</td>
                                    <td style="padding: 8px 12px; font-weight: 700; color: #111827;">{{ $pendaftaran->email }}</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #E5E7EB;">
                                    <td style="padding: 8px 12px; font-weight: 700; color: #4B5563;">Kelas</td>
                                    <td style="padding: 8px 12px; font-weight: 800; color: #1A467C; font-family: monospace;">{{ $pendaftaran->kelas }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 12px; font-weight: 700; color: #4B5563;">No. WhatsApp</td>
                                    <td style="padding: 8px 12px; font-weight: 800; color: #111827; font-family: monospace;">{{ $pendaftaran->no_telp }}</td>
                                </tr>
                            </table>

                            <!-- Jadwal & Lokasi Acara -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border: 2px solid #CA2C2A; margin-bottom: 22px; font-size: 12px; border-collapse: collapse;">
                                <tr style="background-color: #FEF2F2; border-bottom: 2px solid #CA2C2A;">
                                    <td colspan="2" style="padding: 8px 12px; font-weight: 900; color: #901C1A; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">
                                        Jadwal & Lokasi Pelaksanaan
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #FEE2E2;">
                                    <td style="padding: 8px 12px; font-weight: 700; color: #4B5563; width: 35%;">Tanggal</td>
                                    <td style="padding: 8px 12px; font-weight: 800; color: #111827;">{{ $pengaturan->tanggal_acara ?? '13 Oktober 2026' }}</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #FEE2E2;">
                                    <td style="padding: 8px 12px; font-weight: 700; color: #4B5563;">Waktu</td>
                                    <td style="padding: 8px 12px; font-weight: 800; color: #111827;">{{ $pengaturan->jam_acara ?? '08:00 WIB - Selesai' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 12px; font-weight: 700; color: #4B5563;">Lokasi</td>
                                    <td style="padding: 8px 12px; font-weight: 800; color: #CA2C2A;">{{ $pengaturan->lokasi_acara ?? 'Ruangan 105' }}</td>
                                </tr>
                            </table>

                            <!-- Call to Action: WhatsApp Group Button -->
                            @if (!empty($pengaturan->link_grup_wa))
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 10px;">
                                    <tr>
                                        <td align="center">
                                            <a href="{{ $pengaturan->link_grup_wa }}" target="_blank" style="display: block; background-color: #15803D; color: #FFFFFF; font-size: 13px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px; padding: 13px 20px; text-decoration: none; border: 2px solid #0F2A4A; box-shadow: 4px 4px 0px #0F2A4A; text-align: center;">
                                                GABUNG GRUP WHATSAPP PESERTA
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #0F2A4A; padding: 16px 20px; text-align: center; border-top: 2px solid #000000;">
                            <p style="margin: 0; color: #FFFFFF; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">
                                HIMA IF - INFORMATIKA LPKIA
                            </p>
                            <p style="margin: 4px 0 0 0; color: #93C5FD; font-size: 10px;">
                                Email ini dibuat secara otomatis oleh sistem pendaftaran resmi POKJA HIMA IF.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
