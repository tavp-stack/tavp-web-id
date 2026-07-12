<?php
$file = "C:/Users/JT/Projects/tavp-web-id/routes/web.php";
$content = file_get_contents($file);

// Add category/tag routes before "// Blog post"
$pos = strpos($content, "// Blog post");
if ($pos === false) {
    die("Could not find insertion point\n");
}

$newRoutes = '

// Blog category archive
$router->get("/blog/category/{slug}", function (array $params) {
    $slug = $params["slug"] ?? "";
    $taxonomy = app()->getService(Tavp\Cms\Taxonomy\TaxonomyManager::class);
    $term = $taxonomy->findBySlug("category", $slug);
    
    if (!$term) {
        http_response_code(404);
        return view("404");
    }
    
    $postIds = $taxonomy->contentIdsWithTerm($term->id, "post");
    $bread = app()->getService(BreadManager::class);
    
    $posts = [];
    foreach ($postIds as $postId) {
        $post = $bread->read("post", $postId);
        if ($post && ($post["status"] ?? "draft") === "published") {
            $posts[] = $post;
        }
    }
    
    return view("taxonomy", [
        "posts" => $posts,
        "term" => ["name" => $term->name, "slug" => $term->slug, "type" => "category"],
        "type" => "category"
    ]);
});

// Blog tag archive
$router->get("/blog/tag/{slug}", function (array $params) {
    $slug = $params["slug"] ?? "";
    $taxonomy = app()->getService(Tavp\Cms\Taxonomy\TaxonomyManager::class);
    $term = $taxonomy->findBySlug("tag", $slug);
    
    if (!$term) {
        http_response_code(404);
        return view("404");
    }
    
    $postIds = $taxonomy->contentIdsWithTerm($term->id, "post");
    $bread = app()->getService(BreadManager::class);
    
    $posts = [];
    foreach ($postIds as $postId) {
        $post = $bread->read("post", $postId);
        if ($post && ($post["status"] ?? "draft") === "published") {
            $posts[] = $post;
        }
    }
    
    return view("taxonomy", [
        "posts" => $posts,
        "term" => ["name" => $term->name, "slug" => $term->slug, "type" => "tag"],
        "type" => "tag"
    ]);
});
';

$newContent = substr($content, 0, $pos) . $newRoutes . "\n" . substr($content, $pos);
file_put_contents($file, $newContent);
echo "Routes added successfully\n";
