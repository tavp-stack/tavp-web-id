<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Security Headers — set HTTP headers for protection.
 */
class SecurityHeaders
{
    public static function send(): void
    {
        header('X-Frame-Options: DENY');
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}
