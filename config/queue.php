<?php

declare(strict_types=1);

/**
 * Queue configuration for tavp.web.id.
 */
return [
    'default' => env('QUEUE_CONNECTION', 'database'),

    'connections' => [
        'database' => [
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => 'default',
            'retry_after' => 90,
        ],

        'redis' => [
            'driver' => 'redis',
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'port' => (int) env('REDIS_PORT', 6379),
            'password' => env('REDIS_PASSWORD', ''),
            'database' => (int) env('REDIS_QUEUE_DB', 1),
            'queue' => 'tavp:jobs',
            'retry_after' => 90,
        ],
    ],

    'batching' => [
        'database' => env('DB_DATABASE', 'tavp'),
        'table' => 'job_batches',
    ],

    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'),
        'database' => env('DB_DATABASE', 'tavp'),
        'table' => 'failed_jobs',
    ],
];
