<?php

declare(strict_types=1);

namespace App\Console;

/**
 * Local migrate command — executes SQL via PDO directly.
 */
class MigrateCommand
{
    private string $migrationsPath;
    private string $storagePath;

    public function __construct()
    {
        $this->migrationsPath = base_path('database/migrations');
        $this->storagePath = storage_path('migrations.json');
    }

    public function handle(array $args): void
    {
        $mode = 'up';
        $step = null;
        foreach ($args as $arg) {
            if ($arg === '--rollback') $mode = 'rollback';
            if ($arg === '--fresh') $mode = 'fresh';
            if ($arg === '--status') $mode = 'status';
            if (str_starts_with($arg, '--step=')) $step = (int) substr($arg, 7);
        }
        match ($mode) {
            'up' => $this->migrate($step),
            'rollback' => $this->rollback($step),
            'fresh' => $this->fresh(),
            'status' => $this->status(),
        };
    }

    private function getPdo(): \PDO
    {
        $default = config('database.default', 'mariadb');
        $conn = config("database.connections.{$default}");
        $dsn = "mysql:host={$conn['host']};port={$conn['port']};dbname={$conn['dbname']};charset=utf8mb4";
        return new \PDO($dsn, $conn['username'], $conn['password'], [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ]);
    }

    private function getAdapter()
    {
        $pdo = $this->getPdo();
        $adapter = new \Phalcon\Db\Adapter\Pdo\Mysql([
            'host' => '', 'dbname' => '', 'username' => '', 'password' => '',
        ]);
        $ref = new \ReflectionProperty(\Phalcon\Db\Adapter\Pdo\AbstractPdo::class, '_pdo');
        $ref->setAccessible(true);
        $ref->setValue($adapter, $pdo);
        $ref2 = new \ReflectionProperty(\Phalcon\Db\Adapter\Pdo\AbstractPdo::class, '_connected');
        $ref2->setAccessible(true);
        $ref2->setValue($adapter, true);
        return $adapter;
    }

    private function migrate(?int $step = null): void
    {
        $ran = $this->getRan();
        $pending = $this->getPendingMigrations($ran);
        if (empty($pending)) { echo "Nothing to migrate.\n"; return; }
        $count = 0;
        foreach ($pending as $file) {
            if ($step !== null && $count >= $step) break;
            $this->runMigration($file, 'up');
            $this->logMigration($file);
            $count++;
        }
        echo "Migrated {$count} migration(s).\n";
    }

    private function rollback(?int $step = null): void
    {
        $ran = $this->getRan();
        $toRollback = array_reverse($ran);
        if (empty($toRollback)) { echo "Nothing to rollback.\n"; return; }
        $count = 0;
        foreach ($toRollback as $file) {
            if ($step !== null && $count >= $step) break;
            $this->runMigration($file, 'down');
            $this->forgetMigration($file);
            $count++;
        }
        echo "Rolled back {$count} migration(s).\n";
    }

    private function fresh(): void
    {
        echo "Dropping all tables...\n";
        $ran = $this->getRan();
        foreach (array_reverse($ran) as $file) { $this->runMigration($file, 'down'); }
        $this->saveRan([]);
        echo "All migrations rolled back.\n";
        $this->migrate();
    }

    private function status(): void
    {
        $ran = $this->getRan();
        $pending = $this->getPendingMigrations($ran);
        echo "Migration Status:\n";
        echo str_repeat('-', 60) . "\n";
        echo sprintf("  %-45s %s\n", 'Migration', 'Status');
        echo str_repeat('-', 60) . "\n";
        foreach ($this->getAllMigrationFiles() as $file) {
            $name = $this->getMigrationName($file);
            $status = in_array($file, $ran, true) ? '✓ Ran' : '○ Pending';
            echo sprintf("  %-45s %s\n", $name, $status);
        }
        echo str_repeat('-', 60) . "\n";
        $pendingCount = count($pending);
        echo $pendingCount > 0 ? "\n  {$pendingCount} pending migration(s).\n" : "\n  All migrations are up to date.\n";
    }

    private function runMigration(string $file, string $direction): void
    {
        $path = is_file($file) ? $file : $this->migrationsPath . '/' . $file;
        if (!is_file($path)) { echo "  Migration file not found: {$file}\n"; return; }

        $migration = require $path;
        if (!($migration instanceof \Tavp\Core\Database\Migrations\Migration)) {
            echo "  Invalid migration: {$file}\n"; return;
        }

        $name = $this->getMigrationName($file);
        $verb = $direction === 'up' ? 'Migrating' : 'Rolling back';
        echo "  {$verb}: {$name}\n";

        try {
            // Create a proper adapter with connection
            $default = config('database.default', 'mariadb');
            $conn = config("database.connections.{$default}");
            $adapter = new \Phalcon\Db\Adapter\Pdo\Mysql([
                'host' => $conn['host'],
                'port' => $conn['port'],
                'dbname' => $conn['dbname'],
                'username' => $conn['username'],
                'password' => $conn['password'],
            ]);

            if ($direction === 'up') {
                $migration->runUp($adapter);
            } else {
                $migration->runDown($adapter);
            }
            echo "    ✓ Done\n";
        } catch (\Throwable $e) {
            echo "    ✗ Error: {$e->getMessage()}\n";
        }
    }

    private function getAllMigrationFiles(): array
    {
        if (!is_dir($this->migrationsPath)) return [];
        $files = glob($this->migrationsPath . '/*_*.php');
        sort($files);
        return $files;
    }

    private function getPendingMigrations(array $ran): array
    {
        return array_filter($this->getAllMigrationFiles(), fn ($f) => !in_array($f, $ran, true));
    }

    private function getMigrationName(string $file): string { return pathinfo($file, PATHINFO_FILENAME); }
    private function getRan(): array
    {
        if (!is_file($this->storagePath)) return [];
        $data = json_decode(file_get_contents($this->storagePath), true);
        return is_array($data) ? $data : [];
    }
    private function logMigration(string $file): void { $ran = $this->getRan(); $ran[] = $file; $this->saveRan($ran); }
    private function forgetMigration(string $file): void { $ran = array_values(array_diff($this->getRan(), [$file])); $this->saveRan($ran); }
    private function saveRan(array $ran): void { $dir = dirname($this->storagePath); if (!is_dir($dir)) mkdir($dir, 0755, true); file_put_contents($this->storagePath, json_encode($ran, JSON_PRETTY_PRINT)); }
}
