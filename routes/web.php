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

// --- SEO: sitemap.xml, robots.txt, feed -----------------------------------
$router->get('/sitemap.xml', function () {
    $sitemap = new SitemapController(app()->getService(BreadManager::class));
    return $sitemap();
});

$router->get('/robots.txt', function () {
    $adminPrefix = config('cms.admin.route_prefix', 'admin');
    $siteUrl = env('APP_URL', 'https://tavp.web.id');
    $lines = [
        'User-agent: *',
        'Allow: /',
        'Disallow: /' . $adminPrefix,
        'Disallow: /api',
        '',
        'Sitemap: ' . $siteUrl . '/sitemap.xml',
    ];
    return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain']);
});

$router->get('/feed', function () {
    $bread = app()->getService(BreadManager::class);
    $posts = $bread->browse('post', ['status' => 'published']);
    $siteName = config('general.site_name', 'TAVP Stack');
    $siteUrl = env('APP_URL', 'https://tavp.web.id');
    
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">' . "\n";
    $xml .= '<channel>' . "\n";
    $xml .= '  <title>' . htmlspecialchars($siteName) . ' Blog</title>' . "\n";
    $xml .= '  <link>' . $siteUrl . '</link>' . "\n";
    $xml .= '  <description>Latest posts from ' . htmlspecialchars($siteName) . '</description>' . "\n";
    $xml .= '  <atom:link href="' . $siteUrl . '/feed" rel="self" type="application/rss+xml"/>' . "\n";
    
    foreach (array_slice($posts, 0, 20) as $post) {
        $pubDate = strtotime($post['published_at'] ?? $post['created_at'] ?? 'now') ?: time();
        $xml .= '  <item>' . "\n";
        $xml .= '    <title>' . htmlspecialchars($post['title'] ?? '') . '</title>' . "\n";
        $xml .= '    <link>' . $siteUrl . '/blog/' . htmlspecialchars($post['slug'] ?? '') . '</link>' . "\n";
        $xml .= '    <description>' . htmlspecialchars($post['excerpt'] ?? '') . '</description>' . "\n";
        $xml .= '    <pubDate>' . date(DATE_RSS, $pubDate) . '</pubDate>' . "\n";
        $xml .= '    <guid>' . $siteUrl . '/blog/' . htmlspecialchars($post['slug'] ?? '') . '</guid>' . "\n";
        $xml .= '  </item>' . "\n";
    }
    
    $xml .= '</channel>' . "\n";
    $xml .= '</rss>';
    
    return response($xml, 200, ['Content-Type' => 'application/rss+xml']);
});

// --- Analytics Dashboard -------------------------------------------------
$router->get('/analytics', function () {
    return view('analytics::dashboard.index');
});

// --- Front-end -----------------------------------------------------------

// Home — content-driven landing template
$router->get('/', function () {
    $bread = app()->getService(BreadManager::class);
    $home = $bread->readBySlug('home', 'home');
    if (!$home) {
        // Fallback: get first home record regardless of slug
        $records = $bread->browse('home');
        $home = $records[0] ?? null;
    }

    return view('home', ['content' => $home ?? []]);
});

// Marketing pages (design templates, editable text via CMS)
$router->get('/get-started', function () {
    $records = app()->getService(BreadManager::class)->browse('get_started');
    return view('get-started', ['content' => $records[0] ?? []]);
});
$router->get('/performance', function () {
    $records = app()->getService(BreadManager::class)->browse('performance');
    return view('performance', ['content' => $records[0] ?? []]);
});
$router->get('/documentation', function () {
    $records = app()->getService(BreadManager::class)->browse('documentation');
    return view('documentation', ['content' => $records[0] ?? []]);
});

// Contact page with dynamic captcha
$router->get('/contact', function () {
    session_start();
    $a = random_int(1, 10);
    $b = random_int(1, 10);
    $_SESSION['captcha_answer'] = $a + $b;
    $_SESSION['captcha_question'] = "What is {$a} + {$b}?";
    $hash = hash('sha256', (string) ($a + $b));

    $records = app()->getService(BreadManager::class)->browse('contact');

    return view('contact', [
        'content' => $records[0] ?? [],
        'captcha_question' => "What is {$a} + {$b}?",
        'captcha_hash' => $hash,
    ]);
});

