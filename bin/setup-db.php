<?php
/**
 * TAVP CMS — Full Database Setup Script
 * 
 * Creates all tables, seeds data, fixes permissions.
 * Run after every rebuild: tavpbox php /var/www/html/bin/setup-db.php
 */

declare(strict_types=1);

echo "━━━ TAVP CMS Database Setup ━━━\n\n";

// ─── 0. Create symlinks for volume mount compatibility ───────────
echo "[0/5] Creating symlinks...\n";
$symlinks = [
    '/var/vendor' => '/var/www/html/vendor',
    '/var/bootstrap' => '/var/www/html/bootstrap',
    '/var/config' => '/var/www/html/config',
    '/var/routes' => '/var/www/html/routes',
    '/var/themes' => '/var/www/html/themes',
    '/var/app' => '/var/www/html/app',
    '/var/storage' => '/var/www/html/storage',
];
foreach ($symlinks as $link => $target) {
    if (!file_exists($link)) {
        symlink($target, $link);
        echo "  ✓ {$link} → {$target}\n";
    } else {
        echo "  · {$link} already exists\n";
    }
}
echo "\n";

$pdo = new PDO('mysql:host=127.0.0.1;dbname=tavp', 'tavp', 'tavp');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ─── 1. Create Tables ───────────────────────────────────────────
echo "[1/5] Creating tables...\n";

