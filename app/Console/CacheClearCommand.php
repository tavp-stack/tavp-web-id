<?php

declare(strict_types=1);

namespace App\Console;

/**
 * Clear application cache.
 *
 * Usage: php bin/tavp cache:clear
 */
class CacheClearCommand
{
    public function handle(array $args): void
    {
        $dirs = [
            storage_path('compiled/volt'),
            storage_path('cache'),
            storage_path('cms/cache'),
        ];

        $cleared = 0;

        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                continue;
            }

            $files = glob($dir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                    $cleared++;
                } elseif (is_dir($file)) {
                    $this->removeDir($file);
                    $cleared++;
                }
            }
        }

        echo "Cleared {$cleared} cache entries.\n";
    }

    private function removeDir(string $dir): void
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->removeDir($path) : unlink($path);
        }
        rmdir($dir);
    }
}