// Contact form handler
$router->post('/contact', function () {
    session_start();
    $contactRecords = app()->getService(BreadManager::class)->browse('contact');
    $contactContent = $contactRecords[0] ?? [];

    // Sanitize input
    $name = htmlspecialchars(trim((string) ($_POST['name'] ?? '')), ENT_QUOTES, 'UTF-8');
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $subject = htmlspecialchars(trim((string) ($_POST['subject'] ?? '')), ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars(trim((string) ($_POST['message'] ?? '')), ENT_QUOTES, 'UTF-8');
    $captcha = trim((string) ($_POST['captcha'] ?? ''));
    $captchaHash = trim((string) ($_POST['captcha_hash'] ?? ''));
    $website = trim((string) ($_POST['website'] ?? ''));

    // Validate required fields
    if ($name === '' || $email === '' || $message === '') {
        return view('contact', [
            'content' => $contactContent,
            'success' => false,
            'error' => 'Name, email, and message are required.',
            'captcha_question' => $_SESSION['captcha_question'] ?? '',
            'captcha_hash' => $captchaHash,
        ]);
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return view('contact', [
            'content' => $contactContent,
            'success' => false,
            'error' => 'Please enter a valid email address.',
            'captcha_question' => $_SESSION['captcha_question'] ?? '',
            'captcha_hash' => $captchaHash,
        ]);
    }

    // Honeypot check
    if ($website !== '') {
        return view('contact', ['content' => $contactContent, 'success' => false, 'error' => 'Spam detected.']);
    }

    // Captcha check
    $expectedHash = hash('sha256', (string) ($_SESSION['captcha_answer'] ?? ''));
    if ($captchaHash !== $expectedHash || (string) $captcha !== (string) ($_SESSION['captcha_answer'] ?? '')) {
        $a = random_int(1, 10);
        $b = random_int(1, 10);
        $_SESSION['captcha_answer'] = $a + $b;
        $_SESSION['captcha_question'] = "What is {$a} + {$b}?";
        $hash = hash('sha256', (string) ($a + $b));

        return view('contact', [
            'content' => $contactContent,
            'success' => false,
            'error' => 'Invalid captcha. Please try again.',
            'captcha_question' => "What is {$a} + {$b}?",
            'captcha_hash' => $hash,
        ]);
    }

    // Save to database
    try {
        $db = app('db');
        $db->execute(
            'INSERT INTO contact_messages (name, email, subject, message, ip_address, created_at) VALUES (:name, :email, :subject, :message, :ip, NOW())',
            [
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message,
                'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
            ]
        );

        // Send notification email to admin
        $adminEmail = config('contact.email', '') ?: config('cms.mail.from', 'noreply@tavp.web.id');
        $siteName = config('general.site_name', 'TAVP');
        if ($adminEmail) {
            $mailSubject = "[{$siteName}] New Contact: " . ($subject ?: 'No Subject');
            $mailBody = "New contact message received:\n\n";
            $mailBody .= "Name: {$name}\n";
            $mailBody .= "Email: {$email}\n";
            $mailBody .= "Subject: {$subject}\n";
            $mailBody .= "Message:\n{$message}\n\n";
            $mailBody .= "IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . "\n";
            $mailBody .= "Date: " . date('Y-m-d H:i:s') . "\n\n";
            $mailBody .= "View in admin: " . (env('APP_URL', 'https://tavp.web.id')) . "/" . config('cms.admin.route_prefix', 'admin') . "\n";

            $headers = "From: " . config('cms.mail.from', 'noreply@tavp.web.id') . "\r\n";
            $headers .= "Reply-To: {$email}\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

            @mail($adminEmail, $mailSubject, $mailBody, $headers);
        }
    } catch (\Throwable $e) {
        error_log('Contact form error: ' . $e->getMessage());
    }

    // Generate new captcha
    $a = random_int(1, 10);
    $b = random_int(1, 10);
    $_SESSION['captcha_answer'] = $a + $b;
    $_SESSION['captcha_question'] = "What is {$a} + {$b}?";
    $hash = hash('sha256', (string) ($a + $b));

    return view('contact', [
        'content' => $contactContent,
        'success' => true,
        'message' => "Thank you {$name}! We'll get back to you soon.",
        'captcha_question' => "What is {$a} + {$b}?",
        'captcha_hash' => $hash,
    ]);
});

// --- Contact Messages Admin ------------------------------------------------
$msgPrefix = '/' . trim(config('cms.admin.route_prefix', 'admin'), '/');
$router->get("{$msgPrefix}/messages", function () use ($msgPrefix) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['cms_admin'])) {
        header('Location: ' . $msgPrefix . '/login');
        http_response_code(302);
        exit;
    }
    $db = app('db');
    $messages = $db->fetchAll('SELECT * FROM contact_messages ORDER BY created_at DESC', PDO::FETCH_ASSOC);
    
    // Use admin layout
    $brand = config('cms.admin.brand', 'TAVP');
    $adminEmail = $_SESSION['cms_admin'] ?? '';
    
    // Build messages HTML
    $msgHtml = '';
    if (empty($messages)) {
        $msgHtml = '<div class="text-center py-16 text-on-tertiary-container"><span class="material-symbols-outlined text-secondary text-5xl mb-4 block">mail</span><p>No messages yet.</p></div>';
    } else {
        foreach ($messages as $msg) {
            $isRead = $msg['is_read'] ?? 0;
            $msgHtml .= '<div class="bg-surface-container border border-outline-variant rounded-xl p-6' . ($isRead ? ' opacity-60' : '') . '">';
            $msgHtml .= '<div class="flex justify-between items-start mb-3">';
            $msgHtml .= '<div><span class="font-bold text-lg text-on-surface">' . htmlspecialchars($msg['name']) . '</span>';
            $msgHtml .= '<span class="text-on-tertiary-container ml-3 text-sm">' . htmlspecialchars($msg['email']) . '</span></div>';
            $msgHtml .= '<span class="text-xs text-on-tertiary-container">' . htmlspecialchars($msg['created_at'] ?? '') . '</span>';
            $msgHtml .= '</div>';
            if (!empty($msg['subject'])) {
                $msgHtml .= '<p class="font-semibold mb-2 text-on-surface">' . htmlspecialchars($msg['subject']) . '</p>';
            }
            $msgHtml .= '<p class="text-on-tertiary-container leading-relaxed">' . nl2br(htmlspecialchars($msg['message'])) . '</p>';
            $msgHtml .= '<p class="text-xs text-on-tertiary-container mt-3">IP: ' . htmlspecialchars($msg['ip_address'] ?? 'unknown') . '</p>';
            $msgHtml .= '</div>';
        }
    }
    
    // Wrap in admin layout
    $content = '<div class="flex justify-between items-center mb-gutter"><div><h2 class="font-headline-xl text-headline-xl">Contact Messages</h2><p class="font-body-md text-body-md text-on-surface-variant mt-1">View messages from the contact form</p></div></div>';
    $content .= '<div class="space-y-4">' . $msgHtml . '</div>';
    
    // Simple layout wrapper
    $html = '<!DOCTYPE html><html class="dark"><head><meta charset="utf-8"><title>Messages — ' . htmlspecialchars($brand) . '</title>';
    $html .= '<script src="https://cdn.tailwindcss.com"></script>';
    $html .= '<link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;600;700&family=Inter:wght@400;600&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet"/>';
    $html .= '<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>';
    $html .= '<script>tailwind.config={darkMode:"class",theme:{extend:{colors:{"background":"#0d131f","on-background":"#dde2f3","surface-container":"#1a202c","surface-container-low":"#161c27","surface-container-high":"#242a36","on-surface":"#dde2f3","on-surface-variant":"#c5c6cd","secondary":"#e6c446","on-secondary":"#3b2f00","outline-variant":"#45474c","on-tertiary-container":"#95a0b5","error":"#ffb4ab"}}}}</script>';
    $html .= '<style>.material-symbols-outlined{font-variation-settings:"FILL" 0,"wght" 400,"GRAD" 0,"opsz" 24;}</style>';
    $html .= '</head><body class="bg-background text-on-background font-[Inter]">';
    $html .= '<div class="max-w-[1280px] mx-auto px-10 py-8">';
    $html .= '<div class="mb-6"><a href="' . $msgPrefix . '" class="text-on-tertiary-container hover:text-secondary transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-sm">arrow_back</span> Back to Dashboard</a></div>';
    $html .= $content;
    $html .= '</div></body></html>';
    
    return new \Tavp\Core\Http\Response($html);
});