$tables = [
    "CREATE TABLE IF NOT EXISTS contents (
        id BIGINT AUTO_INCREMENT PRIMARY KEY,
        type VARCHAR(64) NOT NULL,
        slug VARCHAR(191) NOT NULL,
        status VARCHAR(32) NOT NULL DEFAULT 'draft',
        data TEXT NULL,
        author_id BIGINT NULL,
        published_at TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_type_slug (type, slug)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    "CREATE TABLE IF NOT EXISTS content_types (id BIGINT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(64) NOT NULL UNIQUE, config TEXT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS content_taxonomy (id BIGINT AUTO_INCREMENT PRIMARY KEY, content_id BIGINT NOT NULL, term_id BIGINT NOT NULL, content_type VARCHAR(64) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, UNIQUE KEY unique_content_term (content_id, term_id, content_type)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS taxonomy_terms (id BIGINT AUTO_INCREMENT PRIMARY KEY, taxonomy VARCHAR(64) NOT NULL DEFAULT 'category', type VARCHAR(64) NOT NULL DEFAULT 'category', name VARCHAR(191) NOT NULL, slug VARCHAR(191) NOT NULL, parent_id BIGINT NULL, description TEXT NULL, color VARCHAR(16) NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, UNIQUE KEY unique_tax_slug (taxonomy, slug)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS content_revisions (id BIGINT AUTO_INCREMENT PRIMARY KEY, content_type VARCHAR(64) NOT NULL, content_id BIGINT NOT NULL, data TEXT NULL, author_email VARCHAR(191) NULL, note VARCHAR(255) NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS media (id BIGINT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, path VARCHAR(500) NOT NULL, mime_type VARCHAR(100) NOT NULL, size BIGINT NOT NULL DEFAULT 0, alt_text VARCHAR(255) NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS menus (id BIGINT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL UNIQUE, location VARCHAR(100) NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS menu_items (id BIGINT AUTO_INCREMENT PRIMARY KEY, menu_id BIGINT NOT NULL, parent_id BIGINT NULL, label VARCHAR(255) NOT NULL, url VARCHAR(500) NOT NULL, sort_order INT DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS settings (id BIGINT AUTO_INCREMENT PRIMARY KEY, `group` VARCHAR(64) NOT NULL DEFAULT 'general', `key` VARCHAR(128) NOT NULL, value TEXT NULL, type VARCHAR(32) NOT NULL DEFAULT 'text', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, UNIQUE KEY unique_group_key (`group`, `key`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS users (id BIGINT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(128) NULL, email VARCHAR(191) NOT NULL UNIQUE, phone VARCHAR(32) NULL, password VARCHAR(255) NULL, email_verified_at TIMESTAMP NULL, remember_token VARCHAR(100) NULL, bio TEXT NULL, social_github VARCHAR(255) NULL, social_twitter VARCHAR(255) NULL, social_linkedin VARCHAR(255) NULL, social_instagram VARCHAR(255) NULL, social_website VARCHAR(255) NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS roles (id BIGINT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(64) NOT NULL UNIQUE, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS user_roles (id BIGINT AUTO_INCREMENT PRIMARY KEY, user_id BIGINT NOT NULL, role_id BIGINT NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, UNIQUE KEY unique_user_role (user_id, role_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS permissions (id BIGINT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(128) NOT NULL UNIQUE, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS role_permissions (id BIGINT AUTO_INCREMENT PRIMARY KEY, role_id BIGINT NOT NULL, permission_id BIGINT NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, UNIQUE KEY unique_role_perm (role_id, permission_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS otp_codes (id BIGINT AUTO_INCREMENT PRIMARY KEY, email VARCHAR(191) NOT NULL, code VARCHAR(10) NOT NULL, expires_at TIMESTAMP NOT NULL, used TINYINT(1) DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS user_sessions (id BIGINT AUTO_INCREMENT PRIMARY KEY, user_id BIGINT NULL, token VARCHAR(255) NOT NULL, ip_address VARCHAR(45) NULL, user_agent VARCHAR(500) NULL, last_activity TIMESTAMP NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS api_tokens (id BIGINT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, token VARCHAR(255) NOT NULL UNIQUE, permissions TEXT NULL, last_used_at TIMESTAMP NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS webhooks (id BIGINT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, url VARCHAR(500) NOT NULL, events TEXT NULL, secret VARCHAR(255) NULL, active TINYINT(1) DEFAULT 1, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS webhook_deliveries (id BIGINT AUTO_INCREMENT PRIMARY KEY, webhook_id BIGINT NOT NULL, event VARCHAR(100) NOT NULL, payload TEXT NULL, response_code INT NULL, response_body TEXT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS contact_messages (id BIGINT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(128) NOT NULL, email VARCHAR(191) NOT NULL, subject VARCHAR(255) NULL, message TEXT NOT NULL, ip_address VARCHAR(45) NULL, is_read TINYINT(1) DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS analytics_page_visits (id BIGINT AUTO_INCREMENT PRIMARY KEY, path VARCHAR(500) NOT NULL, title VARCHAR(500) NULL, ip_address VARCHAR(45) NULL, user_agent VARCHAR(500) NULL, referrer VARCHAR(500) NULL, country VARCHAR(100) NULL, city VARCHAR(100) NULL, device VARCHAR(50) NOT NULL, browser VARCHAR(50) NOT NULL, os VARCHAR(50) NOT NULL, platform VARCHAR(50) NOT NULL, session_id VARCHAR(100) NULL, duration INT DEFAULT 0, is_bounce TINYINT(1) DEFAULT 0, is_bot TINYINT(1) DEFAULT 0, metadata JSON NULL, visited_at TIMESTAMP NULL, created_at TIMESTAMP NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS analytics_events (id BIGINT AUTO_INCREMENT PRIMARY KEY, event_name VARCHAR(100) NOT NULL, event_category VARCHAR(100) NULL, event_label VARCHAR(255) NULL, path VARCHAR(500) NULL, ip_address VARCHAR(45) NULL, session_id VARCHAR(100) NULL, platform VARCHAR(50) NOT NULL, metadata JSON NULL, fraud_score DECIMAL(5,3) DEFAULT 0, is_suspicious TINYINT(1) DEFAULT 0, created_at TIMESTAMP NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS analytics_sessions (id BIGINT AUTO_INCREMENT PRIMARY KEY, session_id VARCHAR(100) NOT NULL, ip_address VARCHAR(45) NOT NULL, user_agent VARCHAR(500) NULL, device VARCHAR(50) NOT NULL, browser VARCHAR(50) NOT NULL, os VARCHAR(50) NOT NULL, platform VARCHAR(50) NOT NULL, country VARCHAR(100) NULL, city VARCHAR(100) NULL, referrer VARCHAR(500) NULL, landing_page VARCHAR(500) NOT NULL, page_views INT DEFAULT 1, duration INT DEFAULT 0, is_bounce TINYINT(1) DEFAULT 1, is_bot TINYINT(1) DEFAULT 0, started_at TIMESTAMP NULL, last_activity_at TIMESTAMP NULL, created_at TIMESTAMP NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS analytics_fraud_events (id BIGINT AUTO_INCREMENT PRIMARY KEY, session_id VARCHAR(100) NULL, ip_address VARCHAR(45) NOT NULL, event_type VARCHAR(50) NOT NULL, rule_name VARCHAR(200) NOT NULL, score DECIMAL(5,3) NOT NULL, details JSON NULL, action_taken VARCHAR(50) NOT NULL, resolved_at TIMESTAMP NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    // SEO tables
    "CREATE TABLE IF NOT EXISTS seo_meta (id BIGINT AUTO_INCREMENT PRIMARY KEY, content_type VARCHAR(64) NOT NULL, content_id BIGINT NOT NULL, meta_title VARCHAR(255) NULL, meta_description TEXT NULL, og_title VARCHAR(255) NULL, og_description TEXT NULL, og_image VARCHAR(500) NULL, twitter_title VARCHAR(255) NULL, twitter_description TEXT NULL, twitter_image VARCHAR(500) NULL, canonical_url VARCHAR(500) NULL, robots VARCHAR(100) NULL, focus_keyword VARCHAR(255) NULL, seo_score INT DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, UNIQUE KEY unique_content (content_type, content_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS redirects (id BIGINT AUTO_INCREMENT PRIMARY KEY, from_url VARCHAR(500) NOT NULL, to_url VARCHAR(500) NOT NULL, status_code INT DEFAULT 301, hits INT DEFAULT 0, active TINYINT(1) DEFAULT 1, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, UNIQUE KEY unique_from (from_url(191))) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS outbound_links (id BIGINT AUTO_INCREMENT PRIMARY KEY, content_type VARCHAR(64) NOT NULL, content_id BIGINT NOT NULL, url VARCHAR(500) NOT NULL, is_broken TINYINT(1) DEFAULT 0, last_checked_at TIMESTAMP NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
];

