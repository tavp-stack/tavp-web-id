<?php
$file = 'C:\Users\JT\Projects\tavp-web-id\vendor\tavp\cms\src\Admin\ContentController.php';
$content = file_get_contents($file);

// Add private method to get current user's name
$newMethod = '
    /**
     * Get the current logged-in user\'s name for author field.
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

// Find a good place to insert the method (before the last closing })
// For simplicity, we'll append it before the final }
$lastBrace = strrpos($content, '}');
if ($lastBrace !== false) {
    $content = substr($content, 0, $lastBrace) . $newMethod . "\n}\n";
}

file_put_contents($file . '.backup', file_get_contents($file));
file_put_contents($file, $content);
echo "Backup saved to $file.backup\n";
echo "Method added. Now need to inject author in store() and update().\n";
