<?php

declare(strict_types=1);

use Tavp\Analytics\AnalyticsManager;
use Tavp\Cms\Admin\AdminModule;
use Tavp\Cms\Api\ApiModule;
use Tavp\Cms\Bread\BreadManager;
use Tavp\Cms\Seo\SitemapController;

/**
 * tavp.web.id routes.
 *
 * @var \Tavp\Core\Routing\Router $router
 */

// --- CMS admin panel -----------------------------------------------------
AdminModule::routes($router);

// --- Analytics -----------------------------------------------------------
AnalyticsManager::register($router);

// --- Headless REST API ---------------------------------------------------
if (config('cms.api.enabled', true)) {
    ApiModule::routes($router);
}

// --- SEO: sitemap.xml ----------------------------------------------------
$router->get('/sitemap.xml', function () {
    $sitemap = new SitemapController(app()->getService(BreadManager::class));
    return $sitemap();
});

// --- Analytics Dashboard -------------------------------------------------
$router->get('/analytics', function () {
    return view('analytics::dashboard.index');
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

// Contact page with dynamic captcha
$router->get('/contact', function () {
    session_start();
    $a = random_int(1, 10);
    $b = random_int(1, 10);
    $_SESSION['captcha_answer'] = $a + $b;
    $hash = hash('sha256', (string) ($a + $b));

    return view('contact', [
        'captcha_question' => "What is {$a} + {$b}?",
        'captcha_hash' => $hash,
    ]);
});

// Contact form handler
$router->post('/contact', function () {
    session_start();
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    $captcha = $_POST['captcha'] ?? '';
    $captchaHash = $_POST['captcha_hash'] ?? '';
    $website = $_POST['website'] ?? '';

    // Honeypot check
    if ($website !== '') {
        return view('contact', ['success' => false, 'error' => 'Spam detected.']);
    }

    // Captcha check
    $expectedHash = hash('sha256', (string) $_SESSION['captcha_answer'] ?? '');
    if ($captchaHash !== $expectedHash || (string) $captcha !== (string) $_SESSION['captcha_answer']) {
        $a = random_int(1, 10);
        $b = random_int(1, 10);
        $_SESSION['captcha_answer'] = $a + $b;
        $hash = hash('sha256', (string) ($a + $b));

        return view('contact', [
            'success' => false,
            'error' => 'Invalid captcha. Please try again.',
            'captcha_question' => "What is {$a} + {$b}?",
            'captcha_hash' => $hash,
        ]);
    }

    // Here you would send the email or save to database
    // For now, just show success message

    $a = random_int(1, 10);
    $b = random_int(1, 10);
    $_SESSION['captcha_answer'] = $a + $b;
    $hash = hash('sha256', (string) ($a + $b));

    return view('contact', [
        'success' => true,
        'message' => "Thank you {$name}! We'll get back to you soon.",
        'captcha_question' => "What is {$a} + {$b}?",
        'captcha_hash' => $hash,
    ]);
});

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
        http_response_code(404);
        return view('404');
    }

    return view('page', ['content' => $page]);
});
