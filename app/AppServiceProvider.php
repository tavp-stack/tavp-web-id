<?php

declare(strict_types=1);

namespace App;

use Tavp\Cms\Api\ApiModule;
use Tavp\Cms\Cache\CachedContentStore;
use Tavp\Cms\Content\ValidationException;
use Tavp\Cms\Media\MediaLibrary;
use Tavp\Cms\Menu\MenuBuilder;
use Tavp\Cms\Publishing\PublishScheduler;
use Tavp\Cms\Revisions\RevisionStore;
use Tavp\Cms\Search\SearchEngine;
use Tavp\Cms\Seo\SitemapController;
use Tavp\Cms\Settings\Settings;
use Tavp\Cms\Storage\ContentStore;
use Tavp\Cms\Taxonomy\DatabaseTaxonomyFactory;
use Tavp\Cms\Taxonomy\TaxonomyManager;
use Tavp\Cms\Webhooks\DatabaseWebhookFactory;
use Tavp\Cms\Webhooks\WebhookManager;
use Tavp\Core\Module\ServiceProvider;

/**
 * Local service provider — extends the CMS and wires all modules.
 *
 * This is the single place where every TAVP CMS module is registered.
 * The vendor CmsServiceProvider handles the core (storage, bread, theme);
 * this provider handles the optional modules.
 */
class AppServiceProvider implements ServiceProvider
{
    public function register(): void
    {
        $app = app();

        // --- Core CMS (already registered by vendor CmsServiceProvider) ---
        // We wrap the ContentStore with CachedContentStore when caching is enabled.
        if (config('cms.cache.enabled', true)) {
            $app->bind(ContentStore::class, function () {
                $store = $this->makeStore();
                return new CachedContentStore(
                    inner: $store,
                    cachePath: (string) config('cms.cache.path', storage_path('cms/cache')),
                    ttl: (int) config('cms.cache.ttl', 300),
                );
            });
        }

        // --- Taxonomy ---
        $app->bind(TaxonomyManager::class, function () {
            $db = app('db');
            return DatabaseTaxonomyFactory\buildDatabaseTaxonomy($db);
        });

        // --- Webhooks ---
        $app->bind(WebhookManager::class, function () {
            $db = app('db');
            return DatabaseWebhookFactory\buildDatabaseWebhooks(
                $db,
                (int) config('cms.webhooks.timeout', 5),
            );
        });

        // --- Revisions ---
        $app->bind(RevisionStore::class, fn () => new RevisionStore(
            path: (string) config('cms.revisions.path', storage_path('cms/revisions')),
            limit: (int) config('cms.revisions.limit', 50),
        ));

        // --- Search ---
        $app->bind(SearchEngine::class, function () {
            $bread = $app->getService(\Tavp\Cms\Bread\BreadManager::class);
            $fields = config('cms.search.fields', ['title', 'body', 'excerpt', 'slug']);
            return new SearchEngine($bread, $fields);
        });

        // --- Settings ---
        $app->bind(Settings::class, function () {
            $db = app('db');
            return new Settings(
                loader: function () use ($db) {
                    $rows = $db->query('SELECT `key`, `value` FROM settings');
                    $settings = [];
                    foreach ($rows as $row) {
                        $settings[$row['key']] = $row['value'];
                    }
                    return $settings;
                },
                writer: function (string $key, mixed $value) use ($db) {
                    $exists = $db->query(
                        'SELECT COUNT(*) AS cnt FROM settings WHERE `key` = :key',
                        ['key' => $key]
                    );
                    if (($exists[0]['cnt'] ?? 0) > 0) {
                        $db->update('settings', ['value' => (string) $value], ['`key`' => $key]);
                    } else {
                        $db->insert('settings', [
                            '`key`' => $key,
                            'value' => (string) $value,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                },
            );
        });

        // --- Media Library ---
        $app->bind(MediaLibrary::class, function () {
            $db = app('db');
            return new MediaLibrary(
                config: (array) config('cms.media', []),
                persist: function (array $data) use ($db) {
                    $db->insert('media', array_merge($data, [
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]));
                    $id = $db->lastInsertId();
                    $rows = $db->query('SELECT * FROM media WHERE id = :id LIMIT 1', ['id' => $id]);
                    return $rows[0] ?? $data;
                },
            );
        });

        // --- Menu Builder ---
        $app->bind(MenuBuilder::class, fn () => new MenuBuilder());

        // --- Publish Scheduler ---
        $app->bind(PublishScheduler::class, function () {
            $bread = $app->getService(\Tavp\Cms\Bread\BreadManager::class);
            return new PublishScheduler($bread);
        });
    }

    public function boot(): void
    {
        // Nothing to boot — modules are lazy-loaded via the container.
    }

    private function makeStore(): ContentStore
    {
        $driver = (string) config('cms.storage', 'database');

        return match ($driver) {
            'flatfile' => new \Tavp\Cms\Storage\FlatFileStore(
                basePath: (string) config('cms.drivers.flatfile.path', base_path('content')),
            ),
            default => new \Tavp\Cms\Storage\DatabaseStore(
                connection: fn () => app('db'),
            ),
        };
    }
}
