-- Create content_taxonomy table
CREATE TABLE IF NOT EXISTS content_taxonomy (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content_id INT NOT NULL,
    content_type VARCHAR(50) NOT NULL,
    term_id INT NOT NULL,
    term_type VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_content (content_id, content_type),
    INDEX idx_term (term_id, term_type)
);

-- Link all published posts to "Uncategorized" category (term_id = 1)
INSERT IGNORE INTO content_taxonomy (content_id, content_type, term_id, term_type)
SELECT id, 'post', 1, 'category'
FROM contents
WHERE type = 'post' AND status = 'published';

-- Show results
SELECT * FROM content_taxonomy;
SELECT COUNT(*) as post_count FROM contents WHERE type = 'post' AND status = 'published';
