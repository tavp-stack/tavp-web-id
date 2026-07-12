<?php
// Simple test to check if TaxonomyManager is registered

require_once '/app/vendor/autoload.php';

// Manually load bootstrap
require_once '/app/bootstrap/app.php';

echo "After bootstrap, checking app variable...\n";

// Check if $app exists
if (isset($app)) {
    echo "App variable exists\n";
    
    // Try to register TaxonomyManager manually
    require_once '/app/vendor/tavp/cms/src/Taxonomy/DatabaseTaxonomyFactory.php';
    
    $app->bind('Tavp\Cms\Taxonomy\TaxonomyManager', function () use ($app) {
        return \Tavp\Cms\Taxonomy\buildDatabaseTaxonomy($app->getService('db'));
    });
    
    echo "TaxonomyManager registered manually\n";
    
    // Now test
    try {
        $taxonomy = $app->getService('Tavp\Cms\Taxonomy\TaxonomyManager');
        echo "TaxonomyManager service found!\n";
        
        $term = $taxonomy->findBySlug("category", "uncategorized");
        if ($term) {
            echo "Term found: " . $term->name . "\n";
        } else {
            echo "Term not found\n";
        }
    } catch (\Throwable $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "App variable NOT available\n";
}
