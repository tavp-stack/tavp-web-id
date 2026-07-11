<?php

declare(strict_types=1);

namespace App\Console;

use Tavp\Cms\Publishing\PublishScheduler;

/**
 * Schedule runner — executes scheduled tasks.
 *
 * Usage: php bin/tavp schedule:run
 *
 * Run via cron: * * * * * cd /path/to/project && php bin/tavp schedule:run
 */
class ScheduleRunCommand
{
    public function handle(array $args): void
    {
        echo "Running scheduled tasks...\n\n";

        $ran = 0;

        // --- Publish scheduled content ---
        if (config('cms.publishing.enabled', false)) {
            $ran += $this->runPublishScheduler();
        }

        // --- Custom tasks can be added here ---

        if ($ran === 0) {
            echo "No scheduled tasks to run.\n";
        } else {
            echo "\nCompleted {$ran} scheduled task(s).\n";
        }
    }

    private function runPublishScheduler(): int
    {
        try {
            $scheduler = new PublishScheduler(
                app()->getService(\Tavp\Cms\Bread\BreadManager::class)
            );

            $published = $scheduler->publishDue();

            foreach ($published as $item) {
                echo "  ✓ Published: [{$item['type']}] {$item['title']} (ID: {$item['id']})\n";
            }

            return count($published);
        } catch (\Throwable $e) {
            echo "  ✗ Publish scheduler error: {$e->getMessage()}\n";
            return 0;
        }
    }
}
