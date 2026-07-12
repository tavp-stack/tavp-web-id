<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../bootstrap/app.php';
$c = new Tavp\Cms\CmsServiceProvider();
$c->register(); $c->boot();

$db = app('db');
$rows = $db->fetchAll(
    "SELECT id, data FROM contents WHERE type='post' AND id=10 LIMIT 1",
    \PDO::FETCH_ASSOC
);
if (empty($rows)) { echo "Post #10 not found\n"; exit; }

$data = json_decode($rows[0]['data'], true);
$body = $data['body'] ?? '';
echo "Post #10 body (first 500 chars):\n";
echo substr($body, 0, 500) . "\n\n";
echo "Contains HTML tags: " . (preg_match('/<[a-z][a-z0-9]*[\s>]/i', $body) ? 'YES' : 'NO') . "\n";
echo "Starts with <p or <h: " . (preg_match('/^<[ph]/i', $body) ? 'YES' : 'NO') . "\n";
