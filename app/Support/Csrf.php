<?php

declare(strict_types=1);

namespace App\Support;

/**
 * CSRF Protection — generate and verify tokens for forms.
 */
class Csrf
{
    public static function token(): string
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(self::token()) . '">';
    }

    public static function verify(): bool
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        return !empty($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }

    public static function regenerate(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}
