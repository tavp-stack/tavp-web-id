<?php
$file = "C:/Users/JT/Projects/tavp-web-id/routes/web.php";
$content = file_get_contents($file);

$newRoutes = "

// Blog category archive
\$router->get(\"/blog/category/{slug}\", function (array \$params) {
    \$slug = \$params[\"slug\"] ?? \"\";
    \$bread = app()->getService(BreadManager::class);
    \$allPosts = \$bread->browse(\"post\", [\"status\" => \"published\"]);

    // Filter posts by category
    \$posts = array_filter(\$allPosts, function (\$post) use (\$slug) {
        \$categories = \$post[\"categories\"] ?? [];
        return in_array(\$slug, \$categories, true);
    });

    return view(\"taxonomy\", [
        \"posts\" => array_values(\$posts),
        \"term\" => [\"name\" => ucfirst(str_replace(\"-\", \" \", \$slug)), \"slug\" => \$slug, \"type\" => \"category\"],
        \"type\" => \"category\"
    ]);
});

// Blog tag archive
\$router->get(\"/blog/tag/{slug}\", function (array \$params) {
    \$slug = \$params[\"slug\"] ?? \"\";
    \$bread = app()->getService(BreadManager::class);
    \$allPosts = \$bread->browse(\"post\", [\"status\" => \"published\"]);

    // Filter posts by tag
    \$posts = array_filter(\$allPosts, function (\$post) use (\$slug) {
        \$tags = \$post[\"tags\"] ?? [];
        return in_array(\$slug, \$tags, true);
    });

    return view(\"taxonomy\", [
        \"posts\" => array_values(\$posts),
        \"term\" => [\"name\" => ucfirst(str_replace(\"-\", \" \", \$slug)), \"slug\" => \$slug, \"type\" => \"tag\"],
        \"type\" => \"tag\"
    ]);
});
";

$newContent = str_replace("// Blog post", $newRoutes . "\n// Blog post", $content);

file_put_contents($file, $newContent);
echo "Routes added successfully\n";
