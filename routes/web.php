<?php

declare(strict_types=1);

use Tavp\Cms\Bread\BreadManager;

/**
 * tavp.web.id routes.
 *
 * The home page and blog index are explicit; everything else falls through
 * to the CMS, which resolves the path to a published page/post via the
 * active storage driver and renders it with the active theme.
 *
 * @var \Tavp\Core\Routing\Router $router
 */

// Home — bespoke landing template
$router->get('/', fn () => view('home'));

// Blog index
$router->get('/blog', function () {
    $bread = app()->getService(BreadManager::class);
    $posts = $bread->browse('post', ['status' => 'published']);

    return view('blog', ['posts' => $posts]);
});

// Blog post
$router->get('/blog/{slug}', function (string $slug) {
    $bread = app()->getService(BreadManager::class);
    $post = $bread->readBySlug('post', $slug);

    if ($post === null || ($post['status'] ?? 'draft') !== 'published') {
        return response()->notFound();
    }

    return view('post', ['content' => $post]);
});

// Page catch-all (keep last)
$router->get('/{slug}', function (string $slug) {
    $bread = app()->getService(BreadManager::class);
    $page = $bread->readBySlug('page', $slug);

    if ($page === null || ($page['status'] ?? 'draft') !== 'published') {
        return response()->notFound();
    }

    return view('page', ['content' => $page]);
});
