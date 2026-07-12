<?php
require '/app/vendor/autoload.php';

// Enable error display
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '/app/bootstrap/app.php';

echo "Bootstrap loaded successfully\n";

// Check if TaxonomyManager service is registered
$app = require '/app/bootstrap/app.php';
echo "App instance created\n";

try {
    $taxonomy = $app->getService('Tavp\Cms\Taxonomy\TaxonomyManager');
    echo "TaxonomyManager service found!\n";
    
    $term = $taxonomy->findBySlug("category", "uncategorized");
    if ($term) {
        echo "Term found: " . $term->name . "\n";
        echo "Term ID: " . $term->id . "\n";
        
        $postIds = $taxonomy->contentIdsWithTerm($term->id, "post");
        echo "Post IDs: " . json_encode($postIds) . "\n";
    } else {
        echo "Term not found\n";
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
