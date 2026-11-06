<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SecurityMiddleware
{
    /**
     * Daftar field yang dikecualikan dari inspeksi regex ketat (seperti password).
     */
    protected array $excludedFields = [
        'password',
        'password_confirmation',
        '_token',
    ];

    /**
     * Pola serangan berbahaya (SQL Injection, XSS, Path Traversal, Command Injection).
     */
    protected array $attackPatterns = [
        // SQL Injection Signatures
        '/\bunion\s+(all\s+)?select\b/i',
        '/\b(sleep|benchmark)\s*\(\s*\d+/i',
        '/\b(information_schema|sysobjects|xp_cmdshell|load_file|into\s+outfile|into\s+dumpfile)\b/i',
        '/(\%27|\')\s*(or|and)\s*(\%27|\')?\d+(\%27|\')?\s*=\s*(\%27|\')?\d+/i',
        '/;\s*(drop|alter|truncate)\s+table\b/i',
        '/\bwaitfor\s+delay\b/i',

        // XSS & Script Injection Signatures
        '/<\s*script\b[^>]*>/i',
        '/<\s*\/\s*script\s*>/i',
        '/javascript\s*:/i',
        '/vbscript\s*:/i',
        '/data\s*:\s*text\/html/i',
        '/\bon(error|load|mouseover|focus|blur|click|dblclick|submit|keydown|keyup|animationstart|toggle)\s*=/i',
        '/<\s*(iframe|object|embed|applet|base|form)\b[^>]*>/i',

        // Path Traversal & Null Byte Signatures
        '/\.\.(\/|\\\\)/',
        '/%2e%2e(\/|%2f|%5c)/i',
        '/\x00/',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek ukuran URL / Query String berlebih (Mitigasi Buffer / URI Flood)
        $fullUri = $request->getRequestUri();
        if (strlen($fullUri) > 2048) {
            Log::warning('WAF Blocked URI Overflow', [
                'ip' => $request->ip(),
                'uri_length' => strlen($fullUri),
            ]);
            return $this->denyRequest($request, 'URL terlalu panjang atau tidak valid.');
        }

        // 2. Periksa Path Traversal & Null-Byte pada URI mentah
        $decodedUri = rawurldecode($fullUri);
        if (preg_match('/(\.\.\/|\.\.\\\\|\x00)/', $decodedUri)) {
            Log::warning('WAF Blocked Path Traversal in URI', [
                'ip' => $request->ip(),
                'uri' => $fullUri,
            ]);
            return $this->denyRequest($request, 'Akses path tidak sah terdeteksi oleh sistem keamanan.');
        }

        // 3. Inspeksi seluruh parameter input dari potensi SQLi / XSS
        $inputs = $request->all();
        if ($this->containsMaliciousPayload($inputs, $request)) {
            return $this->denyRequest($request, 'Permintaan ditolak oleh sistem keamanan karena terdeteksi karakter atau instruksi berbahaya.');
        }

        // 4. Lanjutkan request ke aplikasi
        /** @var Response $response */
        $response = $next($request);

        // 5. Pasang HTTP Security Headers (Standar OWASP)
        if (function_exists('header_remove')) {
            @header_remove('X-Powered-By');
        }

        $response->headers->remove('X-Powered-By');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(self), microphone=(), geolocation=()');

        return $response;
    }

    /**
     * Periksa secara rekursif apakah array input mengandung payload berbahaya.
     */
    protected function containsMaliciousPayload(array $inputs, Request $request): bool
    {
        foreach ($inputs as $key => $value) {
            if (in_array((string) $key, $this->excludedFields, true)) {
                continue;
            }

            // Cek nama key parameter itu sendiri
            if (is_string($key) && $this->matchesAttackPattern($key)) {
                Log::warning('WAF Blocked Malicious Input Key', [
                    'ip' => $request->ip(),
                    'url' => $request->fullUrl(),
                    'key' => $key,
                ]);
                return true;
            }

            if (is_array($value)) {
                if ($this->containsMaliciousPayload($value, $request)) {
                    return true;
                }
            } elseif (is_string($value)) {
                if ($this->matchesAttackPattern($value)) {
                    Log::warning('WAF Blocked Malicious Input Payload', [
                        'ip' => $request->ip(),
                        'url' => $request->fullUrl(),
                        'field' => $key,
                        'sample' => substr($value, 0, 150),
                        'user_agent' => $request->userAgent(),
                    ]);
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Cek kecocokan string terhadap daftar regex pola serangan.
     */
    protected function matchesAttackPattern(string $value): bool
    {
        $decoded = html_entity_decode(rawurldecode($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        foreach ($this->attackPatterns as $pattern) {
            if (preg_match($pattern, $value) || preg_match($pattern, $decoded)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Kembalikan respons blokir 403 Forbidden (JSON atau HTML View).
     */
    protected function denyRequest(Request $request, string $message): Response
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 403);
        }

        abort(403, $message);
    }
}
