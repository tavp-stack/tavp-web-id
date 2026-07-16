<?php

declare(strict_types=1);

/**
 * TAVP Content Seed Script
 *
 * Injects all hardcoded frontend content into the `contents` table.
 * Uses INSERT ... ON DUPLICATE KEY UPDATE to handle existing records.
 *
 * Usage: php seed_content.php
 */

// ─── Database Configuration ───────────────────────────────────────────────────
$host = '127.0.0.1';
$db   = 'tavp';
$user = 'tavp';
$pass = 'tavp';

// ─── Connect ─────────────────────────────────────────────────────────────────
try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$db};charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
    echo "✔ Connected to MariaDB ({$host}/{$db})\n\n";
} catch (PDOException $e) {
    fwrite(STDERR, "✘ Connection failed: " . $e->getMessage() . "\n");
    exit(1);
}

// ─── Helper: slugify ─────────────────────────────────────────────────────────
function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

// ─── Helper: upsert content ──────────────────────────────────────────────────
function upsert(PDO $pdo, string $type, string $slug, array $data, string $status = 'published'): void
{
    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    $now  = date('Y-m-d H:i:s');

    // Check if record exists
    $stmt = $pdo->prepare("SELECT id FROM contents WHERE type = :type AND slug = :slug LIMIT 1");
    $stmt->execute([':type' => $type, ':slug' => $slug]);
    $row = $stmt->fetch();

    if ($row) {
        $sql = "UPDATE contents
                SET data = :data, status = :status, updated_at = :updated_at
                WHERE type = :type AND slug = :slug";
        $pdo->prepare($sql)->execute([
            ':data'       => $json,
            ':status'     => $status,
            ':updated_at' => $now,
            ':type'       => $type,
            ':slug'       => $slug,
        ]);
        echo "  ↻ UPDATED  {$type}/{$slug}\n";
    } else {
        $sql = "INSERT INTO contents (type, slug, status, data, author_id, published_at, created_at, updated_at)
                VALUES (:type, :slug, :status, :data, 1, :published_at, :created_at, :updated_at)";
        $pdo->prepare($sql)->execute([
            ':type'         => $type,
            ':slug'         => $slug,
            ':status'       => $status,
            ':data'         => $json,
            ':published_at' => $now,
            ':created_at'   => $now,
            ':updated_at'   => $now,
        ]);
        echo "  ✔ INSERTED {$type}/{$slug}\n";
    }
}

// ═════════════════════════════════════════════════════════════════════════════
//  CONTENT DATA
// ═════════════════════════════════════════════════════════════════════════════

echo "━━━ Seeding content types ━━━\n\n";

// ─── 1. HOME PAGE ────────────────────────────────────────────────────────────
echo "[home]\n";
$homeData = [
    'hero_badge'      => 'Stack v1.0 · Stable',
    'hero_title'      => 'The Lean, Mean, PHP Machine.',
    'hero_subtitle'   => 'Build blazingly fast systems with Tailwind, Alpine, Volt, and Phalcon. Thin, light, and engineered for the sub-millisecond era.',
    'cta_primary'     => 'Get Started',
    'cta_secondary'   => 'View Benchmarks',
    'feature_1_title' => 'Lean Architecture',
    'feature_1_desc'  => 'A C-extension core runs your code close to the metal, while a Laravel-style ergonomic layer keeps development a joy.',
    'feature_2_title' => 'Thin Core',
    'feature_2_desc'  => 'Modular by design. Load exactly what your application needs — nothing more.',
    'feature_3_title' => 'High Throughput',
    'feature_3_desc'  => 'Thousands of requests per second on a modest 2-core VPS. Up to 12,000+ with the Coil runtime.',
    'feature_4_title' => 'Low RAM Footprint',
    'feature_4_desc'  => 'Peak performance in under 15MB per worker — efficient enough for edge, containers, and modest boxes alike.',
    'platforms_title'    => 'Runs Where You Do',
    'platforms_subtitle' => 'From the $5/mo VPS you already own to Docker and managed panels — TAVP feels right at home everywhere.',
    'stat_1_label' => 'Response Time',
    'stat_1_value' => '<5ms',
    'stat_1_desc'  => 'P95 latency on a 2-core VPS with PHP-FPM.',
    'stat_2_label' => 'Throughput',
    'stat_2_value' => '12,000+',
    'stat_2_desc'  => 'Requests per second with the Coil (Swoole) runtime.',
    'stat_3_label' => 'Memory',
    'stat_3_value' => '<15MB',
    'stat_3_desc'  => 'Per worker, at peak performance.',
    'cta_title'        => 'Less config, more craft.',
    'cta_highlight'    => 'Start building your product.',
    'cta_final_1_text' => 'Get Started',
    'cta_final_2_text' => 'Documentation',
    'feature_1_code'   => '<span class="text-secondary"># Response time (PHP-FPM)</span><br/><span class="text-on-surface">P95 latency: </span> <span class="text-secondary">&lt;5ms</span>',
];
upsert($pdo, 'home', slugify($homeData['hero_title']), $homeData);

