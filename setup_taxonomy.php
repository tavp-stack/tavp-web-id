<?php
require_once __DIR__ . "/vendor/autoload.php";
$app = require __DIR__ . "/bootstrap/app.php";

$taxonomy = app()->getService(\Tavp\Cms\Taxonomy\TaxonomyManager::class);
$bread = app()->getService(\Tavp\Cms\Bread\BreadManager::class);

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
$tags = ["alpinejs", "javascript", "reactivity", "lightweight", "phalcon", "php", "performance", "framework", "tailwindcss", "css", "utility-first", "frontend"];
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

// Get all posts
$posts = $bread->browse("post", ["status" => "published"]);
echo "\nFound " . count($posts) . " published posts\n";

// Assign categories and tags to posts based on their content
foreach ($posts as $post) {
    $postId = $post["id"];
    $title = strtolower($post["title"] ?? "");
    
    echo "\nPost: " . ($post["title"] ?? "") . " (ID: $postId)\n";
    
    // Assign categories based on title/content
    if (strpos($title, "alpine") !== false) {
        $cat = $taxonomy->findBySlug("category", "frontend");
        if ($cat) {
            $taxonomy->attach($postId, $cat->id, "post");
            echo "  - Attached to category: frontend\n";
        }
        $cat = $taxonomy->findBySlug("category", "javascript");
        if ($cat) {
            $taxonomy->attach($postId, $cat->id, "post");
            echo "  - Attached to category: javascript\n";
        }
    }
    
    if (strpos($title, "phalcon") !== false) {
        $cat = $taxonomy->findBySlug("category", "backend");
        if ($cat) {
            $taxonomy->attach($postId, $cat->id, "post");
            echo "  - Attached to category: backend\n";
        }
        $cat = $taxonomy->findBySlug("category", "php");
        if ($cat) {
            $taxonomy->attach($postId, $cat->id, "post");
            echo "  - Attached to category: php\n";
        }
    }
    
    if (strpos($title, "tailwind") !== false) {
        $cat = $taxonomy->findBySlug("category", "frontend");
        if ($cat) {
            $taxonomy->attach($postId, $cat->id, "post");
            echo "  - Attached to category: frontend\n";
        }
        $cat = $taxonomy->findBySlug("category", "css");
        if ($cat) {
            $taxonomy->attach($postId, $cat->id, "post");
            echo "  - Attached to category: css\n";
        }
    }
}

echo "\nDone!\n";
