<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Pendaftaran POKJA HIMA IF</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F3F4F6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1F2937;">

    <!-- Wrapper Table -->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F3F4F6; padding: 24px 12px;">
        <tr>
            <td align="center">
                
                <!-- Main Clean Card -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 520px; background-color: #FFFFFF; border: 2px solid #1A467C;">
                    
                    <!-- Clean Header Bar -->
                    <tr>
                        <td style="background-color: #1A467C; padding: 20px 24px; text-align: left;">
                            <h1 style="margin: 0; color: #FFFFFF; font-size: 17px; font-weight: 800; letter-spacing: 0.5px; text-transform: uppercase;">
                                {{ $pengaturan->nama_acara ?? 'POKJA HIMA IF 2026' }}
                            </h1>
                            <p style="margin: 3px 0 0 0; color: #BFDBFE; font-size: 11px;">
                                Himpunan Mahasiswa Informatika - Institut Digital Ekonomi LPKIA
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 24px;">
                            
                            <!-- Greeting -->
                            <p style="margin: 0 0 16px 0; font-size: 14px; line-height: 1.5; color: #111827;">
                                Halo <strong>{{ $pendaftaran->nama }}</strong>, pendaftaran Anda telah berhasil dicatat. Berikut adalah tiket dan QR Code presensi kehadiran Anda:
                            </p>

                            <!-- QR Code Section (Clean Centered Box) -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F9FAFB; border: 1px solid #E5E7EB; margin-bottom: 20px; text-align: center;">
                                <tr>
                                    <td style="padding: 24px 16px;">
                                        
                                        <!-- Inline Embedded QR Code -->
                                        <div style="display: inline-block; padding: 8px; background-color: #FFFFFF; border: 1px solid #D1D5DB;">
                                            @if (!empty($qrBinary))
                                                <img 
                                                    src="{{ $message->embedData($qrBinary, 'qrcode.png', 'image/png') }}" 
                                                    alt="QR Code {{ $pendaftaran->nim }}" 
                                                    width="180" 
                                                    height="180" 
                                                    style="display: block; border: 0;" 
                                                />
                                            @else
                                                <img 
                                                    src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&margin=4&data={{ urlencode($pendaftaran->nim) }}" 
                                                    alt="QR Code {{ $pendaftaran->nim }}" 
                                                    width="180" 
                                                    height="180" 
                                                    style="display: block; border: 0;" 
                                                />
                                            @endif
                                        </div>

                                        <p style="margin: 10px 0 0 0; font-family: monospace; font-size: 15px; font-weight: 800; color: #1A467C; letter-spacing: 1px;">
                                            {{ $pendaftaran->nim }}
                                        </p>
                                        <p style="margin: 4px 0 0 0; font-size: 11px; color: #6B7280;">
                                            Tunjukkan QR Code ini kepada panitia saat registrasi di lokasi.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Clean Information Table -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 12px; border-collapse: collapse; margin-bottom: 22px;">
                                <tr>
                                    <td style="padding: 6px 0; color: #6B7280; width: 35%;">Nama Lengkap</td>
                                    <td style="padding: 6px 0; color: #111827; font-weight: 700;">{{ $pendaftaran->nama }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; color: #6B7280;">NIM</td>
                                    <td style="padding: 6px 0; color: #111827; font-weight: 700; font-family: monospace;">{{ $pendaftaran->nim }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; color: #6B7280;">Kelas</td>
                                    <td style="padding: 6px 0; color: #111827; font-weight: 700;">{{ $pendaftaran->kelas }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; color: #6B7280;">Tanggal Acara</td>
                                    <td style="padding: 6px 0; color: #111827; font-weight: 700;">{{ $pengaturan->tanggal_acara ?? '13 Oktober 2026' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; color: #6B7280;">Waktu</td>
                                    <td style="padding: 6px 0; color: #111827; font-weight: 700;">{{ $pengaturan->jam_acara ?? '08:00 WIB - Selesai' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; color: #6B7280;">Lokasi</td>
                                    <td style="padding: 6px 0; color: #111827; font-weight: 700;">{{ $pengaturan->lokasi_acara ?? 'Ruangan 105' }}</td>
                                </tr>
                            </table>

                            <!-- Action Button: WhatsApp Group -->
                            @if (!empty($pengaturan->link_grup_wa))
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 8px;">
                                    <tr>
                                        <td>
                                            <a href="{{ $pengaturan->link_grup_wa }}" target="_blank" style="display: block; background-color: #16A34A; color: #FFFFFF; font-size: 13px; font-weight: 700; text-align: center; text-decoration: none; padding: 12px 16px; border: 1px solid #15803D;">
                                                Gabung Grup WhatsApp Peserta
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                        </td>
                    </tr>

                    <!-- Clean Simple Footer -->
                    <tr>
                        <td style="background-color: #F9FAFB; padding: 14px 24px; text-align: center; border-top: 1px solid #E5E7EB;">
                            <p style="margin: 0; color: #6B7280; font-size: 11px;">
                                &copy; 2026 HIMA IF - Institut Digital Ekonomi LPKIA
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
