SELECT COUNT(*) as cnt FROM analytics_page_visits;
SELECT DATE(created_at) as day, COUNT(*) as cnt 
FROM analytics_page_visits 
WHERE DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
GROUP BY day ORDER BY day;
