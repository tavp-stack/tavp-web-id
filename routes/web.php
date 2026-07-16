<?php

declare(strict_types=1);

error_log('WEB.PHP LOADED: ' . ($_SERVER['REQUEST_URI'] ?? 'CLI'));

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
$msgPrefix = '/';
try {
    $s = app()->getService(\Tavp\Cms\Settings\Settings::class);
    $p = $s?->get('admin.route_prefix');
    if ($p) $msgPrefix = '/' . trim($p, '/');
} catch (\Throwable) {}

$router->get("{$msgPrefix}/messages", function () use ($msgPrefix) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['cms_admin'])) {
        header('Location: ' . $msgPrefix . '/login');
        http_response_code(302);
        exit;
    }

    $db = app('db');
    $messages = $db->fetchAll('SELECT * FROM contact_messages ORDER BY created_at DESC', PDO::FETCH_ASSOC);
    $unread = 0;
    foreach ($messages as $m) { if (!($m['is_read'] ?? 0)) $unread++; }

    // Variables for sidebar
    $adminPrefix = $msgPrefix;
    $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $__brand = config('cms.admin.brand', 'TAVP');
    $__auth_email = $_SESSION['cms_admin'] ?? '';

    $html = '<!DOCTYPE html><html class="dark" lang="id"><head><meta charset="utf-8"><title>Messages — TAVP Admin</title>';
    $html .= '<script src="https://cdn.tailwindcss.com"></script>';
    $html .= '<script>tailwind.config={darkMode:"class",theme:{extend:{colors:{"background":"#0d131f","on-background":"#dde2f3","surface":"#0d131f","surface-container-lowest":"#080e1a","surface-container-low":"#161c27","surface-container":"#1a202c","surface-container-high":"#242a36","surface-container-highest":"#2f3542","on-surface":"#dde2f3","on-surface-variant":"#c5c6cd","primary":"#bdc7dc","on-primary":"#273141","primary-container":"#2d3748","secondary":"#e6c446","on-secondary":"#3b2f00","secondary-container":"#ac8e0a","tertiary":"#bcc7dd","on-tertiary-container":"#95a0b5","outline":"#8f9097","outline-variant":"#45474c","error":"#ffb4ab"}}}}</script>';
    $html .= '<link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;600;700&family=Inter:wght@400;600&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet"/>';
    $html .= '<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>';
    $html .= '<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>';
    $html .= '<style>.material-symbols-outlined{font-variation-settings:"FILL" 0,"wght" 400,"GRAD" 0,"opsz" 24;} .hard-step-shadow{box-shadow:2px 2px 0 0 #000} .msg-card{transition:all .15s} .msg-card:hover{border-color:#e6c446} .msg-card.active{background:#242a36;border-color:#e6c446}</style>';
    $html .= '</head><body class="bg-background text-on-background overflow-x-hidden">';

    // Include sidebar
    ob_start();
    include dirname(__DIR__) . '/vendor/tavp/cms/resources/admin/_sidebar.php';
    $html .= ob_get_clean();

    // Main content
    $html .= '<main class="min-h-screen bg-background transition-all duration-300" x-data="{ sidebarCollapsed: false }" :class="sidebarCollapsed ? \'ml-[68px]\' : \'ml-64\'">';
    $html .= '<div class="max-w-[1280px] mx-auto px-10 py-8">';

    // Messages header
    $html .= '<div class="flex justify-between items-center mb-8"><div><h2 class="text-2xl font-bold text-on-surface">Messages</h2><p class="text-sm text-on-tertiary-container mt-1">' . count($messages) . ' contact messages</p></div>';
    if ($unread > 0) $html .= '<span class="text-xs px-3 py-1 rounded-full bg-error/20 text-error font-bold">' . $unread . ' unread</span>';
    $html .= '</div>';

    if (empty($messages)) {
        $html .= '<div class="text-center py-24"><span class="material-symbols-outlined text-6xl text-on-tertiary-container/30 mb-4">mail</span><p class="text-on-tertiary-container text-lg">No messages yet.</p></div>';
    } else {
        $html .= '<div x-data="{ selected: 0 }" class="grid grid-cols-1 lg:grid-cols-12 gap-6">';
        $html .= '<div class="lg:col-span-4 space-y-1 overflow-y-auto pr-2" style="max-height:calc(100vh - 12rem)">';
        foreach ($messages as $i => $msg) {
            $html .= '<div class="msg-card p-4 rounded-lg border cursor-pointer ' . ($i === 0 ? 'active border-secondary' : 'border-outline-variant') . '" @click="selected = ' . $i . '">';
            $html .= '<div class="flex items-center gap-3 mb-2"><div class="w-8 h-8 rounded-full bg-secondary/20 flex items-center justify-center shrink-0"><span class="text-sm font-bold text-secondary">' . strtoupper(substr(htmlspecialchars($msg['name']), 0, 1)) . '</span></div>';
            $html .= '<div class="flex-1 min-w-0"><span class="font-bold text-sm text-on-surface truncate block">' . htmlspecialchars($msg['name']) . '</span>';
            $html .= '<span class="text-xs text-on-tertiary-container">' . date('M j, g:ia', strtotime($msg['created_at'])) . '</span></div></div>';
            $html .= '<p class="text-xs text-on-tertiary-container truncate ml-11">' . htmlspecialchars($msg['subject'] ?: substr($msg['message'], 0, 50)) . '</p></div>';
        }
        $html .= '</div>';
        $html .= '<div class="lg:col-span-8">';
        foreach ($messages as $i => $msg) {
            $html .= '<div x-show="selected === ' . $i . '" x-transition class="bg-surface-container border border-outline-variant rounded-xl p-8">';
            $html .= '<div class="flex items-start justify-between mb-6"><div class="flex items-center gap-4">';
            $html .= '<div class="w-12 h-12 rounded-full bg-secondary/20 flex items-center justify-center"><span class="text-lg font-bold text-secondary">' . strtoupper(substr(htmlspecialchars($msg['name']), 0, 1)) . '</span></div>';
            $html .= '<div><h3 class="text-lg font-bold text-on-surface">' . htmlspecialchars($msg['name']) . '</h3><p class="text-sm text-on-tertiary-container">' . htmlspecialchars($msg['email']) . '</p></div></div>';
            $html .= '<span class="text-xs text-on-tertiary-container">' . htmlspecialchars($msg['created_at']) . '</span></div>';
            if (!empty($msg['subject'])) {
                $html .= '<div class="mb-4"><span class="text-[10px] font-semibold uppercase tracking-wider text-on-tertiary-container">Subject</span><p class="text-on-surface font-semibold mt-1">' . htmlspecialchars($msg['subject']) . '</p></div>';
            }
            $html .= '<div class="bg-surface-container-low border border-outline-variant rounded-lg p-6 mb-4"><p class="text-on-surface leading-relaxed whitespace-pre-wrap">' . htmlspecialchars($msg['message']) . '</p></div>';
            $html .= '<div class="flex items-center gap-4 text-xs text-on-tertiary-container"><span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">language</span>IP: ' . htmlspecialchars($msg['ip_address'] ?? 'unknown') . '</span></div></div>';
        }
        $html .= '</div></div>';
    }

    $html .= '</div></main></body></html>';
    return (new \Tavp\Core\Http\Response())->setContent($html);
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
    // Always convert markdown to HTML (EasyMDE outputs markdown)
    $post['body'] = \App\Support\Markdown::toHtml($body);

    return view('post', ['content' => $post]);
});