foreach ($tables as $sql) {
    $pdo->exec($sql);
    if (preg_match('/CREATE TABLE IF NOT EXISTS (\w+)/', $sql, $m)) {
        echo "  ✓ {$m[1]}\n";
    }
}

// ─── 2. Seed Settings ───────────────────────────────────────────
echo "\n[2/5] Seeding settings...\n";

$settings = [
    ['general', 'site_name', 'TAVP Tech Stack'],
    ['general', 'tagline', 'The Lean, Mean, PHP Machine'],
    ['general', 'description', 'Tailwind + Alpine + Volt + Phalcon. A curated PHP tech stack — thin, light, and fast.'],
    ['general', 'timezone', 'Asia/Jakarta'],
    ['admin', 'route_prefix', 'admin'],
    ['contact', 'email', 'hello@tavp.web.id'],
    ['contact', 'phone', ''],
    ['contact', 'address', ''],
    ['social', 'twitter', ''],
    ['social', 'github', 'https://github.com/tavp-stack'],
    ['social', 'linkedin', ''],
    ['social', 'instagram', ''],
    ['seo', 'meta_keywords', ''],
    ['seo', 'google_analytics_id', ''],
    ['footer', 'copyright', '© 2026 TAVP Stack. Released under the MIT License.'],
    ['footer', 'footer_note', ''],
];

