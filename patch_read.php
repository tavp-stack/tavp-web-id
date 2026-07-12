<?php

// Read the current BreadManager.php file
$file = __DIR__ . '/vendor/tavp/cms/src/Bread/BreadManager.php';
$content = file_get_contents($file);

if ($content === false) {
    die("ERROR: Could not read file\n");
}

// Replace the read() method
$oldMethod = '    public function read(string $type, string|int $id): ?array
    {
        return $this->store->find($this->must($type), $id);
    }';

$newMethod = '    public function read(string $type, string|int $id): ?array
    {
        $contentType = $this->must($type);

        // If $id is numeric (int or numeric string), treat as ID; otherwise treat as slug.
        if (is_numeric($id)) {
            return $this->store->find($contentType, (int) $id);
        }

        return $this->store->findBySlug($contentType, (string) $id);
    }';

if (strpos($content, $oldMethod) !== false) {
    $content = str_replace($oldMethod, $newMethod, $content);
    file_put_contents($file, $content);
    echo "SUCCESS: Patched BreadManager::read() method\n";
} else {
    echo "ERROR: Could not find the read() method to patch\n";
    echo "Please check the file manually\n";
}
