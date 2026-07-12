-- Seed Homepage content
INSERT INTO contents (type, slug, data, status, created_at, updated_at) VALUES
('home', 'home', '{"hero_badge":"Stack v1.0 · Stable","hero_title":"The Lean, Mean, PHP Machine.","hero_subtitle":"Build blazingly fast systems with Tailwind, Alpine, Volt, and Phalcon.","cta_primary":"Get Started","cta_secondary":"View Benchmarks","feature_1_title":"Lean Architecture","feature_1_desc":"A C-extension core runs your code close to the metal.","feature_2_title":"Thin Core","feature_2_desc":"Modular by design. Load exactly what your application needs.","feature_3_title":"High Throughput","feature_3_desc":"Thousands of requests per second on a modest 2-core VPS.","feature_4_title":"Low RAM Footprint","feature_4_desc":"Peak performance in under 15MB per worker.","platforms_title":"Runs Where You Do","platforms_subtitle":"From the $5/mo VPS to Docker, Kubernetes, and beyond.","stat_1_label":"Response Time","stat_1_value":"<5ms","stat_1_desc":"P95 latency on a 2-core VPS.","stat_2_label":"Throughput","stat_2_value":"12,000+","stat_2_desc":"Requests per second with Coil runtime.","stat_3_label":"Memory","stat_3_value":"<15MB","stat_3_desc":"Per worker, at peak performance.","cta_title":"Less config, more craft.","cta_highlight":"Start building your product.","cta_final_1_text":"Get Started","cta_final_2_text":"Documentation"}', 'published', NOW(), NOW())
ON DUPLICATE KEY UPDATE data = VALUES(data), updated_at = NOW();

-- Seed Contact page
INSERT INTO contents (type, slug, data, status, created_at, updated_at) VALUES
('contact', 'contact', '{"page_title":"Contact","intro":"Have a question, suggestion, or want to contribute?","github_title":"GitHub","github_desc":"Open an issue or start a discussion.","github_url":"github.com/tavp-stack","email_title":"Email","email_desc":"For business inquiries or partnerships.","email_address":"hello@tavp.web.id","form_button":"Send Message"}', 'published', NOW(), NOW())
ON DUPLICATE KEY UPDATE data = VALUES(data), updated_at = NOW();

-- Seed Get Started page
INSERT INTO contents (type, slug, data, status, created_at, updated_at) VALUES
('get_started', 'get-started', '{"badge":"STABLE RELEASE V1.0","page_title":"Installation Guide","intro":"Set up the TAVP stack on your machine in under 5 minutes.","step1_title":"Install the Phalcon Extension","step1_desc":"Phalcon is a C-extension for PHP. Install it via pecl.","step2_title":"Create Your Project","step2_desc":"Bootstrap a new app with the TAVP installer.","step3_title":"TAVPblocks UI Components","step3_desc":"No need to install Tailwind from scratch. 60+ ready-to-use components.","hello_title":"Hello World in Volt","hello_desc":"Volt compiles to plain PHP with elegant syntax.","tips_title":"VPS Optimization","tips_desc":"Running on a $5/mo droplet? Tips to squeeze performance.","help_title":"Need Help?","help_desc":"Read the full documentation or open a GitHub issue.","help_url":"https://docs.tavp.web.id/index.html","help_button":"Open the Docs"}', 'published', NOW(), NOW())
ON DUPLICATE KEY UPDATE data = VALUES(data), updated_at = NOW();

-- Seed Performance page
INSERT INTO contents (type, slug, data, status, created_at, updated_at) VALUES
('performance', 'performance', '{"hero_title":"Built for Bare Metal Speed.","hero_intro":"There are many roads to building great software. We picked the fast one.","cta1_label":"Explore Runtimes","cta1_url":"https://docs.tavp.web.id/runtimes/overview.html","cta2_label":"Methodology","cta2_url":"https://docs.tavp.web.id/reference/performance.html","lowend_title":"The Low-End Box Test","lowend_desc":"We ran TAVP on a modest VPS and the results speak for themselves.","why_title":"Why Its Fast","arch_badge":"Architecture Focus","arch_title":"Leaner Internals, Faster Deployment.","arch_intro":"With Phalcon shared-memory model, your application boots once and stays resident."}', 'published', NOW(), NOW())
ON DUPLICATE KEY UPDATE data = VALUES(data), updated_at = NOW();

-- Seed Documentation page
INSERT INTO contents (type, slug, data, status, created_at, updated_at) VALUES
('documentation', 'documentation', '{"hero_title":"Introduction to the TAVP Stack","intro":"TAVP is a lean, high-performance stack for building modern PHP applications.","core_heading":"Core Components","philosophy_heading":"The Lean Philosophy","runtimes_badge":"Four runtimes, one codebase","runtimes_title":"Pick the road that fits your deploy.","runtimes_desc":"PHP-FPM for shared hosting, Coil for Swoole, Octane for Laravel, FrankenPHP for containers.","license_title":"Open Source License","license_desc":"TAVP is released under the MIT License.","license_btn1_label":"View on GitHub","license_btn1_url":"https://github.com/tavp-stack","license_btn2_label":"Read the Docs","license_btn2_url":"https://docs.tavp.web.id/index.html"}', 'published', NOW(), NOW())
ON DUPLICATE KEY UPDATE data = VALUES(data), updated_at = NOW();

-- Show seeded content
SELECT id, type, slug, status FROM contents ORDER BY type;
