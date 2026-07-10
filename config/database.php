<?php

declare(strict_types=1);

/**
 * Database configuration for tavp.web.id.
 *
 * Uses MariaDB via Phalcon's C-extension PDO adapter.
 * The adapter is bound as app('db') in the entry point.
 */
return [
    'default' => env('DB_CONNECTION', 'mariadb'),

    'connections' => [
        'mariadb' => [
            'adapter' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => (int) env('DB_PORT', 3306),
            'dbname' => env('DB_DATABASE', 'tavp'),
            'username' => env('DB_USERNAME', 'tavp'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'persistent' => false,
        ],

        'sqlite' => [
            'adapter' => 'sqlite',
            'dbname' => env('DB_DATABASE', base_path('storage/database.sqlite')),
        ],
    ],

    'migrations' => [
        'table' => 'migrations',
        'path' => base_path('database/migrations'),
    ],
];