// --- SEO Admin Routes ---------------------------------------------------
$seoPrefix = '/';
try {
    $s = app()->getService(\Tavp\Cms\Settings\Settings::class);
    $p = $s?->get('admin.route_prefix');
    if ($p) $seoPrefix = '/' . trim($p, '/');
} catch (\Throwable $e) { error_log('SEO PREFIX ERROR: ' . $e->getMessage()); }
error_log('SEO PREFIX: ' . $seoPrefix);

$router->get("{$seoPrefix}/seo", function () use ($seoPrefix) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['cms_admin'])) {
        header('Location: ' . $seoPrefix . '/login');
        http_response_code(302);
        exit;
    }

    $db = app('db');
    $pageCount = $db->fetchAll("SELECT COUNT(*) as cnt FROM contents WHERE status='published'", PDO::FETCH_ASSOC);
    $postCount = $db->fetchAll("SELECT COUNT(*) as cnt FROM contents WHERE type='post' AND status='published'", PDO::FETCH_ASSOC);
    $contactCount = $db->fetchAll("SELECT COUNT(*) as cnt FROM contact_messages", PDO::FETCH_ASSOC);

    // Variables for sidebar
    $adminPrefix = $seoPrefix;
    $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $__brand = config('cms.admin.brand', 'TAVP');
    $__auth_email = $_SESSION['cms_admin'] ?? '';

    $html = '<!DOCTYPE html><html class="dark" lang="id"><head><meta charset="utf-8"><title>SEO Dashboard — TAVP Admin</title>';
    $html .= '<script src="https://cdn.tailwindcss.com"></script>';
    $html .= '<script>tailwind.config={darkMode:"class",theme:{extend:{colors:{"background":"#0d131f","on-background":"#dde2f3","surface":"#0d131f","surface-container-lowest":"#080e1a","surface-container-low":"#161c27","surface-container":"#1a202c","surface-container-high":"#242a36","surface-container-highest":"#2f3542","on-surface":"#dde2f3","on-surface-variant":"#c5c6cd","primary":"#bdc7dc","on-primary":"#273141","primary-container":"#2d3748","secondary":"#e6c446","on-secondary":"#3b2f00","secondary-container":"#ac8e0a","tertiary":"#bcc7dd","on-tertiary-container":"#95a0b5","outline":"#8f9097","outline-variant":"#45474c","error":"#ffb4ab"}}}}</script>';
    $html .= '<link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;600;700&family=Inter:wght@400;600&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet"/>';
    $html .= '<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>';
    $html .= '<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>';
    $html .= '<style>.material-symbols-outlined{font-variation-settings:"FILL" 0,"wght" 400,"GRAD" 0,"opsz" 24;} .hard-step-shadow{box-shadow:2px 2px 0 0 #000} .seo-card{transition:all .15s} .seo-card:hover{border-color:#e6c446;transform:translateY(-2px)}</style>';
    $html .= '</head><body class="bg-background text-on-background overflow-x-hidden">';

    // Include sidebar
    ob_start();
    include dirname(__DIR__) . '/vendor/tavp/cms/resources/admin/_sidebar.php';
    $html .= ob_get_clean();

    // Main content
    $html .= '<main class="min-h-screen bg-background transition-all duration-300" x-data="{ sidebarCollapsed: false }" :class="sidebarCollapsed ? \'ml-[68px]\' : \'ml-64\'">';
    $html .= '<div class="max-w-[1280px] mx-auto px-10 py-8">';

    $html .= '<div class="mb-8"><h2 class="text-2xl font-bold text-on-surface">SEO Dashboard</h2><p class="text-sm text-on-tertiary-container mt-1">Search engine optimization overview</p></div>';

    $html .= '<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">';
    $html .= '<div class="seo-card bg-surface-container border border-outline-variant rounded-xl p-6"><div class="flex items-center gap-3 mb-3"><span class="material-symbols-outlined text-secondary text-2xl">description</span><p class="text-on-tertiary-container text-sm">Pages</p></div><p class="text-3xl font-bold text-secondary">' . ($pageCount[0]['cnt'] ?? 0) . '</p></div>';
    $html .= '<div class="seo-card bg-surface-container border border-outline-variant rounded-xl p-6"><div class="flex items-center gap-3 mb-3"><span class="material-symbols-outlined text-secondary text-2xl">article</span><p class="text-on-tertiary-container text-sm">Posts</p></div><p class="text-3xl font-bold text-secondary">' . ($postCount[0]['cnt'] ?? 0) . '</p></div>';
    $html .= '<div class="seo-card bg-surface-container border border-outline-variant rounded-xl p-6"><div class="flex items-center gap-3 mb-3"><span class="material-symbols-outlined text-secondary text-2xl">mail</span><p class="text-on-tertiary-container text-sm">Messages</p></div><p class="text-3xl font-bold text-secondary">' . ($contactCount[0]['cnt'] ?? 0) . '</p></div>';
    $html .= '<div class="seo-card bg-surface-container border border-outline-variant rounded-xl p-6"><div class="flex items-center gap-3 mb-3"><span class="material-symbols-outlined text-secondary text-2xl">folder_open</span><p class="text-on-tertiary-container text-sm">Sitemap</p></div><a href="/sitemap.xml" target="_blank" class="text-secondary hover:underline text-sm">View →</a></div>';
    $html .= '</div>';

    $html .= '<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">';
    $html .= '<div class="bg-surface-container border border-outline-variant rounded-xl p-6"><h3 class="text-lg font-bold mb-4">Quick Links</h3><div class="space-y-3">';
    $html .= '<a href="' . $seoPrefix . '/seo/settings" class="seo-card flex items-center gap-4 p-4 bg-surface-container-low border border-outline-variant rounded-lg"><span class="material-symbols-outlined text-secondary text-xl">settings</span><div><p class="font-bold text-on-surface text-sm">SEO Settings</p><p class="text-xs text-on-tertiary-container">Meta tags, OG, Twitter</p></div></a>';
    $html .= '<a href="' . $seoPrefix . '/seo/redirects" class="seo-card flex items-center gap-4 p-4 bg-surface-container-low border border-outline-variant rounded-lg"><span class="material-symbols-outlined text-secondary text-xl">forward</span><div><p class="font-bold text-on-surface text-sm">Redirects</p><p class="text-xs text-on-tertiary-container">301/302 redirects</p></div></a>';
    $html .= '<a href="' . $seoPrefix . '/seo/analyzer" class="seo-card flex items-center gap-4 p-4 bg-surface-container-low border border-outline-variant rounded-lg"><span class="material-symbols-outlined text-secondary text-xl">analytics</span><div><p class="font-bold text-on-surface text-sm">Analyzer</p><p class="text-xs text-on-tertiary-container">SEO scores</p></div></a>';
    $html .= '</div></div>';

    $html .= '<div class="bg-surface-container border border-outline-variant rounded-xl p-6"><h3 class="text-lg font-bold mb-4">External Tools</h3><div class="space-y-3">';
    $html .= '<a href="/sitemap.xml" target="_blank" class="seo-card flex items-center gap-4 p-4 bg-surface-container-low border border-outline-variant rounded-lg"><span class="material-symbols-outlined text-secondary text-xl">folder_open</span><div><p class="font-bold text-on-surface text-sm">XML Sitemap</p><p class="text-xs text-on-tertiary-container">For search engines</p></div></a>';
    $html .= '<a href="/robots.txt" target="_blank" class="seo-card flex items-center gap-4 p-4 bg-surface-container-low border border-outline-variant rounded-lg"><span class="material-symbols-outlined text-secondary text-xl">smart_toy</span><div><p class="font-bold text-on-surface text-sm">Robots.txt</p><p class="text-xs text-on-tertiary-container">Crawler instructions</p></div></a>';
    $html .= '<a href="/feed" target="_blank" class="seo-card flex items-center gap-4 p-4 bg-surface-container-low border border-outline-variant rounded-lg"><span class="material-symbols-outlined text-secondary text-xl">rss_feed</span><div><p class="font-bold text-on-surface text-sm">RSS Feed</p><p class="text-xs text-on-tertiary-container">Blog feed</p></div></a>';
    $html .= '</div></div></div>';

    $html .= '</div></main></body></html>';
    return (new \Tavp\Core\Http\Response())->setContent($html);
});

