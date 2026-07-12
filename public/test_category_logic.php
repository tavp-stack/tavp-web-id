<?php
require_once '/app/vendor/autoload.php';
require_once '/app/bootstrap/app.php';

use Tavp\Cms\Taxonomy\TaxonomyManager;
use Tavp\Cms\Bread\BreadManager;

// Register TaxonomyManager manually
require_once '/app/vendor/tavp/cms/src/Taxonomy/DatabaseTaxonomyFactory.php';
$app->bind(TaxonomyManager::class, function () use ($app) {
    return \Tavp\Cms\Taxonomy\buildDatabaseTaxonomy($app->getService('db'));
});

echo "Testing category route logic...\n";

$slug = "uncategorized";
$taxonomy = $app->getService(TaxonomyManager::class);
$term = $taxonomy->findBySlug("category", $slug);

if (!$term) {
    echo "Term not found\n";
    exit(1);
}

echo "Term found: " . $term->name . "\n";
echo "Term ID: " . $term->id . "\n";

$postIds = $taxonomy->contentIdsWithTerm($term->id, "post");
echo "Post IDs: " . json_encode($postIds) . "\n";

$bread = $app->getService(BreadManager::class);
echo "BreadManager found\n";

$posts = [];
foreach ($postIds as $postId) {
    echo "Reading post ID: $postId\n";
    $post = $bread->read("post", $postId);
    if ($post && ($post["status"] ?? "draft") === "published") {
        $posts[] = $post;
        echo "Post found: " . ($post["title"] ?? "no title") . "\n";
    } else {
        echo "Post not published or not found\n";
    }
}

echo "\nTotal posts: " . count($posts) . "\n";
echo "Done!\n";
