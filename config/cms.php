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

        // RBAC-lite: map an allowed e-mail to a role. Unknown e-mails default
        // to "editor".
        'roles' => [
            'admin@tavp.web.id' => 'admin',
            'editor@tavp.web.id' => 'editor',
        ],
        'permissions' => [
            'admin' => ['content.*', 'taxonomy.*', 'media.*', 'menu.*', 'settings.*', 'webhook.*', 'api.*', 'bread.*', 'users.*'],
            'editor' => ['content.*', 'taxonomy.*', 'media.*'],
        ],
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

                // Feature bento grid
                ['name' => 'feature_1_title', 'type' => 'text'],
                ['name' => 'feature_1_desc', 'type' => 'textarea'],
                ['name' => 'feature_2_title', 'type' => 'text'],
                ['name' => 'feature_2_desc', 'type' => 'textarea'],
                ['name' => 'feature_3_title', 'type' => 'text'],
                ['name' => 'feature_3_desc', 'type' => 'textarea'],
                ['name' => 'feature_4_title', 'type' => 'text'],
                ['name' => 'feature_4_desc', 'type' => 'textarea'],

                // "Runs Where You Do" section
                ['name' => 'platforms_title', 'type' => 'text'],
                ['name' => 'platforms_subtitle', 'type' => 'textarea'],

                // Stats
                ['name' => 'stat_1_label', 'type' => 'text'],
                ['name' => 'stat_1_value', 'type' => 'text'],
                ['name' => 'stat_1_desc', 'type' => 'text'],
                ['name' => 'stat_2_label', 'type' => 'text'],
                ['name' => 'stat_2_value', 'type' => 'text'],
                ['name' => 'stat_2_desc', 'type' => 'text'],
                ['name' => 'stat_3_label', 'type' => 'text'],
                ['name' => 'stat_3_value', 'type' => 'text'],
                ['name' => 'stat_3_desc', 'type' => 'text'],

                // Final CTA
                ['name' => 'cta_title', 'type' => 'text'],
                ['name' => 'cta_highlight', 'type' => 'text'],
                ['name' => 'cta_final_1_text', 'type' => 'text'],
                ['name' => 'cta_final_2_text', 'type' => 'text'],

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
                 ['name' => 'author', 'type' => 'text'],
                 ['name' => 'status', 'type' => 'select', 'options' => ['draft', 'published'], 'default' => 'draft'],
                ['name' => 'published_at', 'type' => 'datetime'],
            ],
        ],
        'contact' => [
            'label' => 'Contact Page',
            'singular' => 'Contact',
            'route' => '/contact',
            'fields' => [
                ['name' => 'page_title', 'type' => 'text', 'required' => true],
                ['name' => 'intro', 'type' => 'textarea'],
                ['name' => 'github_title', 'type' => 'text'],
                ['name' => 'github_desc', 'type' => 'text'],
                ['name' => 'github_url', 'type' => 'text'],
                ['name' => 'email_title', 'type' => 'text'],
                ['name' => 'email_desc', 'type' => 'text'],
                ['name' => 'email_address', 'type' => 'text'],
                ['name' => 'form_button', 'type' => 'text'],
                ['name' => 'slug', 'type' => 'slug', 'from' => 'page_title'],
            ],
        ],
        'get_started' => [
            'label' => 'Get Started Page',
            'singular' => 'Get Started',
            'route' => '/get-started',
            'fields' => [
                ['name' => 'badge', 'type' => 'text'],
                ['name' => 'page_title', 'type' => 'text', 'required' => true],
                ['name' => 'intro', 'type' => 'textarea'],
                ['name' => 'step1_title', 'type' => 'text'],
                ['name' => 'step1_desc', 'type' => 'textarea'],
                ['name' => 'step2_title', 'type' => 'text'],
                ['name' => 'step2_desc', 'type' => 'textarea'],
                ['name' => 'step3_title', 'type' => 'text'],
                ['name' => 'step3_desc', 'type' => 'textarea'],
                ['name' => 'hello_title', 'type' => 'text'],
                ['name' => 'hello_desc', 'type' => 'textarea'],
                ['name' => 'tips_title', 'type' => 'text'],
                ['name' => 'tips_desc', 'type' => 'textarea'],
                ['name' => 'help_title', 'type' => 'text'],
                ['name' => 'help_desc', 'type' => 'textarea'],
                ['name' => 'help_button', 'type' => 'text'],
                ['name' => 'help_url', 'type' => 'text'],
                ['name' => 'slug', 'type' => 'slug', 'from' => 'page_title'],
            ],
        ],
        'performance' => [
            'label' => 'Performance Page',
            'singular' => 'Performance',
            'route' => '/performance',
            'fields' => [
                ['name' => 'hero_title', 'type' => 'text', 'required' => true],
                ['name' => 'hero_intro', 'type' => 'textarea'],
                ['name' => 'cta1_label', 'type' => 'text'],
                ['name' => 'cta1_url', 'type' => 'text'],
                ['name' => 'cta2_label', 'type' => 'text'],
                ['name' => 'cta2_url', 'type' => 'text'],
                ['name' => 'lowend_title', 'type' => 'text'],
                ['name' => 'lowend_desc', 'type' => 'textarea'],
                ['name' => 'why_title', 'type' => 'text'],
                ['name' => 'arch_badge', 'type' => 'text'],
                ['name' => 'arch_title', 'type' => 'text'],
                ['name' => 'arch_intro', 'type' => 'textarea'],
                ['name' => 'slug', 'type' => 'slug', 'from' => 'hero_title'],
            ],
        ],
        'documentation' => [
            'label' => 'Documentation Page',
            'singular' => 'Documentation',
            'route' => '/documentation',
            'fields' => [
                ['name' => 'hero_title', 'type' => 'text', 'required' => true],
                ['name' => 'intro', 'type' => 'textarea'],
                ['name' => 'core_heading', 'type' => 'text'],
                ['name' => 'philosophy_heading', 'type' => 'text'],
                ['name' => 'runtimes_badge', 'type' => 'text'],
                ['name' => 'runtimes_title', 'type' => 'text'],
                ['name' => 'runtimes_desc', 'type' => 'textarea'],
                ['name' => 'license_title', 'type' => 'text'],
                ['name' => 'license_desc', 'type' => 'textarea'],
                ['name' => 'license_btn1_label', 'type' => 'text'],
                ['name' => 'license_btn1_url', 'type' => 'text'],
                ['name' => 'license_btn2_label', 'type' => 'text'],
                ['name' => 'license_btn2_url', 'type' => 'text'],
                ['name' => 'slug', 'type' => 'slug', 'from' => 'hero_title'],
            ],
        ],
    ],
];