// SEO sub-routes (settings, redirects, analyzer, ping)
$router->get("{$seoPrefix}/seo/test123", function () {
    return (new \Tavp\Core\Http\Response())->setContent('SEO Test123 Works!');
});

$router->get("{$seoPrefix}/seo/settings", function () use ($seoPrefix) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['cms_admin'])) {
        header('Location: ' . $seoPrefix . '/login'); http_response_code(302); exit;
    }
    if (empty($_SESSION['cms_admin'])) {
        header('Location: ' . $seoPrefix . '/login'); http_response_code(302); exit;
    }
    $html = '<!DOCTYPE html><html class="dark" lang="id"><head><meta charset="utf-8"><title>SEO Settings</title>';
    $html .= '<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2/dist/tailwind.min.css" rel="stylesheet">';
    $html .= '<script>tailwind.config={darkMode:"class",theme:{extend:{colors:{"bg":"#0d131f","surface":"#1a202c","high":"#242a36","text":"#dde2f3","sec":"#e6c446","out":"#45474c","dim":"#95a0b5"}}}}</script>';
    $html .= '</head><body class="bg-bg text-text"><div class="max-w-3xl mx-auto px-6 py-8">';
    $html .= '<a href="' . $seoPrefix . '/seo" class="text-dim hover:text-sec mb-4 inline-block">← Back to SEO</a>';
    $html .= '<h1 class="text-2xl font-bold text-sec mb-6">SEO Settings</h1>';
    $html .= '<p class="text-dim">SEO settings will be configurable here. For now, edit config/seo.php directly.</p>';
    $html .= '</div></body></html>';
    return (new \Tavp\Core\Http\Response())->setContent($html);
});

