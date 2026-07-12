<?php
require '/app/vendor/autoload.php';
require '/app/bootstrap/app.php';

// Enable error display
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "Testing TaxonomyManager...\n";

try {
    $taxonomy = app()->getService('Tavp\Cms\Taxonomy\TaxonomyManager');
    echo "TaxonomyManager found!\n";
    
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
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
