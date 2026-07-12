-- Seed taxonomy terms
INSERT IGNORE INTO taxonomy_terms (type, name, slug, created_at, updated_at) VALUES
('category', 'Uncategorized', 'uncategorized', NOW(), NOW()),
('tag', 'TAVP', 'tavp', NOW(), NOW()),
('tag', 'PHP', 'php', NOW(), NOW()),
('tag', 'Performance', 'performance', NOW(), NOW());

-- Show results
SELECT * FROM taxonomy_terms;
