<?php
$file = "C:/Users/JT/Projects/tavp-web-id/routes/web.php";
$content = file_get_contents($file);

// Find the position to insert the new routes (before "// Blog post")
$pos = strpos($content, "// Blog post");
if ($pos === false) {
    echo "Could not find insertion point\n";
    exit(1);
}

$newRoutes = "

// Blog category archive
\$router->get(\"/blog/category/{slug}\", function (array \$params) {
    \$slug = \$params[\"slug\"] ?? \"\";
    \$bread = app()->getService(BreadManager::class);
    \$allPosts = \$bread->browse(\"post\", [\"status\" => \"published\"]);
    
    // Debug: return post data
    if (count(\$allPosts) > 0) {
        return \"First post: \" . json_encode(\$allPosts[0]);
    }
    
    return \"No posts found\";
});

// Blog tag archive
\$router->get(\"/blog/tag/{slug}\", function (array \$params) {
    \$slug = \$params[\"slug\"] ?? \"\";
    return \"Tag: \" . \$slug;
});
";

$newContent = substr($content, 0, $pos) . $newRoutes . substr($content, $pos);

file_put_contents($file, $newContent);
echo "Routes added with debug\n";
