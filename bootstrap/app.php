<?php

declare(strict_types=1);

/**
 * Bootstrap the application.
 *
 * This file is shared between the web entry point (public/index.php)
 * and CLI commands. It registers all core services.
 */

use Phalcon\Db\Adapter\Pdo\Mysql as MysqlAdapter;
use Phalcon\Db\Adapter\Pdo\Sqlite as SqliteAdapter;
use Tavp\Core\Application;
use Tavp\Core\Routing\Router;
use Tavp\Core\View\ViewFactory;

/**
 * @var Application $app
 */
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

return $app;
