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

    'cache' => [
        'enabled' => true,
        'driver' => 'file',
        'path' => storage_path('cms/cache'),
        'ttl' => 300,
    ],

    'taxonomy' => [
        'enabled' => true,
        'types' => ['category', 'tag'],
        'hierarchical' => ['category'],
    ],

    'revisions' => [
        'enabled' => true,
        'limit' => 50,
        'path' => storage_path('cms/revisions'),
    ],

    'search' => [
        'enabled' => true,
        'fields' => ['title', 'body', 'excerpt', 'slug'],
    ],

    'api' => [
        'enabled' => true,
        'prefix' => 'api/cms',
        'tokens' => array_filter(array_map('trim', explode(',', (string) env('CMS_API_TOKENS', '')))),
        'tokens_file' => storage_path('cms/api_tokens.json'),
        'per_page' => 15,
        'max_per_page' => 100,
    ],

    'webhooks' => [
        'enabled' => false,
        'timeout' => 5,
        'events' => ['content.created', 'content.updated', 'content.deleted'],
    ],

    'seo' => [
        'enabled' => true,
        'sitemap_path' => '/sitemap.xml',
        'default_title_suffix' => '',
    ],

    'publishing' => [
        'enabled' => false,
        'sleep_until_field' => 'published_at',
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
