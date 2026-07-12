<?php
require '/app/vendor/autoload.php';
require '/app/bootstrap/app.php';

$taxonomy = app()->getService(Tavp\Cms\Taxonomy\TaxonomyManager::class);
$term = $taxonomy->findBySlug("category", "uncategorized");

if (!$term) {
    echo "Term not found\n";
    exit(1);
}

echo "Term found: " . $term->name . "\n";
echo "Term ID: " . $term->id . "\n";

$postIds = $taxonomy->contentIdsWithTerm($term->id, "post");
echo "Post IDs: " . print_r($postIds, true) . "\n";
