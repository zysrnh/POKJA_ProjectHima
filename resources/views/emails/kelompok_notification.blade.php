<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectLine }} - {{ $pengaturan->nama_acara ?? 'POKJA HIMA IF 2026' }}</title>
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
                                Notifikasi Kelompok Portal Mahasiswa POKJA
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 24px;">

                            <div style="margin-bottom: 14px;">
                                <span style="display: inline-block; background-color: {{ $badgeColor }}; color: #FFFFFF; font-size: 10px; font-weight: 800; letter-spacing: 0.6px; text-transform: uppercase; padding: 4px 10px; border: 1px solid #000000;">
                                    {{ $badgeText }}
                                </span>
                            </div>

                            <h2 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 800; color: #111827;">
                                {{ $heading }}
                            </h2>

                            <p style="margin: 0 0 12px 0; font-size: 14px; line-height: 1.5; color: #111827;">
                                Halo <strong>{{ $penerima->nama }}</strong>,
                            </p>
                            <p style="margin: 0 0 20px 0; font-size: 13px; line-height: 1.6; color: #374151;">
                                {{ $messageText }}
                            </p>

                            <!-- Detail Box + CTA -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F9FAFB; border: 1px solid #D1D5DB; margin-bottom: 20px; text-align: center;">
                                <tr>
                                    <td style="padding: 20px 16px;">
                                        <div style="margin-bottom: 8px; font-size: 12px; color: #4B5563;">
                                            Kelompok: <strong style="color: #1A467C; font-size: 13px;">{{ $namaKelompok }}</strong>
                                        </div>

                                        @if (!empty($namaAktor))
                                            <div style="margin-bottom: 16px; font-size: 12px; color: #4B5563;">
                                                <strong>{{ $namaAktor }}</strong>
                                            </div>
                                        @else
                                            <div style="margin-bottom: 16px;"></div>
                                        @endif

                                        <div>
                                            <a
                                                href="{{ $ctaUrl }}"
                                                target="_blank"
                                                style="display: inline-block; background-color: #1A467C; color: #FFFFFF; font-weight: 800; font-size: 12px; text-transform: uppercase; text-decoration: none; padding: 12px 24px; border: 2px solid #000000; letter-spacing: 0.5px;"
                                            >
                                                {{ $ctaText }}
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Direct Link Fallback -->
                            <p style="margin: 0 0 6px 0; font-size: 11px; color: #6B7280; line-height: 1.4;">
                                Atau buka tautan berikut di browser Anda:
                            </p>
                            <p style="margin: 0 0 20px 0; font-size: 10px; word-break: break-all; color: #1A467C; font-family: monospace; background-color: #F3F4F6; padding: 8px; border: 1px solid #E5E7EB;">
                                {{ $ctaUrl }}
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
