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

// Register local modules (taxonomy, revisions, search, api, webhooks, etc.)
$appProvider = new AppServiceProvider();
$appProvider->register();

// Site routes (front-end + CMS catch-all). $router is in scope here.
require_once $app->getBasePath() . '/routes/web.php';

$kernel = new Kernel($app, $router);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

echo $kernel->handle($method, $uri);
