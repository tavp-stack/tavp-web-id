<!DOCTYPE html>
<html class="dark" lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>{{ page_title | default('TAVP Stack — The Lean, Mean, PHP Machine') }}</title>
<meta name="description" content="{{ page_description | default('Tailwind + Alpine + Volt + Phalcon. A curated PHP tech stack — thin, light, and fast.') }}"/>
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&family=Inter:wght@100..900&family=JetBrains+Mono:wght@100..900&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
</style>
</head>
<body class="bg-background text-on-background font-body-md selection:bg-secondary selection:text-on-secondary">

<nav class="fixed top-0 w-full z-50 bg-background/80 backdrop-blur-md border-b border-outline-variant">
  <div class="max-w-[1280px] mx-auto px-gutter h-16 flex justify-between items-center">
    <a href="/" class="flex items-center gap-3">
      <img alt="TAVP" class="w-8 h-8 object-contain" src="/assets/logo.png"/>
      <span class="font-headline-lg text-headline-lg font-bold text-on-surface">TAVP Stack</span>
    </a>
    <div class="hidden md:flex gap-8 items-center">
      <a class="font-body-md text-on-surface-variant hover:text-secondary transition-colors duration-200" href="https://docs.tavp.web.id">Docs</a>
      <a class="font-body-md text-on-surface-variant hover:text-secondary transition-colors duration-200" href="/performance">Performance</a>
      <a class="font-body-md text-on-surface-variant hover:text-secondary transition-colors duration-200" href="/showcase">Showcase</a>
      <a class="font-body-md text-on-surface-variant hover:text-secondary transition-colors duration-200" href="/blog">Blog</a>
    </div>
    <div class="flex items-center gap-4">
      <a href="https://github.com/tavp-stack" class="p-2 text-on-surface hover:text-secondary transition-colors duration-200" aria-label="GitHub">
        <span class="material-symbols-outlined">code</span>
      </a>
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
        <img alt="TAVP" class="w-8 h-8" src="/assets/logo.png"/>
        <span class="font-headline-lg text-headline-lg font-bold text-secondary">TAVP Stack</span>
      </div>
      <p class="font-code-sm text-code-sm text-on-tertiary-container max-w-xs">
        &copy; {{ '2026' }} TAVP Stack. Released under the MIT License. A curated PHP tech stack for modern engineers.
      </p>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-12">
      <div class="space-y-4">
        <h4 class="font-label-caps text-on-surface text-xs uppercase tracking-widest">Resources</h4>
        <ul class="space-y-2">
          <li><a class="font-code-sm text-code-sm text-on-tertiary-container hover:text-on-surface underline transition-all" href="https://docs.tavp.web.id">Documentation</a></li>
          <li><a class="font-code-sm text-code-sm text-on-tertiary-container hover:text-on-surface underline transition-all" href="/performance">Benchmarks</a></li>
        </ul>
      </div>
      <div class="space-y-4">
        <h4 class="font-label-caps text-on-surface text-xs uppercase tracking-widest">Connect</h4>
        <ul class="space-y-2">
          <li><a class="font-code-sm text-code-sm text-on-tertiary-container hover:text-on-surface underline transition-all" href="https://github.com/tavp-stack">GitHub</a></li>
          <li><a class="font-code-sm text-code-sm text-on-tertiary-container hover:text-on-surface underline transition-all" href="#">Discord</a></li>
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
</body>
</html>
