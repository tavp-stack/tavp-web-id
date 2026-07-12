-- Seed admin user
INSERT IGNORE INTO users (name, email, role, created_at, updated_at) VALUES
('Jeremy Cheng', 'admin@tavp.web.id', 'admin', NOW(), NOW()),
('Editor', 'editor@tavp.web.id', 'editor', NOW(), NOW());

-- Seed home content
INSERT IGNORE INTO contents (type, slug, data, status, created_at, updated_at) VALUES
('home', 'home', '{"hero_badge":"Stack v1.0 • Stable","hero_title":"The Lean, Mean, PHP Machine.","hero_subtitle":"Build blazingly fast systems with Tailwind, Alpine, Volt, and Phalcon. Thin, light, and engineered for the sub-millisecond era.","cta_primary":"Get Started","cta_secondary":"View Benchmarks","feature_1_title":"Lean Architecture","feature_1_desc":"A C-extension core runs your code close to the metal, while a Laravel-style ergonomic layer keeps development a joy.","feature_2_title":"Thin Core","feature_2_desc":"Modular by design. Load exactly what your application needs — nothing more.","feature_3_title":"High Throughput","feature_3_desc":"Thousands of requests per second on a modest 2-core VPS. Up to 12,000+ with the Coil runtime.","feature_4_title":"Low RAM Footprint","feature_4_desc":"Peak performance in under 15MB per worker — efficient enough for edge, containers, and modest boxes alike.","platforms_title":"Runs Where You Do","platforms_subtitle":"From the $5/mo VPS you already own to Docker and managed panels — TAVP feels right at home everywhere.","stat_1_label":"Response Time","stat_1_value":"<5ms","stat_1_desc":"P95 latency on a 2-core VPS with PHP-FPM.","stat_2_label":"Throughput","stat_2_value":"12,000+","stat_2_desc":"Requests per second with the Coil (Swoole) runtime.","stat_3_label":"Memory","stat_3_value":"<15MB","stat_3_desc":"Per worker, at peak performance.","cta_title":"Less config, more craft.","cta_highlight":"Start building your product.","cta_final_1_text":"Get Started","cta_final_2_text":"Documentation"}', 'published', NOW(), NOW());

-- Seed single pages
INSERT IGNORE INTO contents (type, slug, data, status, created_at, updated_at) VALUES
('page', 'home', '{"title":"TAVP Stack","body":"Welcome to TAVP Stack.","status":"published"}', 'published', NOW(), NOW()),
('contact', 'contact', '{"page_title":"Contact","email":"hello@tavp.web.id","phone":"+62 xxx","address":"Indonesia","github":"https://github.com/tavp-stack"}', 'published', NOW(), NOW()),
('get_started', 'get-started', '{"page_title":"Get Started","step1_title":"Install TAVP","step1_desc":"Clone the repo and run composer install.","step2_title":"Configure","step2_desc":"Copy .env.example to .env and configure your database.","step3_title":"Run","step3_desc":"Start the dev server with lando start.","hello_desc":"Volt compiles to plain PHP for maximum performance.","tips_desc":"Running on a $5/mo VPS? Enable OPcache and disable view stats for best performance."}', 'published', NOW(), NOW()),
('performance', 'performance', '{"page_title":"Performance","intro_title":"Blazing Fast","intro_desc":"TAVP Stack is engineered for the sub-millisecond era.","benchmark_title":"Benchmarks","benchmark_desc":"Real-world performance on modest hardware."}', 'published', NOW(), NOW()),
('documentation', 'documentation', '{"page_title":"Documentation","intro_title":"Documentation","intro_desc":"Everything you need to build with TAVP Stack.","guide_title":"Guides","guide_desc":"Step-by-step tutorials to get you started.","api_title":"API Reference","api_desc":"Complete API documentation for all components."}', 'published', NOW(), NOW());

-- Seed posts
INSERT IGNORE INTO contents (type, slug, data, status, published_at, created_at, updated_at) VALUES
('post', 'tavp-stack-php-tapi-cepat-pengantar-lengkap-ekosistem-arsitekturnya', '{"title":"TAVP Stack: PHP Tapi Cepat — Pengantar Lengkap Ekosistem & Arsitekturnya","excerpt":"TAVP Stack combines Tailwind, Alpine, Volt, and Phalcon for blazingly fast PHP applications.","body":"## Apa itu TAVP Stack?\\n\\nTAVP Stack adalah framework modern yang menggabungkan empat teknologi:\\n\\n- **Tailwind CSS** — utility-first CSS framework\\n- **Alpine.js** — JavaScript ringan untuk interaktivitas\\n- **Volt** — template engine dari Phalcon\\n- **Phalcon** — PHP C-extension framework\\n\\n## Mengapa TAVP?\\n\\nPerforma sub-milidetik dengan developer experience yang menyenangkan.","status":"published","author":"Jeremy Cheng"}', 'published', '2026-07-12 04:23:00', NOW(), NOW());

-- Seed menus
INSERT IGNORE INTO menus (name, slug, created_at, updated_at) VALUES ('Main Menu', 'main-menu', NOW(), NOW());

-- Seed analytics data
INSERT IGNORE INTO analytics_page_visits (path, session_id, ip_address, device, browser, created_at) VALUES
('/', 'sess_001', '127.0.0.1', 'Desktop', 'Chrome', NOW() - INTERVAL 1 DAY),
('/', 'sess_002', '127.0.0.1', 'Desktop', 'Chrome', NOW() - INTERVAL 1 DAY),
('/blog', 'sess_003', '127.0.0.1', 'Mobile', 'Safari', NOW() - INTERVAL 1 DAY),
('/blog', 'sess_004', '127.0.0.1', 'Desktop', 'Firefox', NOW() - INTERVAL 1 DAY),
('/contact', 'sess_005', '127.0.0.1', 'Desktop', 'Chrome', NOW() - INTERVAL 1 DAY),
('/', 'sess_006', '127.0.0.1', 'Mobile', 'Chrome', NOW()),
('/performance', 'sess_007', '127.0.0.1', 'Desktop', 'Chrome', NOW());