// Blog index
$router->get('/blog', function () {
    $posts = app()->getService(BreadManager::class)->browse('post', ['status' => 'published']);

    return view('blog', ['posts' => $posts]);
});



// Blog category archive
$router->get("/blog/category/{slug}", function (array $params) {
    try {
        $slug = $params["slug"] ?? "";
        $taxonomy = app()->getService(Tavp\Cms\Taxonomy\TaxonomyManager::class);
        $term = $taxonomy->findBySlug("category", $slug);

        if (!$term) {
            http_response_code(404);
            return view("404");
        }

        $postIds = $taxonomy->contentIdsWithTerm($term->id, "post");
        $bread = app()->getService(BreadManager::class);

        $posts = [];
        foreach ($postIds as $postId) {
            $post = $bread->read("post", $postId);
            if ($post && ($post["status"] ?? "draft") === "published") {
                $posts[] = $post;
            }
        }

        return view("taxonomy", [
            "posts" => $posts,
            "term" => ["name" => $term->name, "slug" => $term->slug, "type" => "category"],
            "type" => "category"
        ]);
    } catch (\Throwable $e) {
        return "Category Error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine();
    }
});

// Blog tag archive
$router->get("/blog/tag/{slug}", function (array $params) {
    try {
        $slug = $params["slug"] ?? "";
        $taxonomy = app()->getService(Tavp\Cms\Taxonomy\TaxonomyManager::class);
        $term = $taxonomy->findBySlug("tag", $slug);

        if (!$term) {
            http_response_code(404);
            return view("404");
        }

        $postIds = $taxonomy->contentIdsWithTerm($term->id, "post");
        $bread = app()->getService(BreadManager::class);

        $posts = [];
        foreach ($postIds as $postId) {
            $post = $bread->read("post", $postId);
            if ($post && ($post["status"] ?? "draft") === "published") {
                $posts[] = $post;
            }
        }

        return view("taxonomy", [
            "posts" => $posts,
            "term" => ["name" => $term->name, "slug" => $term->slug, "type" => "tag"],
            "type" => "tag"
        ]);
    } catch (\Throwable $e) {
        return "Tag Error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine();
    }
});

