<?php

declare(strict_types=1);

namespace App\Console;

/**
 * Deploy command — syncs code to production server via rsync.
 *
 * Usage: php bin/tavp deploy [--dry-run]
 *
 * This command:
 *   1. Rsyncs the project to the remote server
 *   2. Runs composer install on the server
 *   3. Runs migrations
 *   4. Sets correct permissions
 *   5. Restarts PHP-FPM
 */
class DeployCommand
{
    private array $config;

    public function __construct()
    {
        $this->config = [
            'host' => env('DEPLOY_HOST', ''),
            'user' => env('DEPLOY_USER', ''),
            'path' => env('DEPLOY_PATH', ''),
            'php_version' => env('DEPLOY_PHP_VERSION', '8.3'),
        ];
    }

    public function handle(array $args): void
    {
        $dryRun = in_array('--dry-run', $args, true);

        if (empty($this->config['host']) || empty($this->config['user']) || empty($this->config['path'])) {
            echo "Error: Deploy configuration incomplete.\n";
            echo "Set DEPLOY_HOST, DEPLOY_USER, and DEPLOY_PATH in .env\n";
            return;
        }

        echo "═══════════════════════════════════════════\n";
        echo " TAVP Deploy" . ($dryRun ? ' (DRY RUN)' : '') . "\n";
        echo "═══════════════════════════════════════════\n\n";

        $host = "{$this->config['user']}@{$this->config['host']}";
        $path = $this->config['path'];
        $php = "php{$this->config['php_version']}";

        // 1. Rsync
        echo "[1/5] Syncing code...\n";
        $excludes = [
            '--exclude', '.env',
            '--exclude', 'vendor/',
            '--exclude', 'node_modules/',
            '--exclude', 'storage/cache/',
            '--exclude', 'storage/sessions/',
            '--exclude', 'storage/compiled/',
            '--exclude', '.git/',
            '--exclude', '.idea/',
            '--exclude', 'docs/',
        ];

        $rsyncCmd = array_merge(
            ['rsync', '-avz', '--delete'],
            $excludes,
            ['./', "{$host}:{$path}/"]
        );

        if ($dryRun) {
            $rsyncCmd[] = '--dry-run';
        }

        $this->exec($rsyncCmd);

        // 2. Composer install
        echo "\n[2/5] Installing dependencies...\n";
        if (!$dryRun) {
            $this->ssh($host, "cd {$path} && composer install --no-dev --optimize-autoloader --no-interaction 2>&1");
        } else {
            echo "  (skipped in dry run)\n";
        }

        // 3. Run migrations
        echo "\n[3/5] Running migrations...\n";
        if (!$dryRun) {
            $this->ssh($host, "cd {$path} && {$php} vendor/bin/tavp migrate 2>&1");
        } else {
            echo "  (skipped in dry run)\n";
        }

        // 4. Set permissions
        echo "\n[4/5] Setting permissions...\n";
        if (!$dryRun) {
            $this->ssh($host, "cd {$path} && chmod -R 755 public/ && chmod -R 775 storage/ 2>&1");
        } else {
            echo "  (skipped in dry run)\n";
        }

        // 5. Restart PHP-FPM
        echo "\n[5/5] Restarting PHP-FPM...\n";
        if (!$dryRun) {
            $this->ssh($host, "sudo systemctl restart php{$this->config['php_version']}-fpm 2>&1");
        } else {
            echo "  (skipped in dry run)\n";
        }

        echo "\n═══════════════════════════════════════════\n";
        echo " Deploy " . ($dryRun ? 'preview' : 'complete') . "!\n";
        echo "═══════════════════════════════════════════\n";
    }

    private function exec(array $cmd): void
    {
        $escaped = array_map('escapeshellarg', $cmd);
        $fullCmd = implode(' ', $escaped);

        echo "  > {$fullCmd}\n";
        passthru($fullCmd, $exitCode);

        if ($exitCode !== 0) {
            echo "  Warning: command exited with code {$exitCode}\n";
        }
    }

    private function ssh(string $host, string $command): void
    {
        $fullCmd = 'ssh -o ConnectTimeout=10 ' . escapeshellarg($host) . ' ' . escapeshellarg($command);

        echo "  > {$command}\n";
        passthru($fullCmd, $exitCode);

        if ($exitCode !== 0) {
            echo "  Warning: SSH command exited with code {$exitCode}\n";
        }
    }
}
