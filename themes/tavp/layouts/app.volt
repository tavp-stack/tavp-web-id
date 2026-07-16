<?php
$settings = app()->getService('Tavp\Cms\Settings\Settings') ?? null;
$siteName = ($v = $settings?->get('general.site_name')) !== '' && $v !== null ? $v : 'TAVP Stack';
$tagline = ($v = $settings?->get('general.tagline')) !== '' && $v !== null ? $v : 'The Lean, Mean, PHP Machine';
$defaultTitle = $siteName . ' — ' . $tagline;
$defaultDescription = ($v = $settings?->get('general.description')) !== '' && $v !== null ? $v : 'Tailwind + Alpine + Volt + Phalcon. A curated PHP tech stack — thin, light, and fast.';
$copyright = ($v = $settings?->get('footer.copyright')) !== '' && $v !== null ? $v : '© 2026 TAVP Stack. Released under the MIT License.';
$logoUrl = ($v = $settings?->get('site.logo_url')) !== '' && $v !== null ? $v : '/assets/logo.png';
?>
<?php
$layoutBread = app()->getService(\Tavp\Cms\Bread\BreadManager::class) ?? null;
$layoutRecords = $layoutBread ? $layoutBread->browse('site_layout') : [];
$layout = $layoutRecords[0] ?? [];
?>
<!DOCTYPE html>
<html class="dark" lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<link rel="icon" type="image/png" href="/favicon.png"/>
<title><?= htmlspecialchars($page_title ?? $defaultTitle) ?></title>
<meta name="description" content="<?= htmlspecialchars($page_description ?? $defaultDescription) ?>"/>
<?php
$ogTitle = htmlspecialchars($page_title ?? $defaultTitle);
$ogDesc = htmlspecialchars($page_description ?? $defaultDescription);
$ogImage = $logoUrl ? (env('APP_URL', 'https://tavp.web.id') . $logoUrl) : '';
$ogUrl = env('APP_URL', 'https://tavp.web.id') . ($_SERVER['REQUEST_URI'] ?? '/');
?>
<meta property="og:type" content="website"/>
<meta property="og:title" content="<?= $ogTitle ?>"/>
<meta property="og:description" content="<?= $ogDesc ?>"/>
<meta property="og:image" content="<?= $ogImage ?>"/>
<meta property="og:url" content="<?= htmlspecialchars($ogUrl) ?>"/>
<meta property="og:site_name" content="<?= htmlspecialchars($siteName) ?>"/>
<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:title" content="<?= $ogTitle ?>"/>
<meta name="twitter:description" content="<?= $ogDesc ?>"/>
<meta name="twitter:image" content="<?= $ogImage ?>"/>
<link rel="canonical" href="<?= htmlspecialchars($ogUrl) ?>"/>
<link rel="alternate" type="application/rss+xml" title="<?= htmlspecialchars($siteName) ?> Blog" href="/feed"/>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"Organization","name":"<?= htmlspecialchars($siteName) ?>","url":"<?= env('APP_URL', 'https://tavp.web.id') ?>","logo":"<?= $ogImage ?>","description":"<?= $ogDesc ?>"}
</script>
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/themes/prism-tomorrow.min.css"/>
<script defer src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/prism.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-php.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-javascript.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-css.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-bash.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-json.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>window.tavpAnalyticsConfig={endpoint:'/api/analytics',sessionRecording:false};</script>
<script src="/js/tracker.js" defer></script>
<script>
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          "background": "#0d131f",
          "on-background": "#dde2f3",
          "surface": "#0d131f",
          "surface-container-lowest": "#080e1a",
          "surface-container-low": "#161c27",
          "surface-container": "#1a202c",
          "surface-container-high": "#242a36",
          "surface-container-highest": "#2f3542",
          "surface-variant": "#2f3542",
          "surface-bright": "#333946",
          "on-surface": "#dde2f3",
          "on-surface-variant": "#c5c6cd",
          "primary": "#bdc7dc",
          "on-primary": "#273141",
          "primary-container": "#2d3748",
          "secondary": "#e6c446",
          "on-secondary": "#3b2f00",
          "secondary-container": "#ac8e0a",
          "tertiary": "#bcc7dd",
          "on-tertiary-container": "#95a0b5",
          "outline": "#8f9097",
          "outline-variant": "#45474c",
          "error": "#ffb4ab"
        },
        borderRadius: { "DEFAULT": "0.125rem", "lg": "0.25rem", "xl": "0.5rem", "full": "0.75rem" },
        spacing: { "gutter": "1.5rem", "component-padding-y": "0.75rem", "component-padding-x": "1rem", "base": "4px", "container-margin": "2rem" },
        fontFamily: {
          "headline-xl": ["Geist"], "headline-lg": ["Geist"],
          "body-md": ["Inter"], "code-sm": ["JetBrains Mono"], "label-caps": ["JetBrains Mono"]
        },
        fontSize: {
          "headline-xl": ["40px", {"lineHeight": "48px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
          "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
          "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
          "code-sm": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
          "label-caps": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}]
        }
      }
    }
  }
