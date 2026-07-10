<?php

declare(strict_types=1);

/**
 * Cache configuration for tavp.web.id.
 */
return [
    'default' => env('CACHE_DRIVER', 'file'),

    'stores' => [
        'file' => [
            'driver' => 'file',
            'path' => storage_path('cache'),
            'ttl' => (int) env('CACHE_TTL', 3600),
        ],

        'redis' => [
            'driver' => 'redis',
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'port' => (int) env('REDIS_PORT', 6379),
            'password' => env('REDIS_PASSWORD', ''),
            'database' => (int) env('REDIS_CACHE_DB', 0),
            'prefix' => 'tavp:cache:',
            'ttl' => (int) env('CACHE_TTL', 3600),
        ],

        'apcu' => [
            'driver' => 'apcu',
            'prefix' => 'tavp:',
            'ttl' => (int) env('CACHE_TTL', 3600),
        ],
    ],
];