// ─── 2. GET STARTED PAGE ────────────────────────────────────────────────────
echo "\n[get_started]\n";
$getStartedData = [
    'badge'      => 'STABLE RELEASE V1.0',
    'page_title' => 'Installation Guide',
    'intro'      => 'Set up the TAVP stack on your local environment or production server in a few minutes. Thin, light, and low-latency by default.',
    'step1_title' => 'Install the Phalcon Extension',
    'step1_desc'  => 'Phalcon is a C-extension for PHP — the backbone of the stack. The TAVP CLI installs it for you.',
    'step1_code'  => '<span class="token-comment"># Install the TAVP CLI globally</span><br/>composer global require tavp/cli<br/><br/><span class="token-comment"># Install the Phalcon C-extension</span><br/>tavp phalcon:install',
    'step2_title' => 'Create Your Project',
    'step2_desc'  => 'Bootstrap a new app with Composer, then start the development server.',
    'step2_code'  => 'composer create-project tavp/core my-app<br/><span class="token-keyword">cd</span> my-app<br/>tavp serve',
    'step3_title' => 'TAVPblocks — UI Components',
    'step3_desc'  => 'No need to install Tailwind or Alpine manually. TAVPblocks includes 40+ pre-built UI components (buttons, modals, forms, cards, charts, etc.) that work out of the box with Tailwind CSS and Alpine.js.',
    'step3_code'  => '<span class="token-comment"># TAVPblocks is included via composer</span><br/>composer require tavp/tavpblocks<br/><br/><span class="token-comment"># Components available out of the box:</span><br/>Button, Input, Select, Modal, Card,<br/>Chart, Datatable, Form, Pagination...',
    'hello_title' => 'Hello World in Volt',
    'hello_desc'  => 'Volt compiles to plain PHP for speed. Here is a simple counter using Alpine.js inside a Volt template.',
    'hello_code'  => '<span class="token-comment">&lt;!-- Volt + Alpine.js --&gt;</span>' . "\n" . '&lt;div <span class="token-keyword">x-data</span>="{ count: 0 }" class="p-8 text-center"&gt;' . "\n" . '    &lt;h1 class="text-3xl font-bold"&gt;' . "\n" . '        &#123;&#123; <span class="token-string">"Hello, "</span> ~ user_name &#125;&#125;' . "\n" . '    &lt;/h1&gt;' . "\n" . '' . "\n" . '    &lt;button <span class="token-keyword">@click</span>="count++" class="bg-secondary text-black px-4 py-2 mt-4"&gt;' . "\n" . '        Clicks: &lt;span <span class="token-keyword">x-text</span>="count"&gt;&lt;/span&gt;' . "\n" . '    &lt;/button&gt;' . "\n" . '&lt;/div&gt;',
    'tips_title'  => 'VPS Optimization',
    'tips_desc'   => "Running on a \$5/mo droplet? TAVP is designed for exactly that. A few tips to squeeze out every drop:",
    'help_title'  => 'Need Help?',
    'help_desc'   => 'Read the full documentation or join the community.',
    'help_button' => 'Open the Docs',
    'help_url'    => 'https://docs.tavp.web.id/index.html',
];
upsert($pdo, 'get_started', slugify($getStartedData['page_title']), $getStartedData);

