<?php

declare(strict_types=1);

namespace App\Console;

/**
 * Queue worker — processes pending jobs from the queue.
 *
 * Usage: php bin/tavp queue:work [--queue=default] [--sleep=3] [--tries=3]
 *
 * This is a simplified worker. For production, consider using a supervisor
 * or systemd to keep the worker running.
 */
class QueueWorkCommand
{
    private string $storagePath;
    private bool $running = true;

    public function __construct()
    {
        $this->storagePath = base_path('storage/queue');
    }

    public function handle(array $args): void
    {
        $queue = $this->getArg($args, '--queue', 'default');
        $sleep = (int) $this->getArg($args, '--sleep', '3');
        $tries = (int) $this->getArg($args, '--tries', '3');

        echo "Queue worker started (queue: {$queue}, sleep: {$sleep}s, tries: {$tries})\n";
        echo "Press Ctrl+C to stop.\n\n";

        // Handle graceful shutdown.
        pcntl_signal(SIGTERM, function () {
            $this->running = false;
            echo "\nShutting down gracefully...\n";
        });
        pcntl_signal(SIGINT, function () {
            $this->running = false;
            echo "\nShutting down gracefully...\n";
        });

        while ($this->running) {
            $job = $this->pop($queue);

            if ($job === null) {
                sleep($sleep);
                continue;
            }

            $this->process($job, $tries);
        }

        echo "Worker stopped.\n";
    }

    private function pop(string $queue): ?array
    {
        $file = $this->queueFile($queue);

        if (!is_file($file)) {
            return null;
        }

        $jobs = json_decode((string) file_get_contents($file), true);

        if (!is_array($jobs) || empty($jobs)) {
            return null;
        }

        $job = array_shift($jobs);
        file_put_contents($file, json_encode($jobs), LOCK_EX);

        return $job;
    }

    private function process(array $job, int $maxTries): void
    {
        $jobClass = $job['job'] ?? '';
        $data = $job['data'] ?? null;
        $id = $job['id'] ?? uniqid('job_', true);
        $attempt = ($job['attempt'] ?? 0) + 1;

        echo "[{$id}] Processing {$jobClass} (attempt {$attempt}/{$maxTries})\n";

        try {
            if (!class_exists($jobClass)) {
                throw new \RuntimeException("Job class not found: {$jobClass}");
            }

            $instance = new $jobClass();
            $instance->handle($data);

            echo "[{$id}] ✓ Completed\n";
        } catch (\Throwable $e) {
            echo "[{$id}] ✗ Failed: {$e->getMessage()}\n";

            if ($attempt < $maxTries) {
                $job['attempt'] = $attempt;
                $this->retry($job);
                echo "[{$id}] Requeued (attempt {$attempt}/{$maxTries})\n";
            } else {
                $this->fail($job, $e->getMessage());
                echo "[{$id}] Moved to failed jobs\n";
            }
        }
    }

    private function retry(array $job): void
    {
        $queue = $job['queue'] ?? 'default';
        $file = $this->queueFile($queue);
        $jobs = is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: []) : [];
        $jobs[] = $job;
        file_put_contents($file, json_encode($jobs), LOCK_EX);
    }

    private function fail(array $job, string $error): void
    {
        $file = $this->storagePath . '/failed.json';
        $failed = is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: []) : [];
        $job['error'] = $error;
        $job['failed_at'] = date('c');
        $failed[] = $job;
        file_put_contents($file, json_encode($failed, JSON_PRETTY_PRINT), LOCK_EX);
    }

    private function queueFile(string $queue): string
    {
        if (!is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0755, true);
        }

        return $this->storagePath . "/{$queue}.json";
    }

    private function getArg(array $args, string $key, string $default): string
    {
        foreach ($args as $arg) {
            if (str_starts_with($arg, $key . '=')) {
                return substr($arg, strlen($key) + 1);
            }
        }

        return $default;
    }
}
