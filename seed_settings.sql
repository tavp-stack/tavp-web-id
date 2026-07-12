-- Update settings to match SettingsController schema
INSERT INTO settings (`group`, `key`, value, type, created_at, updated_at) VALUES
('general', 'site_name', 'TAVP Stack', 'text', NOW(), NOW()),
('general', 'tagline', 'The Lean, Mean, PHP Machine', 'text', NOW(), NOW()),
('general', 'description', 'Tailwind + Alpine + Volt + Phalcon. A curated PHP tech stack — thin, light, and fast.', 'text', NOW(), NOW()),
('general', 'timezone', 'Asia/Jakarta', 'text', NOW(), NOW()),
('contact', 'email', 'hello@tavp.web.id', 'text', NOW(), NOW()),
('contact', 'phone', '', 'text', NOW(), NOW()),
('contact', 'address', '', 'text', NOW(), NOW()),
('social', 'twitter', '', 'text', NOW(), NOW()),
('social', 'github', 'https://github.com/tavp-stack', 'text', NOW(), NOW()),
('social', 'linkedin', '', 'text', NOW(), NOW()),
('social', 'instagram', '', 'text', NOW(), NOW()),
('seo', 'meta_keywords', 'TAVP, PHP, Phalcon, Tailwind, Alpine, Volt', 'text', NOW(), NOW()),
('seo', 'google_analytics_id', '', 'text', NOW(), NOW()),
('footer', 'copyright', '© 2026 TAVP Stack. Released under the MIT License.', 'text', NOW(), NOW()),
('footer', 'footer_note', 'A curated PHP tech stack for modern engineers.', 'text', NOW(), NOW())
ON DUPLICATE KEY UPDATE value = VALUES(value), updated_at = NOW();

-- Show results
SELECT * FROM settings ORDER BY `group`, `key`;
