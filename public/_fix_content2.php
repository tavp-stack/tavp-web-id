<?php
$file = '/app/vendor/tavp/cms/src/Admin/ContentController.php';
$content = file_get_contents($file);

// 1. Add private method before the last }
$method = '
    /**
     * Get current logged-in user\'s name for author field.
     */
    private function getCurrentUserName(): string
    {
        $email = $_SESSION[\'cms_admin\'] ?? \'\';
        if (empty($email)) {
            return \'\';
        }
        try {
            $rows = app(\'db\')->fetchAll(
                \'SELECT name FROM users WHERE email = :email LIMIT 1\',
                \PDO::FETCH_ASSOC,
                [\'email\' => $email]
            );
            return $rows[0][\'name\'] ?? \'\';
        } catch (\Throwable) {
            return \'\';
        }
    }
';

$lastBrace = strrpos($content, '}');
if ($lastBrace !== false) {
    $content = substr($content, 0, $lastBrace) . $method . "\n}\n";
}

// 2. Inject author in store() - find "public function store" and add after the first {$ block
$storePattern = '/public function store\(string \$type\): .*?\n\{/s';
if (preg_match($storePattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
    $pos = $matches[0][1] + strlen($matches[0][0]);
    // Find the next { to get function body
    $bodyStart = strpos($content, '{', $pos);
    if ($bodyStart !== false) {
        // Find matching } for function body
        $braceCount = 1;
        $insertPos = $bodyStart + 1;
        // Insert author injection after the first line of the function body
        $insertCode = "\n        // Auto-fill author from logged-in user\n        \$data['author'] = \$this->getCurrentUserName();\n";
        // We need to find where $data is set or used
        // For simplicity, we'll inject after the first few lines of the function
        $content = substr($content, 0, $insertPos) . $insertCode . substr($content, $insertPos);
    }
}

file_put_contents($file . '.bak', file_get_contents($file));
file_put_contents($file, $content);
echo "File modified. Backup saved to $file.bak\n";