// Blog post
$router->get('/blog/{slug}', function (array $params) {
    $post = app()->getService(BreadManager::class)->readBySlug('post', $params['slug'] ?? '');

    if ($post === null || ($post['status'] ?? 'draft') !== 'published') {
        return view('404', []);
    }

    $body = $post['body'] ?? '';
    // If content already contains HTML tags (from WYSIWYG editor), use as-is.
    // Only convert Markdown if no HTML tags detected.
    if (preg_match('/<[a-z][a-z0-9]*[\s>]/i', $body)) {
        $post['body'] = $body;
    } else {
        $post['body'] = \App\Support\Markdown::toHtml($body);
    }

    return view('post', ['content' => $post]);
});

// --- SEO Admin Routes ---------------------------------------------------
$seoPrefix = '/' . trim(config('cms.admin.route_prefix', 'admin'), '/');
$router->get("{$seoPrefix}/seo", function () use ($seoPrefix) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['cms_admin'])) {
        header('Location: ' . $seoPrefix . '/login');
        http_response_code(302);
        exit;
    }
    $brand = config('cms.admin.brand', 'TAVP');
    $db = app('db');
    $pageCount = $db->fetchAll("SELECT COUNT(*) as cnt FROM contents WHERE status='published'", PDO::FETCH_ASSOC);
    $postCount = $db->fetchAll("SELECT COUNT(*) as cnt FROM contents WHERE type='post' AND status='published'", PDO::FETCH_ASSOC);
    
    $html = '<!DOCTYPE html><html class="dark"><head><meta charset="utf-8"><title>SEO Dashboard — ' . htmlspecialchars($brand) . '</title>';
    $html .= '<script src="https://cdn.tailwindcss.com"></script>';
    $html .= '<link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;600;700&family=Inter:wght@400;600&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet"/>';
    $html .= '<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>';
    $html .= '<script>tailwind.config={darkMode:"class",theme:{extend:{colors:{"background":"#0d131f","on-background":"#dde2f3","surface-container":"#1a202c","surface-container-low":"#161c27","surface-container-high":"#242a36","on-surface":"#dde2f3","on-surface-variant":"#c5c6cd","secondary":"#e6c446","on-secondary":"#3b2f00","outline-variant":"#45474c","on-tertiary-container":"#95a0b5","error":"#ffb4ab"}}}}</script>';
    $html .= '<style>.material-symbols-outlined{font-variation-settings:"FILL" 0,"wght" 400,"GRAD" 0,"opsz" 24;}</style>';
    $html .= '</head><body class="bg-background text-on-background font-[Inter]">';
    $html .= '<div class="max-w-[1280px] mx-auto px-10 py-8">';
    $html .= '<div class="mb-6"><a href="' . $seoPrefix . '" class="text-on-tertiary-container hover:text-secondary transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-sm">arrow_back</span> Back to Dashboard</a></div>';
    $html .= '<h2 class="font-headline-xl text-headline-xl mb-2">SEO Dashboard</h2>';
    $html .= '<p class="text-on-tertiary-container mb-8">Search engine optimization overview</p>';
    
    $html .= '<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">';
    $html .= '<div class="bg-surface-container border border-outline-variant rounded-xl p-6"><p class="text-on-tertiary-container text-sm mb-1">Published Pages</p><p class="text-3xl font-bold text-secondary">' . ($pageCount[0]['cnt'] ?? 0) . '</p></div>';
    $html .= '<div class="bg-surface-container border border-outline-variant rounded-xl p-6"><p class="text-on-tertiary-container text-sm mb-1">Published Posts</p><p class="text-3xl font-bold text-secondary">' . ($postCount[0]['cnt'] ?? 0) . '</p></div>';
    $html .= '<div class="bg-surface-container border border-outline-variant rounded-xl p-6"><p class="text-on-tertiary-container text-sm mb-1">Sitemap</p><a href="/sitemap.xml" target="_blank" class="text-secondary hover:underline">/sitemap.xml</a></div>';
    $html .= '</div>';
    
    $html .= '<div class="bg-surface-container border border-outline-variant rounded-xl p-6 mb-6">';
    $html .= '<h3 class="font-headline-lg text-headline-lg mb-4">Quick Links</h3>';
    $html .= '<div class="grid grid-cols-1 md:grid-cols-3 gap-4">';
    $html .= '<a href="/sitemap.xml" target="_blank" class="flex items-center gap-3 p-4 bg-surface-container-low border border-outline-variant rounded-lg hover:border-secondary transition-colors"><span class="material-symbols-outlined text-secondary">sitemap</span><div><p class="font-bold text-on-surface">Sitemap</p><p class="text-sm text-on-tertiary-container">XML sitemap for search engines</p></div></a>';
    $html .= '<a href="/robots.txt" target="_blank" class="flex items-center gap-3 p-4 bg-surface-container-low border border-outline-variant rounded-lg hover:border-secondary transition-colors"><span class="material-symbols-outlined text-secondary">smart_toy</span><div><p class="font-bold text-on-surface">Robots.txt</p><p class="text-sm text-on-tertiary-container">Crawler instructions</p></div></a>';
    $html .= '<a href="/feed" target="_blank" class="flex items-center gap-3 p-4 bg-surface-container-low border border-outline-variant rounded-lg hover:border-secondary transition-colors"><span class="material-symbols-outlined text-secondary">rss_feed</span><div><p class="font-bold text-on-surface">RSS Feed</p><p class="text-sm text-on-tertiary-container">Blog feed for readers</p></div></a>';
    $html .= '</div></div>';
    
    $html .= '</div></body></html>';
    return new \Tavp\Core\Http\Response($html);
});

// Page catch-all (keep last)
$router->get('/{slug}', function (array $params) {
    $page = app()->getService(BreadManager::class)->readBySlug('page', $params['slug'] ?? '');

    if ($page === null || ($page['status'] ?? 'draft') !== 'published') {
        http_response_code(404);
        return view('404');
    }

    $page['body'] = \App\Support\Markdown::toHtml($page['body'] ?? '');

    return view('page', ['content' => $page]);
});
