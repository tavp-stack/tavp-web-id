{% extends 'layouts/app.volt' %}

{% block head %}
<style>
  .code-block { background-color: #0d131f; border: 1px solid #45474c; border-left: 4px solid #e6c446; }
  .performance-card { transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
  .performance-card:hover { transform: translateY(-2px); border-color: #e6c446; }
</style>
{% endblock %}

{% block content %}
<div class="max-w-[1280px] mx-auto flex">

  {# Sidebar #}
  <aside class="hidden md:block w-64 sticky top-16 h-[calc(100vh-4rem)] border-r border-outline-variant overflow-y-auto pt-8 px-gutter">
    <div class="space-y-8">
      <div>
        <h5 class="font-label-caps text-label-caps text-label-caps text-on-tertiary-container mb-4 uppercase tracking-widest">Introduction</h5>
        <ul class="space-y-2">
          <li><a class="block text-secondary font-bold font-body-md" href="/documentation">The TAVP Philosophy</a></li>
          <li><a class="block text-on-surface-variant hover:text-secondary transition-colors" href="https://docs.tavp.web.id/guide/installation.html">Installation</a></li>
          <li><a class="block text-on-surface-variant hover:text-secondary transition-colors" href="https://docs.tavp.web.id/guide/what-is-tavp.html">What is TAVP</a></li>
        </ul>
      </div>
      <div>
        <h5 class="font-label-caps text-label-caps text-label-caps text-on-tertiary-container mb-4 uppercase tracking-widest">Components</h5>
        <ul class="space-y-2">
          <li><a class="block text-on-surface-variant hover:text-secondary transition-colors" href="https://docs.tavp.web.id/guide/what-is-tavp.html">Tailwind &amp; Alpine</a></li>
          <li><a class="block text-on-surface-variant hover:text-secondary transition-colors" href="https://docs.tavp.web.id/guide/quick-start.html">Volt Templates</a></li>
          <li><a class="block text-on-surface-variant hover:text-secondary transition-colors" href="https://docs.tavp.web.id/architecture/overview.html">Phalcon Core</a></li>
        </ul>
      </div>
      <div>
        <h5 class="font-label-caps text-label-caps text-label-caps text-on-tertiary-container mb-4 uppercase tracking-widest">Advanced</h5>
        <ul class="space-y-2">
          <li><a class="block text-on-surface-variant hover:text-secondary transition-colors" href="https://docs.tavp.web.id/runtimes/overview.html">Runtimes</a></li>
          <li><a class="block text-on-surface-variant hover:text-secondary transition-colors" href="/performance">Benchmarks</a></li>
        </ul>
      </div>
    </div>
  </aside>

  {# Main #}
  <main class="flex-1 md:pl-12 px-gutter py-12">
    <div class="max-w-3xl">
      <nav class="flex items-center gap-2 mb-8 text-on-tertiary-container font-code-sm text-code-sm text-code-sm">
        <span>Docs</span>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="text-secondary">Introduction</span>
      </nav>

      <header class="mb-12 relative overflow-hidden p-8 bg-surface-container rounded-xl border border-outline-variant">
        <div class="relative z-10">
          <h1 class="font-headline-xl text-headline-xl text-headline-xl mb-4">{{ content['hero_title']|default('Introduction to the TAVP Stack') }}</h1>
          <p class="text-body-md text-on-surface-variant max-w-2xl leading-relaxed">
            {{ content['intro']|default('TAVP is a lean, high-performance stack for modern web applications. It pairs the speed of C-extension PHP with utility-first CSS and lightweight reactive JS — thin by default, powerful when you need it.') }}
          </p>
        </div>
      </header>

      {# Core components #}
      <section class="mb-16">
        <h2 class="font-headline-lg text-headline-lg text-headline-lg mb-8 border-b-2 border-surface-variant pb-2">{{ content['core_heading']|default('Core Components') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="performance-card bg-surface-container p-6 rounded-xl border border-outline-variant border-t-2 border-t-secondary">
            <div class="flex items-center gap-3 mb-4"><span class="w-10 h-10 flex items-center justify-center bg-secondary-container text-on-secondary rounded font-bold font-code-sm text-code-sm">T</span><h3 class="font-headline-lg text-headline-lg text-xl">Tailwind CSS</h3></div>
            <p class="text-body-md text-on-tertiary-container mb-4">Utility-first styling that keeps bundles small and design consistent.</p>
            <div class="code-block p-3 rounded font-code-sm text-code-sm text-code-sm text-on-tertiary-container"><span class="text-secondary">class</span>=<span class="text-on-surface">"flex gap-4 items-center"</span></div>
          </div>
          <div class="performance-card bg-surface-container p-6 rounded-xl border border-outline-variant border-t-2 border-t-secondary">
            <div class="flex items-center gap-3 mb-4"><span class="w-10 h-10 flex items-center justify-center bg-secondary-container text-on-secondary rounded font-bold font-code-sm text-code-sm">A</span><h3 class="font-headline-lg text-headline-lg text-xl">Alpine.js</h3></div>
            <p class="text-body-md text-on-tertiary-container mb-4">Lightweight reactivity right in your HTML. Minimal footprint, declarative power.</p>
            <div class="code-block p-3 rounded font-code-sm text-code-sm text-code-sm text-on-tertiary-container"><span class="text-secondary">x-data</span>=<span class="text-on-surface">"{ open: false }"</span></div>
          </div>
          <div class="performance-card bg-surface-container p-6 rounded-xl border border-outline-variant border-t-2 border-t-secondary">
            <div class="flex items-center gap-3 mb-4"><span class="w-10 h-10 flex items-center justify-center bg-secondary-container text-on-secondary rounded font-bold font-code-sm text-code-sm">V</span><h3 class="font-headline-lg text-headline-lg text-xl">Volt Templates</h3></div>
            <p class="text-body-md text-on-tertiary-container mb-4">A template engine compiled to plain PHP for fast, zero-delay rendering.</p>
            <div class="code-block p-3 rounded font-code-sm text-code-sm text-code-sm text-on-tertiary-container">&#123;&#123; <span class="text-secondary">content()</span> &#125;&#125;</div>
          </div>
          <div class="performance-card bg-surface-container p-6 rounded-xl border border-outline-variant border-t-2 border-t-secondary">
            <div class="flex items-center gap-3 mb-4"><span class="w-10 h-10 flex items-center justify-center bg-secondary-container text-on-secondary rounded font-bold font-code-sm text-code-sm">P</span><h3 class="font-headline-lg text-headline-lg text-xl">Phalcon PHP</h3></div>
            <p class="text-body-md text-on-tertiary-container mb-4">A C-extension PHP framework: high-speed routing, ORM, and DI container.</p>
            <div class="code-block p-3 rounded font-code-sm text-code-sm text-code-sm text-on-tertiary-container"><span class="text-secondary">$app</span>-&gt;<span class="text-on-surface">handle()</span>;</div>
          </div>
        </div>
      </section>

      {# Lean philosophy #}
      <section class="mb-16">
        <h2 class="font-headline-lg text-headline-lg text-headline-lg mb-8 border-b-2 border-surface-variant pb-2">{{ content['philosophy_heading']|default('The \'Lean\' Philosophy') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="space-y-4"><span class="material-symbols-outlined text-secondary text-4xl">speed</span><h4 class="font-headline-lg text-headline-lg text-lg">Maximum Speed</h4><p class="text-body-md text-on-tertiary-container">Phalcon's C-extension keeps backend response times low and predictable.</p></div>
          <div class="space-y-4"><span class="material-symbols-outlined text-secondary text-4xl">layers_clear</span><h4 class="font-headline-lg text-headline-lg text-lg">Zero Bloat</h4><p class="text-body-md text-on-tertiary-container">Ship only what the page needs. Lightweight by default, no heavy runtime.</p></div>
          <div class="space-y-4"><span class="material-symbols-outlined text-secondary text-4xl">developer_mode</span><h4 class="font-headline-lg text-headline-lg text-lg">Tooling Unity</h4><p class="text-body-md text-on-tertiary-container">One coherent stack for styling, behavior, and logic — productive end to end.</p></div>
        </div>
      </section>

      {# Architecture callout (replaces comparison image) #}
      <section class="mb-16">
        <div class="relative rounded-xl overflow-hidden border border-outline-variant p-10 bg-surface-container-low">
          <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-secondary/10 rounded-full blur-[80px]"></div>
          <div class="relative z-10">
            <p class="font-label-caps text-label-caps text-label-caps text-secondary mb-1">{{ content['runtimes_badge']|default('Four runtimes, one codebase') }}</p>
            <h3 class="font-headline-lg text-headline-lg text-2xl mb-3">{{ content['runtimes_title']|default('Pick the road that fits your deploy.') }}</h3>
            <p class="text-on-surface-variant max-w-2xl">{{ content['runtimes_desc']|default('PHP-FPM for shared hosting, Coil (Swoole) and Relay (RoadRunner) for high traffic, Weave (PHP Fibers) for parallel I/O — the same app, no rewrite.') }}</p>
          </div>
        </div>
      </section>

      {# License #}
      <section class="mb-16 p-8 bg-surface-container-low rounded-xl border border-outline-variant border-l-4 border-l-secondary">
        <div class="flex items-start gap-4">
          <span class="material-symbols-outlined text-secondary">verified_user</span>
          <div>
            <h2 class="font-headline-lg text-headline-lg text-2xl mb-4">{{ content['license_title']|default('Open Source License') }}</h2>
            <p class="text-body-md text-on-surface-variant leading-relaxed mb-4">{{ content['license_desc']|default('TAVP is released under the MIT License — free for personal and commercial use. Fork it, modify it, and contribute back to the ecosystem.') }}</p>
            <div class="flex gap-4">
              <a href="{{ content['license_btn1_url']|default('https://github.com/tavp-stack') }}" class="px-6 py-2 bg-secondary text-on-secondary rounded-lg font-bold hover:bg-secondary-fixed transition-colors">{{ content['license_btn1_label']|default('View on GitHub') }}</a>
              <a href="{{ content['license_btn2_url']|default('https://docs.tavp.web.id/index.html') }}" class="px-6 py-2 border border-outline-variant text-on-surface rounded-lg font-bold hover:bg-surface-container-high transition-colors">{{ content['license_btn2_label']|default('Read the Docs') }}</a>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>
</div>
{% endblock %}
