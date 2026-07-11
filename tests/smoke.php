<?php

declare(strict_types=1);

/**
 * Smoke test — verifies the basic functionality of tavp.web.id.
 *
 * Usage: php tests/smoke.php [base_url]
 *
 * Requires: curl extension enabled.
 */

$baseUrl = $argv[1] ?? 'http://tavp-web-id.lndo.site';
$passed = 0;
$failed = 0;

echo "═══════════════════════════════════════════\n";
echo " TAVP.web.id Smoke Test\n";
echo " Target: {$baseUrl}\n";
echo "═══════════════════════════════════════════\n\n";

// --- Test 1: Home page loads ---
test('GET /', function () use ($baseUrl) {
    $status = httpGet("{$baseUrl}/");
    return $status === 200;
});

// --- Test 2: Documentation page loads ---
test('GET /documentation', function () use ($baseUrl) {
    $status = httpGet("{$baseUrl}/documentation");
    return $status === 200;
});

// --- Test 3: Performance page loads ---
test('GET /performance', function () use ($baseUrl) {
    $status = httpGet("{$baseUrl}/performance");
    return $status === 200;
});

// --- Test 4: Get Started page loads ---
test('GET /get-started', function () use ($baseUrl) {
    $status = httpGet("{$baseUrl}/get-started");
    return $status === 200;
});

// --- Test 5: Blog index loads ---
test('GET /blog', function () use ($baseUrl) {
    $status = httpGet("{$baseUrl}/blog");
    return $status === 200;
});

// --- Test 6: Sitemap loads ---
test('GET /sitemap.xml', function () use ($baseUrl) {
    $status = httpGet("{$baseUrl}/sitemap.xml");
    return $status === 200;
});

// --- Test 7: Admin login page loads ---
test('GET /admin/login', function () use ($baseUrl) {
    $status = httpGet("{$baseUrl}/admin/login");
    return $status === 200;
});

// --- Test 8: API types endpoint (unauthorized) ---
test('GET /api/cms/types (should return 401)', function () use ($baseUrl) {
    $status = httpGet("{$baseUrl}/api/cms/types");
    return $status === 401;
});

// --- Test 9: 404 page for non-existent slug ---
test('GET /nonexistent-page-xyz (should return 404)', function () use ($baseUrl) {
    $status = httpGet("{$baseUrl}/nonexistent-page-xyz");
    return $status === 404;
});

// --- Summary ---
echo "\n═══════════════════════════════════════════\n";
echo " Results: {$passed} passed, {$failed} failed\n";
echo "═══════════════════════════════════════════\n";

exit($failed > 0 ? 1 : 0);

// --- Helpers ---

function test(string $name, callable $fn): void
{
    global $passed, $failed;

    try {
        $result = $fn();
        if ($result) {
            echo "  ✓ {$name}\n";
            $passed++;
        } else {
            echo "  ✗ {$name}\n";
            $failed++;
        }
    } catch (\Throwable $e) {
        echo "  ✗ {$name} — {$e->getMessage()}\n";
        $failed++;
    }
}

function httpGet(string $url): int
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_NOBODY => false,
        CURLOPT_HTTPHEADER => ['Accept: text/html,application/json'],
    ]);
    curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $status;
}
