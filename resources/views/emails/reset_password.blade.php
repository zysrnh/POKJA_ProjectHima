<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Reset Password - {{ $pengaturan->nama_acara ?? 'POKJA HIMA IF 2026' }}</title>
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
                                Halo <strong>{{ $mahasiswa->nama }}</strong>,
                            </p>
                            <p style="margin: 0 0 20px 0; font-size: 13px; line-height: 1.5; color: #374151;">
                                Kami menerima permintaan untuk mereset kata sandi akun Portal Mahasiswa POKJA Anda. Silakan klik tombol di bawah ini untuk membuat password baru:
                            </p>

                            <!-- Call to Action Box -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F9FAFB; border: 1px solid #E5E7EB; margin-bottom: 20px; text-align: center;">
                                <tr>
                                    <td style="padding: 24px 16px;">
                                        
                                        <!-- Student Identity Info -->
                                        <div style="margin-bottom: 16px; font-size: 12px; color: #4B5563;">
                                            <span>NIM: <strong style="font-family: monospace; color: #1A467C; font-size: 13px;">{{ $mahasiswa->nim }}</strong></span>
                                            <span style="margin: 0 6px;">•</span>
                                            <span>Kelas: <strong>{{ $mahasiswa->kelas }}</strong></span>
                                        </div>

                                        <!-- Reset Button -->
                                        <div>
                                            <a 
                                                href="{{ $resetUrl }}" 
                                                target="_blank" 
                                                style="display: inline-block; background-color: #1A467C; color: #FFFFFF; font-weight: 800; font-size: 13px; text-transform: uppercase; text-decoration: none; padding: 13px 26px; border: 2px solid #000000; letter-spacing: 0.5px;"
                                            >
                                                Atur Ulang Password
                                            </a>
                                        </div>

                                        <p style="margin: 14px 0 0 0; font-size: 11px; color: #6B7280; line-height: 1.4;">
                                            Tautan ini berlaku selama <strong>60 menit</strong>. Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini dan password Anda akan tetap aman.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Direct Link Alternative -->
                            <p style="margin: 16px 0 6px 0; font-size: 11px; color: #6B7280; line-height: 1.4;">
                                Jika tombol di atas tidak berfungsi, Anda juga dapat menyalin tautan berikut ke browser:
                            </p>
                            <p style="margin: 0 0 20px 0; font-size: 10px; word-break: break-all; color: #1A467C; font-family: monospace; background-color: #F3F4F6; padding: 8px; border: 1px solid #E5E7EB;">
                                {{ $resetUrl }}
                            </p>

                            <!-- Footer note -->
                            <div style="border-top: 1px solid #E5E7EB; padding-top: 16px; text-align: center; color: #9CA3AF; font-size: 11px;">
                                &copy; {{ date('Y') }} {{ $pengaturan->nama_acara ?? 'POKJA HIMA IF' }}. Pesan ini dikirim secara otomatis oleh sistem.
                            </div>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
