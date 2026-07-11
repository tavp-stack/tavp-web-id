<?php

declare(strict_types=1);

// Public entry point for tavp.web.id.
// Every web request is routed through this file.

require_once __DIR__ . '/../vendor/autoload.php';

use App\AppServiceProvider;
use Phalcon\Db\Adapter\Pdo\Mysql as MysqlAdapter;
use Phalcon\Db\Adapter\Pdo\Sqlite as SqliteAdapter;
use Tavp\Cms\CmsServiceProvider;
use Tavp\Core\Application;
use Tavp\Core\Kernel;
use Tavp\Core\Routing\Router;
use Tavp\Core\View\ViewFactory;

$app = new Application(dirname(__DIR__));
$app->bootstrap();

$router = new Router();

// --- Database adapter (Phalcon C-extension) --------------------------------
$app->bind('db', function () {
    $name = config('database.default', 'mariadb');
    $conn = config("database.connections.{$name}");

    return match ($conn['adapter'] ?? 'mysql') {
        'mysql' => new MysqlAdapter([
            'host' => $conn['host'] ?? '127.0.0.1',
            'port' => $conn['port'] ?? 3306,
            'dbname' => $conn['dbname'] ?? 'tavp',
            'username' => $conn['username'] ?? 'tavp',
            'password' => $conn['password'] ?? '',
            'charset' => $conn['charset'] ?? 'utf8mb4',
            'options' => [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ],
        ]),
        'sqlite' => new SqliteAdapter([
            'dbname' => $conn['dbname'] ?? base_path('storage/database.sqlite'),
        ]),
        default => throw new \RuntimeException("Unsupported DB adapter: {$conn['adapter']}"),
    };
});

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

// Register local modules (taxonomy, revisions, search, api, webhooks, etc.)
$appProvider = new AppServiceProvider();
$appProvider->register();

// Site routes (front-end + CMS catch-all). $router is in scope here.
require_once $app->getBasePath() . '/routes/web.php';

$kernel = new Kernel($app, $router);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

echo $kernel->handle($method, $uri);
