<?php

declare(strict_types=1);

// Public entry point for tavp.web.id.
// Every web request is routed through this file.

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap/app.php';

use App\AppServiceProvider;
use Tavp\Cms\CmsServiceProvider;
use Tavp\Core\Kernel;

// Register the CMS (storage driver, content types, theme).
$cms = new CmsServiceProvider();
$cms->register();
$cms->boot();
$cms->loadRoutes();

// Manually register required CMS services (in case CmsServiceProvider didn't)
// 1. ContentStore
$app->bind('Tavp\Cms\Storage\ContentStore', function () use ($app) {
    return new \Tavp\Cms\Storage\DatabaseStore(
        connection: fn () => $app->getService('db'),
    );
});

// 2. BreadManager
$app->bind('Tavp\Cms\Bread\BreadManager', function () use ($app) {
    $store = $app->getService('Tavp\Cms\Storage\ContentStore');
    $manager = new \Tavp\Cms\Bread\BreadManager($store);
    $manager->registerFromConfig((array) config('cms.content_types', []));
    return $manager;
});

// 3. TaxonomyManager
require_once __DIR__ . '/../vendor/tavp/cms/src/Taxonomy/DatabaseTaxonomyFactory.php';
$app->bind('Tavp\Cms\Taxonomy\TaxonomyManager', function () use ($app) {
    return \Tavp\Cms\Taxonomy\buildDatabaseTaxonomy($app->getService('db'));
});

// Register local modules (taxonomy, revisions, search, api, webhooks, etc.)
$appProvider = new AppServiceProvider();
$appProvider->register();

// Site routes (front-end + CMS catch-all). $router is in scope here.
require_once $app->getBasePath() . '/routes/web.php';

$kernel = new Kernel($app, $router);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

echo $kernel->handle($method, $uri);
