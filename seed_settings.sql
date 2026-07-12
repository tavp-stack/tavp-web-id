-- Seed site settings
INSERT IGNORE INTO settings (`group`, `key`, value, type, created_at, updated_at) VALUES
('site', 'name', 'TAVP Stack', 'text', NOW(), NOW()),
('site', 'default_title', 'TAVP Stack — The Lean, Mean, PHP Machine', 'text', NOW(), NOW()),
('site', 'default_description', 'Tailwind + Alpine + Volt + Phalcon. A curated PHP tech stack — thin, light, and fast.', 'text', NOW(), NOW()),
('site', 'copyright', '© 2026 TAVP Stack. Released under the MIT License. A curated PHP tech stack for modern engineers.', 'text', NOW(), NOW()),
('site', 'logo_url', '/assets/logo.png', 'text', NOW(), NOW()),
('contact', 'email', 'hello@tavp.web.id', 'text', NOW(), NOW()),
('contact', 'github_url', 'https://github.com/tavp-stack', 'text', NOW(), NOW()),
('analytics', 'endpoint', '/api/analytics', 'text', NOW(), NOW()),
('analytics', 'session_recording', '0', 'boolean', NOW(), NOW());

-- Show seeded settings
SELECT * FROM settings;