</script>
<style>
  .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
  .performance-grid-pattern { background-image: radial-gradient(#4A5568 0.5px, transparent 0.5px); background-size: 24px 24px; }
  .hard-shadow { box-shadow: 4px 4px 0px 0px #000000; }
  .hard-shadow:active { box-shadow: 1px 1px 0px 0px #000000; transform: translate(3px, 3px); }
  .code-glow { box-shadow: 0 0 20px rgba(236, 201, 75, 0.05); }
  .step-number { font-family: 'JetBrains Mono'; -webkit-text-stroke: 1px #e6c446; color: transparent; }
  pre { background-color: #0d131f !important; border: 1px solid #45474c; }
  .token-keyword { color: #e6c446; }
  .token-string { color: #95a0b5; }
  .token-function { color: #f6ad55; }
  .token-comment { color: #45474c; font-style: italic; }
</style>
{% block head %}{% endblock %}
</head>
<body class="bg-background text-on-background font-body-md selection:bg-secondary selection:text-on-secondary" x-data="{ mobileMenu: false }">

<nav class="fixed top-0 w-full z-50 bg-background/80 backdrop-blur-md border-b border-outline-variant">
  <div class="max-w-[1280px] mx-auto px-gutter h-16 flex justify-between items-center">
    <a href="/" class="flex items-center gap-3">
      <img alt="TAVP" class="w-8 h-8 object-contain" src="<?= htmlspecialchars($layout['logo_url'] ?? $logoUrl) ?>"/>
      <span class="font-headline-lg text-headline-lg font-bold text-on-surface"><?= htmlspecialchars($siteName) ?></span>
    </a>
    <div class="flex items-center gap-6">
      <div class="hidden md:flex gap-6 items-center">
        <a class="font-body-md text-on-surface-variant hover:text-secondary transition-colors duration-200" href="<?= htmlspecialchars($layout['nav_1_url'] ?? '/documentation') ?>"><?= htmlspecialchars($layout['nav_1_text'] ?? 'Docs') ?></a>
        <a class="font-body-md text-on-surface-variant hover:text-secondary transition-colors duration-200" href="<?= htmlspecialchars($layout['nav_2_url'] ?? '/performance') ?>"><?= htmlspecialchars($layout['nav_2_text'] ?? 'Performance') ?></a>
        <a class="font-body-md text-on-surface-variant hover:text-secondary transition-colors duration-200" href="<?= htmlspecialchars($layout['nav_3_url'] ?? '/get-started') ?>"><?= htmlspecialchars($layout['nav_3_text'] ?? 'Get Started') ?></a>
        <a class="font-body-md text-on-surface-variant hover:text-secondary transition-colors duration-200" href="<?= htmlspecialchars($layout['nav_4_url'] ?? '/blog') ?>"><?= htmlspecialchars($layout['nav_4_text'] ?? 'Blog') ?></a>
        <a class="font-body-md text-on-surface-variant hover:text-secondary transition-colors duration-200" href="<?= htmlspecialchars($layout['nav_5_url'] ?? '/contact') ?>"><?= htmlspecialchars($layout['nav_5_text'] ?? 'Contact') ?></a>
      </div>
      <a href="<?= htmlspecialchars($layout['github_url'] ?? 'https://github.com/tavp-stack') ?>" target="_blank" rel="noopener noreferrer" class="text-on-surface hover:text-secondary transition-colors duration-200" aria-label="GitHub">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
      </a>
      <button @click="mobileMenu = !mobileMenu" class="md:hidden text-on-surface" aria-label="Toggle menu">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path x-show="!mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/><path x-show="mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
  </div>
  <div x-show="mobileMenu" x-transition @click.away="mobileMenu = false" class="md:hidden bg-surface-container border-t border-outline-variant">
    <div class="px-gutter py-4 space-y-3">
      <a class="block font-body-md text-on-surface-variant hover:text-secondary" href="<?= htmlspecialchars($layout['nav_1_url'] ?? '/documentation') ?>"><?= htmlspecialchars($layout['nav_1_text'] ?? 'Docs') ?></a>
      <a class="block font-body-md text-on-surface-variant hover:text-secondary" href="<?= htmlspecialchars($layout['nav_2_url'] ?? '/performance') ?>"><?= htmlspecialchars($layout['nav_2_text'] ?? 'Performance') ?></a>
      <a class="block font-body-md text-on-surface-variant hover:text-secondary" href="<?= htmlspecialchars($layout['nav_3_url'] ?? '/get-started') ?>"><?= htmlspecialchars($layout['nav_3_text'] ?? 'Get Started') ?></a>
      <a class="block font-body-md text-on-surface-variant hover:text-secondary" href="<?= htmlspecialchars($layout['nav_4_url'] ?? '/blog') ?>"><?= htmlspecialchars($layout['nav_4_text'] ?? 'Blog') ?></a>
      <a class="block font-body-md text-on-surface-variant hover:text-secondary" href="<?= htmlspecialchars($layout['nav_5_url'] ?? '/contact') ?>"><?= htmlspecialchars($layout['nav_5_text'] ?? 'Contact') ?></a>
    </div>
  </div>
</nav>

<main class="pt-16">
  {% block content %}{% endblock %}
</main>

<footer class="w-full py-20 px-gutter bg-surface-container-lowest border-t border-outline-variant">
  <div class="max-w-[1280px] mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
    <div class="space-y-4">
      <div class="flex items-center gap-2">
        <img alt="TAVP" class="w-8 h-8" src="<?= htmlspecialchars($layout['logo_url'] ?? '/assets/logo.png') ?>"/>
        <span class="font-headline-lg text-headline-lg font-bold text-secondary"><?= htmlspecialchars($siteName) ?></span>
      </div>
      <p class="font-body-md text-body-md text-on-tertiary-container max-w-xs">
        <?= htmlspecialchars($copyright) ?>
      </p>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-2 gap-12">
      <div class="space-y-4">
        <h4 class="font-label-caps text-on-surface uppercase tracking-widest">Resources</h4>
        <ul class="space-y-2">
          <?php $r1Url = $layout['footer_resource_1_url'] ?? 'https://docs.tavp.web.id/index.html'; ?>
          <li><a class="font-body-md text-on-tertiary-container hover:text-on-surface transition-all" href="<?= htmlspecialchars($r1Url) ?>" <?= str_starts_with($r1Url, 'http') ? 'target="_blank" rel="noopener noreferrer"' : '' ?>><?= htmlspecialchars($layout['footer_resource_1_text'] ?? 'Documentation') ?></a></li>
          <?php $r2Url = $layout['footer_resource_2_url'] ?? '/performance'; ?>
          <li><a class="font-body-md text-on-tertiary-container hover:text-on-surface transition-all" href="<?= htmlspecialchars($r2Url) ?>" <?= str_starts_with($r2Url, 'http') ? 'target="_blank" rel="noopener noreferrer"' : '' ?>><?= htmlspecialchars($layout['footer_resource_2_text'] ?? 'Benchmarks') ?></a></li>
        </ul>
      </div>
      <div class="space-y-4">
        <h4 class="font-label-caps text-on-surface uppercase tracking-widest">Connect</h4>
        <ul class="space-y-2">
          <?php $c1Url = $layout['footer_connect_1_url'] ?? 'https://github.com/tavp-stack'; ?>
          <li><a class="font-body-md text-on-tertiary-container hover:text-on-surface transition-all" href="<?= htmlspecialchars($c1Url) ?>" <?= str_starts_with($c1Url, 'http') ? 'target="_blank" rel="noopener noreferrer"' : '' ?>><?= htmlspecialchars($layout['footer_connect_1_text'] ?? 'GitHub') ?></a></li>
        </ul>
      </div>
    </div>
  </div>
</footer>

<script>
  document.querySelectorAll('.bg-surface-container, .bg-surface-container-low').forEach(function (card) {
    card.addEventListener('mousemove', function (e) {
      var rect = card.getBoundingClientRect();
      card.style.setProperty('--mouse-x', (e.clientX - rect.left) + 'px');
      card.style.setProperty('--mouse-y', (e.clientY - rect.top) + 'px');
    });
  });
</script>
{% block scripts %}{% endblock %}
</body>
</html>
