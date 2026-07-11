<?php

declare(strict_types=1);

use Tavp\Cms\Admin\AdminModule;
use Tavp\Cms\Api\ApiModule;
use Tavp\Cms\Bread\BreadManager;
use Tavp\Cms\Seo\SitemapController;

/**
 * tavp.web.id routes.
 *
 * Route closures receive the matched path parameters as a single array.
 * The CMS admin panel registers its own routes under /admin.
 *
 * @var \Tavp\Core\Routing\Router $router
 */

// --- CMS admin panel -----------------------------------------------------
AdminModule::routes($router);

// --- Headless REST API ---------------------------------------------------
if (config('cms.api.enabled', true)) {
    ApiModule::routes($router);
}

// --- SEO: sitemap.xml ----------------------------------------------------
$router->get('/sitemap.xml', function () {
    $sitemap = new SitemapController(app()->getService(BreadManager::class));
    return $sitemap();
});

// --- Front-end -----------------------------------------------------------

// Home — content-driven landing template
$router->get('/', function () {
    $home = app()->getService(BreadManager::class)->readBySlug('home', 'home');

    return view('home', ['content' => $home ?? []]);
});

// Static marketing pages (bespoke templates)
$router->get('/get-started', fn () => view('get-started'));
$router->get('/performance', fn () => view('performance'));
$router->get('/documentation', fn () => view('documentation'));
$router->get('/contact', fn () => view('contact'));

// Blog index
$router->get('/blog', function () {
    $posts = app()->getService(BreadManager::class)->browse('post', ['status' => 'published']);

    return view('blog', ['posts' => $posts]);
});

// Blog post
$router->get('/blog/{slug}', function (array $params) {
    $post = app()->getService(BreadManager::class)->readBySlug('post', $params['slug'] ?? '');

    if ($post === null || ($post['status'] ?? 'draft') !== 'published') {
        return response('404 — Not found', 404);
    }

    return view('post', ['content' => $post]);
});

// Page catch-all (keep last)
$router->get('/{slug}', function (array $params) {
    $page = app()->getService(BreadManager::class)->readBySlug('page', $params['slug'] ?? '');

    if ($page === null || ($page['status'] ?? 'draft') !== 'published') {
        return response('404 — Not found', 404);
    }

    return view('page', ['content' => $page]);
});
