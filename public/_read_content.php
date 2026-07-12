<?php
$file = 'C:\Users\JT\Projects\tavp-web-id\vendor\tavp\cms\src\Admin\ContentController.php';
$lines = file($file);
echo "Total lines: " . count($lines) . "\n\n";
for ($i=54; $i<135 && $i<count($lines); $i++) {
    echo ($i+1) . ': ' . rtrim($lines[$i]) . "\n";
}