// ─── 3. PERFORMANCE PAGE ────────────────────────────────────────────────────
echo "\n[performance]\n";
$performanceData = [
    'hero_title' => 'Built for Bare Metal Speed.',
    'hero_intro' => "There are many roads to building great software. TAVP is the path for those who want bare-metal speed with modern ergonomics. Because Phalcon lives in memory as a C-extension, the same app runs comfortably on a tiny box or scales out to serve millions.",
    'cta1_label' => 'Explore Runtimes',
    'cta1_url'   => 'https://docs.tavp.web.id/runtimes/overview.html',
    'cta2_label' => 'Methodology',
    'cta2_url'   => 'https://docs.tavp.web.id/reference/performance.html',
    'lowend_title' => 'The "Low-End Box" Test',
    'lowend_desc'  => 'We ran TAVP on a modest VPS to show how far efficient architecture goes.',
    'why_title'    => "Why It's Fast",
    'arch_badge'   => 'Architecture Focus',
    'arch_title'   => 'Leaner Internals, Faster Deployment.',
    'arch_intro'   => "With Phalcon's shared-memory model, the framework is parsed once when the server starts — not on every request. That efficiency is why a full app can run happily on hardware that would otherwise feel cramped.",
    'arch_code'    => '<span class="token-comment"># TAVP footprint</span>' . "\n" . '$ tavp phalcon:install' . "\n" . '$ userland vendor: ~5MB' . "\n" . '$ files parsed / request: <span class="token-function">12</span>' . "\n" . '$ P95 latency: <span class="token-function">&lt;5ms</span>',
];
upsert($pdo, 'performance', slugify($performanceData['hero_title']), $performanceData);

// ─── 4. DOCUMENTATION PAGE ──────────────────────────────────────────────────
echo "\n[documentation]\n";
$documentationData = [
    'hero_title'        => 'Introduction to the TAVP Stack',
    'intro'             => 'TAVP is a lean, high-performance stack for modern web applications. It pairs the speed of C-extension PHP with utility-first CSS and lightweight reactive JS — thin by default, powerful when you need it.',
    'core_heading'      => 'Core Components',
    'philosophy_heading' => "The 'Lean' Philosophy",
    'runtimes_badge'    => 'Four runtimes, one codebase',
    'runtimes_title'    => 'Pick the road that fits your deploy.',
    'runtimes_desc'     => 'PHP-FPM for shared hosting, Coil (Swoole) and Relay (RoadRunner) for high traffic, Weave (PHP Fibers) for parallel I/O — the same app, no rewrite.',
    'license_title'     => 'Open Source License',
    'license_desc'      => 'TAVP is released under the MIT License — free for personal and commercial use. Fork it, modify it, and contribute back to the ecosystem.',
    'license_btn1_label' => 'View on GitHub',
    'license_btn1_url'   => 'https://github.com/tavp-stack',
    'license_btn2_label' => 'Read the Docs',
    'license_btn2_url'   => 'https://docs.tavp.web.id/index.html',
];
upsert($pdo, 'documentation', slugify($documentationData['hero_title']), $documentationData);

