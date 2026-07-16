<?php

declare(strict_types=1);

/**
 * Bootstrap the application.
 *
 * This file is shared between the web entry point (public/index.php)
 * and CLI commands. It registers all core services.
 */

date_default_timezone_set('Asia/Jakarta');

use Tavp\Core\Application;
use Tavp\Core\Database\DatabaseManager;
use Tavp\Core\Routing\Router;
use Tavp\Core\View\ViewFactory;

/**
 * @var Application $app
 */
$app = new Application(dirname(__DIR__));
$app->bootstrap();

$router = new Router();

// --- Database adapter (Phalcon C-extension via DatabaseManager) -------------
$app->bind('db', function () {
    $manager = new DatabaseManager((array) config('database', []));
    return $manager->getAdapter();
});

// Shared services.
$app->bind('router', fn () => $router);
$app->bind('config', fn () => $app->getConfig());
$app->bind('view', fn () => new ViewFactory(
    $app->getBasePath() . '/themes/' . config('cms.theme.active', 'tavp'),
    storage_path('compiled/volt')
));

// --- Phalcon DI Setup (required for Phalcon models) ------------------------
$di = new \Phalcon\Di\Di();
$di->setShared('db', function () use ($app) {
    return $app->getService('db');
});
$di->setShared('modelsManager', function () {
    return new \Phalcon\Mvc\Model\Manager();
});
$di->setShared('modelsMetadata', function () {
    return new \Phalcon\Mvc\Model\Metadata\Memory();
});
\Phalcon\Di\Di::setDefault($di);

return $app;