$router->get("{$seoPrefix}/seo/redirects", function () use ($seoPrefix) {
    $db = app('db');
    $redirects = $db->fetchAll('SELECT * FROM redirects ORDER BY created_at DESC', PDO::FETCH_ASSOC);
    
    $html = '<!DOCTYPE html><html class="dark"><head><meta charset="utf-8"><title>Redirects</title>';
    $html .= '<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2/dist/tailwind.min.css" rel="stylesheet">';
    $html .= '</head><body class="bg-[#0d131f] text-[#dde2f3]"><div class="max-w-3xl mx-auto px-6 py-8">';
    $html .= '<h1 class="text-2xl font-bold mb-6 text-[#e6c446]">Redirects (' . count($redirects) . ')</h1>';
    $html .= '<a href="' . $seoPrefix . '/seo" class="text-[#e6c446]">← Back</a>';
    $html .= '</div></body></html>';
    return (new \Tavp\Core\Http\Response())->setContent($html);
    $redirects = $db->fetchAll('SELECT * FROM redirects ORDER BY created_at DESC', PDO::FETCH_ASSOC);
    $html = '<!DOCTYPE html><html class="dark" lang="id"><head><meta charset="utf-8"><title>SEO Redirects</title>';
    $html .= '<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2/dist/tailwind.min.css" rel="stylesheet">';
    $html .= '<script>tailwind.config={darkMode:"class",theme:{extend:{colors:{"bg":"#0d131f","surface":"#1a202c","high":"#242a36","text":"#dde2f3","sec":"#e6c446","out":"#45474c","dim":"#95a0b5"}}}}</script>';
    $html .= '</head><body class="bg-bg text-text"><div class="max-w-3xl mx-auto px-6 py-8">';
    $html .= '<a href="' . $seoPrefix . '/seo" class="text-dim hover:text-sec mb-4 inline-block">← Back to SEO</a>';
    $html .= '<h1 class="text-2xl font-bold text-sec mb-6">Redirects</h1>';
    if (empty($redirects)) {
        $html .= '<p class="text-dim">No redirects configured.</p>';
    } else {
        $html .= '<div class="space-y-2">';
        foreach ($redirects as $r) {
            $html .= '<div class="bg-surface border border-out rounded p-3 text-sm">';
            $html .= '<span class="text-sec">' . htmlspecialchars($r['from_url']) . '</span> → <span class="text-dim">' . htmlspecialchars($r['to_url']) . '</span>';
            $html .= '</div>';
        }
        $html .= '</div>';
    }
    $html .= '</div></body></html>';
    return (new \Tavp\Core\Http\Response())->setContent($html);
});