// ─── 5. CONTACT PAGE ────────────────────────────────────────────────────────
echo "\n[contact]\n";
$contactData = [
    'page_title'    => 'Contact',
    'intro'         => "Have a question, suggestion, or want to contribute? We'd love to hear from you.",
    'github_title'  => 'GitHub',
    'github_desc'   => 'Open an issue or start a discussion.',
    'github_url'    => 'github.com/tavp-stack',
    'email_title'   => 'Email',
    'email_desc'    => 'For business inquiries or partnerships.',
    'email_address' => 'hello@tavp.web.id',
    'form_button'   => 'Send Message',
];
upsert($pdo, 'contact', slugify($contactData['page_title']), $contactData);

// ─── 6. ERROR 404 PAGE ──────────────────────────────────────────────────────
echo "\n[error_404]\n";
$error404Data = [
    'error_code'  => '404',
    'title'       => 'Page Not Found',
    'message'     => "The page you're looking for doesn't exist or has been moved.",
    'button_home' => 'Go Home',
    'button_back' => 'Back to Safety',
];
upsert($pdo, 'error_404', '404', $error404Data);

// ─── 7. ERROR 500 PAGE ──────────────────────────────────────────────────────
echo "\n[error_500]\n";
$error500Data = [
    'error_code'  => '500',
    'title'       => 'Server Error',
    'message'     => "Something went wrong on our end. We're working to fix it.",
    'button_home' => 'Go Home',
    'button_back' => 'Try Again',
];
upsert($pdo, 'error_500', '500', $error500Data);

// ─── 8. SITE LAYOUT ─────────────────────────────────────────────────────────
echo "\n[site_layout]\n";
$siteLayoutData = [
    'logo_url' => '/assets/logo.png',
    'github_url' => 'https://github.com/tavp-stack',
    'nav_1_text' => 'Docs',
    'nav_1_url'  => '/documentation',
    'nav_2_text' => 'Performance',
    'nav_2_url'  => '/performance',
    'nav_3_text' => 'Get Started',
    'nav_3_url'  => '/get-started',
    'nav_4_text' => 'Blog',
    'nav_4_url'  => '/blog',
    'nav_5_text' => 'Contact',
    'nav_5_url'  => '/contact',
    'footer_resource_1_text' => 'Documentation',
    'footer_resource_1_url'  => 'https://docs.tavp.web.id/index.html',
    'footer_resource_2_text' => 'Benchmarks',
    'footer_resource_2_url'  => '/performance',
    'footer_connect_1_text'  => 'GitHub',
    'footer_connect_1_url'   => 'https://github.com/tavp-stack',
    'footer_copyright'       => '© 2026 TAVP Stack. Released under the MIT License.',
];
upsert($pdo, 'site_layout', 'site-layout', $siteLayoutData);

// ═════════════════════════════════════════════════════════════════════════════
//  SUMMARY
// ═════════════════════════════════════════════════════════════════════════════

echo "\n━━━ Seeding complete ━━━\n\n";

// Verify
$stmt = $pdo->query("SELECT type, slug, status, LENGTH(data) AS data_bytes FROM contents ORDER BY id");
$rows = $stmt->fetchAll();

echo str_pad('TYPE', 16) . str_pad('SLUG', 28) . str_pad('STATUS', 12) . "DATA (bytes)\n";
echo str_repeat('─', 70) . "\n";
foreach ($rows as $r) {
    echo str_pad($r['type'], 16)
       . str_pad($r['slug'], 28)
       . str_pad($r['status'], 12)
       . $r['data_bytes'] . "\n";
}

echo "\n✔ Total records: " . count($rows) . "\n";

// Content types seeded:
// ────────────────────────────────────────────────────────────────────────────
//  1. home          — hero, features, platforms, stats, CTA
//  2. get_started   — installation steps with code blocks, hello world, tips
//  3. performance   — benchmarks, low-end box, architecture with code block
//  4. documentation — philosophy, core components, runtimes, license
//  5. contact       — GitHub, email, form button text
//  6. error_404     — 404 page title, message, buttons
//  7. error_500     — 500 page title, message, buttons
//  8. site_layout   — logo, nav links, footer links, copyright
// ────────────────────────────────────────────────────────────────────────────
