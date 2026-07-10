<?php

declare(strict_types=1);

/**
 * TAVP CMS configuration for tavp.web.id.
 *
 * The marketing site uses the flat-file driver: content is Markdown + YAML
 * under /content, git-friendly and fast, no database required.
 */
return [
    'storage' => env('CMS_STORAGE', 'flatfile'),

    'drivers' => [
        'database' => [
            'connection' => env('CMS_DB_CONNECTION', 'default'),
        ],
        'flatfile' => [
            'path' => base_path('content'),
            'format' => 'markdown',
        ],
    ],

    'admin' => [
        'route_prefix' => env('CMS_ADMIN_PREFIX', 'admin'),
        'brand' => 'TAVP',
        'auth_guard' => 'tavpid',
        'otp_ttl_minutes' => (int) env('CMS_OTP_TTL', 10),
        // Only these e-mails may sign in to the admin (comma-separated in .env).
        'emails' => array_filter(array_map('trim', explode(',', (string) env('CMS_ADMIN_EMAILS', '')))),
    ],

    'mail' => [
        'driver' => env('MAIL_DRIVER', 'smtp'),
        'host' => env('MAIL_HOST', '127.0.0.1'),
        'port' => (int) env('MAIL_PORT', 1025),
        'username' => env('MAIL_USERNAME', ''),
        'password' => env('MAIL_PASSWORD', ''),
        'from' => env('MAIL_FROM', 'noreply@tavp.web.id'),
    ],

    'media' => [
        'disk' => 'public',
        'path' => 'uploads',
        'max_size' => 10 * 1024 * 1024,
        'allowed' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
    ],

    'theme' => [
        'active' => env('CMS_THEME', 'tavp'),
        'path' => base_path('themes'),
    ],

    'content_types' => [
        'home' => [
            'label' => 'Home Page',
            'singular' => 'Home',
            'route' => '/',
            'fields' => [
                ['name' => 'hero_badge', 'type' => 'text'],
                ['name' => 'hero_title', 'type' => 'text', 'required' => true],
                ['name' => 'hero_subtitle', 'type' => 'textarea'],
                ['name' => 'cta_primary', 'type' => 'text'],
                ['name' => 'cta_secondary', 'type' => 'text'],
                ['name' => 'slug', 'type' => 'slug', 'from' => 'hero_title'],
            ],
        ],
        'page' => [
            'label' => 'Pages',
            'singular' => 'Page',
            'route' => '/{slug}',
            'fields' => [
                ['name' => 'title', 'type' => 'text', 'required' => true],
                ['name' => 'slug', 'type' => 'slug', 'from' => 'title'],
                ['name' => 'body', 'type' => 'richtext'],
                ['name' => 'status', 'type' => 'select', 'options' => ['draft', 'published'], 'default' => 'draft'],
            ],
        ],
        'post' => [
            'label' => 'Posts',
            'singular' => 'Post',
            'route' => '/blog/{slug}',
            'fields' => [
                ['name' => 'title', 'type' => 'text', 'required' => true],
                ['name' => 'slug', 'type' => 'slug', 'from' => 'title'],
                ['name' => 'excerpt', 'type' => 'textarea'],
                ['name' => 'body', 'type' => 'richtext'],
                ['name' => 'status', 'type' => 'select', 'options' => ['draft', 'published'], 'default' => 'draft'],
                ['name' => 'published_at', 'type' => 'datetime'],
            ],
        ],
    ],
];
