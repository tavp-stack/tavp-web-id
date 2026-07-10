<?php

declare(strict_types=1);

// Public entry point for tavp.web.id.
// Every web request is routed through this file.

require_once __DIR__ . '/../vendor/autoload.php';

use Tavp\Cms\CmsServiceProvider;
use Tavp\Core\Application;
use Tavp\Core\Kernel;
use Tavp\Core\Routing\Router;
use Tavp\Core\View\ViewFactory;

$app = new Application(dirname(__DIR__));
$app->bootstrap();

$router = new Router();

// Shared services.
$app->bind('router', fn () => $router);
$app->bind('config', fn () => $app->getConfig());
$app->bind('view', fn () => new ViewFactory(
    $app->getBasePath() . '/themes/' . config('cms.theme.active', 'tavp'),
    storage_path('compiled/volt')
));

// Register the CMS (storage driver, content types, theme).
$cms = new CmsServiceProvider();
$cms->register();
$cms->boot();

// Site routes (front-end + CMS catch-all). $router is in scope here.
require_once $app->getBasePath() . '/routes/web.php';

$kernel = new Kernel($app, $router);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

echo $kernel->handle($method, $uri);
