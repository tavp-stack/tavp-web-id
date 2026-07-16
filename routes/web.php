<?php

declare(strict_types=1);

error_log('ROUTES FILE LOADED FROM: ' . ($_SERVER['REQUEST_URI'] ?? 'CLI'));

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
$router->get('/admin/inbox', function () {
    $db = app('db');
    $messages = $db->fetchAll('SELECT * FROM contact_messages ORDER BY created_at DESC', PDO::FETCH_ASSOC);

    $html = '<!DOCTYPE html><html class="dark" lang="id"><head><meta charset="utf-8"><title>Messages</title>';
    $html .= '<script src="https://cdn.tailwindcss.com"></script>';
    $html .= '<script>tailwind.config={darkMode:"class",theme:{extend:{colors:{"bg":"#0d131f","surface":"#1a202c","high":"#242a36","text":"#dde2f3","sec":"#e6c446","out":"#45474c","dim":"#95a0b5"}}}}</script>';
    $html .= '</head><body class="bg-bg text-text"><div class="max-w-5xl mx-auto px-6 py-8">';
    $html .= '<div class="flex justify-between items-center mb-6">';
    $html .= '<h1 class="text-2xl font-bold text-sec">Messages (' . count($messages) . ')</h1>';
    $html .= '<a href="/admin" class="text-dim hover:text-sec">← Dashboard</a></div>';

    if (empty($messages)) {
        $html .= '<p class="text-dim">No messages yet.</p>';
    } else {
        $html .= '<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">';
        $html .= '<div class="space-y-2">';
        foreach ($messages as $i => $msg) {
            $html .= '<div class="bg-surface border border-out rounded-lg p-4 cursor-pointer hover:border-sec transition" onclick="showMsg(' . $i . ')">';
            $html .= '<div class="flex justify-between mb-1"><span class="font-bold text-sm">' . htmlspecialchars($msg['name']) . '</span><span class="text-xs text-dim">' . date('M j', strtotime($msg['created_at'])) . '</span></div>';
            $html .= '<p class="text-xs text-dim truncate">' . htmlspecialchars($msg['subject'] ?: 'No subject') . '</p>';
            $html .= '</div>';
        }
        $html .= '</div>';
        $html .= '<div id="msg-detail" class="bg-surface border border-out rounded-lg p-6">';
        if (!empty($messages)) {
            $msg = $messages[0];
            $html .= '<h3 class="font-bold text-lg mb-2">' . htmlspecialchars($msg['name']) . '</h3>';
            $html .= '<p class="text-sm text-dim mb-4">' . htmlspecialchars($msg['email']) . ' · ' . htmlspecialchars($msg['created_at']) . '</p>';
            if (!empty($msg['subject'])) $html .= '<p class="font-semibold mb-3">Subject: ' . htmlspecialchars($msg['subject']) . '</p>';
            $html .= '<div class="bg-high border border-out rounded p-4"><p class="whitespace-pre-wrap">' . htmlspecialchars($msg['message']) . '</p></div>';
        }
        $html .= '</div></div>';
        $html .= '<script>var msgs=' . json_encode($messages) . ';function showMsg(i){var m=msgs[i],d=document.getElementById("msg-detail");d.innerHTML="<h3 class=\"font-bold text-lg mb-2\">"+esc(m.name)+"</h3><p class=\"text-sm text-dim mb-4\">"+esc(m.email)+" · "+esc(m.created_at)+"</p>"+(m.subject?"<p class=\"font-semibold mb-3\">Subject: "+esc(m.subject)+"</p>":"")+"<div class=\"bg-high border border-out rounded p-4\"><p class=\"whitespace-pre-wrap\">"+esc(m.message)+"</p></div>";}function esc(t){var d=document.createElement("div");d.textContent=t;return d.innerHTML;}</script>';
    }
    $html .= '</div></body></html>';
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
$seoPrefix = '/' . trim(config('cms.admin.route_prefix', 'admin'), '/');
$router->get("{$seoPrefix}/seo", function () {
    $ctrl = new \App\Admin\SeoController();
    return $ctrl->index();
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
