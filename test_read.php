<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

use Tavp\Cms\Bread\BreadManager;

// Bootstrap the app
$app = require __DIR__ . '/bootstrap/app.php';

// Get BreadManager
$bread = $app->getService(BreadManager::class);

// Test reading performance page with ID 5 (as string)
echo "Testing BreadManager::read('performance', '5')...\n";
$record = $bread->read('performance', '5');

if ($record === null) {
    echo "ERROR: Record not found!\n";
} else {
    echo "SUCCESS! Record found:\n";
    echo "ID: " . ($record['id'] ?? 'N/A') . "\n";
    echo "Title: " . ($record['data']['hero_title'] ?? 'N/A') . "\n";
}

// Test reading with numeric ID
echo "\nTesting BreadManager::read('performance', 5)...\n";
$record2 = $bread->read('performance', 5);

if ($record2 === null) {
    echo "ERROR: Record not found!\n";
} else {
    echo "SUCCESS! Record found:\n";
    echo "ID: " . ($record2['id'] ?? 'N/A') . "\n";
    echo "Title: " . ($record2['data']['hero_title'] ?? 'N/A') . "\n";
}
