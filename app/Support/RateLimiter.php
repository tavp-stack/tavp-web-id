<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Simple rate limiter — file-based, no external dependencies.
 */
class RateLimiter
{
    private string $dir;

    public function __construct(?string $dir = null)
    {
        $this->dir = $dir ?? sys_get_temp_dir() . '/rate_limit';
        if (!is_dir($this->dir)) {
            @mkdir($this->dir, 0755, true);
        }
    }

    /**
     * Check if action is allowed. Returns true if allowed, false if rate limited.
     */
    public function attempt(string $key, int $maxAttempts = 5, int $windowSeconds = 60): bool
    {
        $file = $this->dir . '/' . md5($key) . '.json';
        $now = time();

        $data = ['attempts' => 0, 'first_attempt' => $now];
        if (file_exists($file)) {
            $stored = json_decode(file_get_contents($file), true);
            if ($stored && ($now - $stored['first_attempt']) < $windowSeconds) {
                $data = $stored;
            }
        }

        if ($data['attempts'] >= $maxAttempts) {
            return false;
        }

        $data['attempts']++;
        $data['last_attempt'] = $now;
        file_put_contents($file, json_encode($data));
        return true;
    }

    /**
     * Get remaining attempts.
     */
    public function remaining(string $key, int $maxAttempts = 5, int $windowSeconds = 60): int
    {
        $file = $this->dir . '/' . md5($key) . '.json';
        $now = time();

        if (!file_exists($file)) return $maxAttempts;
        $data = json_decode(file_get_contents($file), true);
        if (!$data || ($now - $data['first_attempt']) >= $windowSeconds) return $maxAttempts;

        return max(0, $maxAttempts - $data['attempts']);
    }

    /**
     * Reset rate limit for a key.
     */
    public function reset(string $key): void
    {
        $file = $this->dir . '/' . md5($key) . '.json';
        @unlink($file);
    }
}