$stmt = $pdo->prepare("INSERT INTO settings (`group`, `key`, value, type, created_at, updated_at) VALUES (?, ?, ?, 'text', NOW(), NOW()) ON DUPLICATE KEY UPDATE value = VALUES(value), updated_at = NOW()");
foreach ($settings as [$group, $key, $value]) {
    $stmt->execute([$group, $key, $value]);
}
echo "  ✓ Settings seeded (" . count($settings) . " items)\n";

// ─── 3. Seed Users ──────────────────────────────────────────────
echo "\n[3/5] Seeding users...\n";

$users = [
    ['name' => 'Admin TAVP', 'email' => 'admin@tavp.web.id', 'bio' => 'TAVP Stack administrator'],
    ['name' => 'Editor TAVP', 'email' => 'editor@tavp.web.id', 'bio' => 'Content editor'],
];

$stmt = $pdo->prepare("INSERT INTO users (name, email, bio, created_at, updated_at) VALUES (:name, :email, :bio, NOW(), NOW()) ON DUPLICATE KEY UPDATE name = VALUES(name), bio = VALUES(bio), updated_at = NOW()");
foreach ($users as $u) {
    $stmt->execute($u);
    echo "  ✓ {$u['email']}\n";
}

// ─── 4. Seed Content ────────────────────────────────────────────
echo "\n[4/5] Seeding content...\n";

// Site Layout
$layoutData = json_encode([
    'logo_url' => '/assets/logo.png',
    'github_url' => 'https://github.com/tavp-stack',
    'nav_1_text' => 'Docs', 'nav_1_url' => '/documentation',
    'nav_2_text' => 'Performance', 'nav_2_url' => '/performance',
    'nav_3_text' => 'Get Started', 'nav_3_url' => '/get-started',
    'nav_4_text' => 'Blog', 'nav_4_url' => '/blog',
    'nav_5_text' => 'Contact', 'nav_5_url' => '/contact',
    'footer_resource_1_text' => 'Documentation', 'footer_resource_1_url' => 'https://docs.tavp.web.id/index.html',
    'footer_resource_2_text' => 'Benchmarks', 'footer_resource_2_url' => '/performance',
    'footer_connect_1_text' => 'GitHub', 'footer_connect_1_url' => 'https://github.com/tavp-stack',
]);
$contentStmt = $pdo->prepare("INSERT INTO contents (type, slug, status, data, created_at, updated_at) VALUES (?, ?, 'published', ?, NOW(), NOW()) ON DUPLICATE KEY UPDATE data = VALUES(data), updated_at = NOW()");
$contentStmt->execute(['site_layout', 'site-layout', $layoutData]);
echo "  ✓ site_layout\n";

// Error pages
$contentStmt->execute(['error_404', '404', json_encode(['title' => '404', 'subtitle' => 'Page Not Found', 'description' => "The page you're looking for doesn't exist or has been moved.", 'btn_home_text' => 'Go Home', 'btn_back_text' => 'Back to Safety'])]);
echo "  ✓ error_404\n";

$contentStmt->execute(['error_500', '500', json_encode(['title' => '500', 'subtitle' => 'Server Error', 'description' => "Something went wrong on our end. We're working to fix it.", 'btn_home_text' => 'Go Home', 'btn_retry_text' => 'Try Again'])]);
echo "  ✓ error_500\n";

// ─── 5. Fix Permissions ─────────────────────────────────────────
echo "\n[5/5] Fixing permissions...\n";

$dirs = [
    '/var/www/html/public/uploads' => 0775,
    '/tmp/storage' => 0777,
    '/tmp/storage/compiled/volt' => 0777,
    '/tmp/storage/cms/cache' => 0777,
    '/tmp/storage/cms/revisions' => 0777,
];
foreach ($dirs as $dir => $mode) {
    if (!is_dir($dir)) {
        mkdir($dir, $mode, true);
    }
    chmod($dir, $mode);
}
echo "  ✓ Storage & upload permissions set\n";

echo "\n━━━ Setup complete! ━━━\n";
