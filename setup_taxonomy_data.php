<?php
require_once __DIR__ . "/vendor/autoload.php";
$app = require __DIR__ . "/bootstrap/app.php";

$taxonomy = $app->getService(\Tavp\Cms\Taxonomy\TaxonomyManager::class);
$bread = $app->getService(\Tavp\Cms\Bread\BreadManager::class);

if (!$taxonomy) {
    echo "TaxonomyManager service not found!\n";
    exit(1);
}

echo "Setting up taxonomy data...\n\n";

// Create category terms
$categories = ["javascript", "frontend", "php", "backend", "css"];
foreach ($categories as $cat) {
    $term = $taxonomy->findBySlug("category", $cat);
    if (!$term) {
        $taxonomy->create([
            "type" => "category",
            "name" => ucfirst($cat),
            "slug" => $cat
        ]);
        echo "Created category: $cat\n";
    } else {
        echo "Category already exists: $cat\n";
    }
}

// Create tag terms  
$tags = ["alpinejs", "javascript", "reactivity", "lightweight", "phalcon", "php", "performance", "framework", "tailwindcss", "css", "utility-first"];
foreach ($tags as $tag) {
    $term = $taxonomy->findBySlug("tag", $tag);
    if (!$term) {
        $taxonomy->create([
            "type" => "tag",
            "name" => $tag,
            "slug" => $tag
        ]);
        echo "Created tag: $tag\n";
    } else {
        echo "Tag already exists: $tag\n";
    }
}

// Get all published posts and assign categories/tags
$posts = $bread->browse("post", ["status" => "published"]);
echo "\nFound " . count($posts) . " published posts\n\n";

foreach ($posts as $post) {
    $postId = $post["id"];
    $title = strtolower($post["title"] ?? "");
    
    echo "Post: " . ($post["title"] ?? "") . " (ID: $postId)\n";
    
    // Assign categories based on title/content
    if (strpos($title, "alpine") !== false) {
        $cat = $taxonomy->findBySlug("category", "frontend");
        if ($cat) { 
            $taxonomy->attach($postId, $cat->id, "post"); 
            echo "  - Attached to category: frontend\n"; 
        }
    }
    
    if (strpos($title, "tailwind") !== false) {
        $cat = $taxonomy->findBySlug("category", "frontend");
        if ($cat) { 
            $taxonomy->attach($postId, $cat->id, "post"); 
            echo "  - Attached to category: frontend\n"; 
        }
    }
    
    echo "\n";
}

echo "Done!\n";