$router->get("{$seoPrefix}/seo/analyzer", function () use ($seoPrefix) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['cms_admin'])) {
        header('Location: ' . $seoPrefix . '/login'); http_response_code(302); exit;
    }
    $html = '<!DOCTYPE html><html class="dark" lang="id"><head><meta charset="utf-8"><title>SEO Analyzer</title>';
    $html .= '<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2/dist/tailwind.min.css" rel="stylesheet">';
    $html .= '<script>tailwind.config={darkMode:"class",theme:{extend:{colors:{"bg":"#0d131f","surface":"#1a202c","high":"#242a36","text":"#dde2f3","sec":"#e6c446","out":"#45474c","dim":"#95a0b5"}}}}</script>';
    $html .= '</head><body class="bg-bg text-text"><div class="max-w-3xl mx-auto px-6 py-8">';
    $html .= '<a href="' . $seoPrefix . '/seo" class="text-dim hover:text-sec mb-4 inline-block">← Back to SEO</a>';
    $html .= '<h1 class="text-2xl font-bold text-sec mb-6">SEO Analyzer</h1>';
    $html .= '<p class="text-dim">SEO content analyzer will check your pages for optimization opportunities.</p>';
    $html .= '</div></body></html>';
    return (new \Tavp\Core\Http\Response())->setContent($html);
});

$router->post("{$seoPrefix}/seo/ping", function () use ($seoPrefix) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['cms_admin'])) {
        return (new \Tavp\Core\Http\Response())->setContent('Unauthorized');
    }
    $siteUrl = env('APP_URL', 'https://tavp.web.id');
    $sitemapUrl = $siteUrl . '/sitemap.xml';
    $results = [];
    
    // Ping Google
    $google = @file_get_contents("https://www.google.com/ping?sitemap=" . urlencode($sitemapUrl));
    $results[] = 'Google: ' . ($google !== false ? 'OK' : 'Failed');
    
    // Ping Bing
    $bing = @file_get_contents("https://www.bing.com/indexnow?url=" . urlencode($siteUrl) . "&key=none");
    $results[] = 'Bing: ' . ($bing !== false ? 'OK' : 'Failed');
    
    $html = '<!DOCTYPE html><html class="dark" lang="id"><head><meta charset="utf-8"><title>Ping Result</title>';
    $html .= '<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2/dist/tailwind.min.css" rel="stylesheet">';
    $html .= '<script>tailwind.config={darkMode:"class",theme:{extend:{colors:{"bg":"#0d131f","surface":"#1a202c","high":"#242a36","text":"#dde2f3","sec":"#e6c446","out":"#45474c","dim":"#95a0b5"}}}}</script>';
    $html .= '</head><body class="bg-bg text-text"><div class="max-w-3xl mx-auto px-6 py-8">';
    $html .= '<a href="' . $seoPrefix . '/seo" class="text-dim hover:text-sec mb-4 inline-block">← Back to SEO</a>';
    $html .= '<h1 class="text-2xl font-bold text-sec mb-6">Sitemap Ping Result</h1>';
    $html .= '<div class="space-y-2">';
    foreach ($results as $r) {
        $html .= '<p class="text-dim">' . $r . '</p>';
    }
    $html .= '</div>';
    $html .= '</div></body></html>';
    return (new \Tavp\Core\Http\Response())->setContent($html);
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
