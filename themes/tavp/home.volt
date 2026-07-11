{% extends 'layouts/app.volt' %}

{% block content %}

{# Hero #}
<section class="relative min-h-[85vh] flex flex-col items-center justify-center text-center px-gutter overflow-hidden performance-grid-pattern">
  <div class="absolute inset-0 bg-gradient-to-b from-background via-transparent to-background pointer-events-none"></div>
  <div class="relative z-10 max-w-4xl mx-auto space-y-8">
    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-surface-container-low border border-outline-variant mb-6">
      <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
      <span class="font-label-caps text-label-caps text-on-tertiary-container uppercase tracking-widest">{{ content['hero_badge']|default('Stack v1.0 · Stable') }}</span>
    </div>
    <div class="flex justify-center mb-8">
      <img alt="TAVP Stack" class="w-32 h-32 md:w-48 md:h-48 object-contain drop-shadow-[0_0_30px_rgba(230,196,70,0.3)]" src="/assets/logo.png"/>
    </div>
    <h1 class="font-headline-xl text-headline-xl md:text-6xl text-on-surface tracking-tighter leading-tight">
      {{ content['hero_title']|default('The Lean, Mean, PHP Machine.') }}
    </h1>
    <p class="font-body-md text-body-md md:text-xl text-on-tertiary-container max-w-2xl mx-auto">
      {{ content['hero_subtitle']|default('Build blazingly fast systems with Tailwind, Alpine, Volt, and Phalcon. Thin, light, and engineered for the sub-millisecond era.') }}
    </p>
    <div class="flex flex-col sm:flex-row items-center justify-center gap-6 pt-4">
      <a href="https://docs.tavp.web.id/guide/what-is-tavp.html" class="w-full sm:w-auto px-8 py-4 bg-secondary text-on-secondary font-bold font-headline-lg text-lg hard-shadow transition-all">
        {{ content['cta_primary']|default('Get Started') }}
      </a>
      <a href="/performance" class="w-full sm:w-auto px-8 py-4 bg-surface-container border border-outline-variant text-on-surface font-bold font-headline-lg text-lg hover:border-secondary transition-colors">
        {{ content['cta_secondary']|default('View Benchmarks') }}
      </a>
    </div>
  </div>
</section>

{# Feature Bento Grid #}
<section class="py-24 max-w-[1280px] mx-auto px-gutter">
  <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

    <div class="md:col-span-8 bg-surface-container border border-outline-variant p-8 relative overflow-hidden group">
      <div class="absolute top-0 left-0 w-full h-[2px] bg-secondary opacity-50"></div>
      <div class="space-y-4 relative z-10">
        <span class="material-symbols-outlined text-secondary text-4xl">architecture</span>
        <h3 class="font-headline-lg text-headline-lg text-on-surface">Lean Architecture</h3>
        <p class="text-on-tertiary-container max-w-md">A C-extension core runs your code close to the metal, while a Laravel-style ergonomic layer keeps development a joy.</p>
      </div>
      <div class="mt-8 bg-background border border-outline-variant p-4 font-code-sm text-code-sm rounded shadow-inner">
        <div class="flex gap-2 mb-2">
          <span class="w-3 h-3 rounded-full bg-error/40"></span>
          <span class="w-3 h-3 rounded-full bg-secondary/40"></span>
          <span class="w-3 h-3 rounded-full bg-primary/40"></span>
        </div>
        <span class="text-secondary"># Response time (PHP-FPM)</span><br/>
        <span class="text-on-surface">P95 latency: </span> <span class="text-secondary">&lt;5ms</span>
      </div>
    </div>

    <div class="md:col-span-4 bg-surface-container-low border border-outline-variant p-8 flex flex-col justify-between hover:border-secondary transition-colors group">
      <div class="space-y-4">
        <span class="material-symbols-outlined text-secondary text-4xl">memory</span>
        <h3 class="font-headline-lg text-headline-lg text-on-surface">Thin Core</h3>
        <p class="text-on-tertiary-container">Modular by design. Load exactly what your application needs — nothing more.</p>
      </div>
      <a href="https://docs.tavp.web.id/index.html" class="pt-6 font-code-sm text-secondary group-hover:translate-x-2 transition-transform cursor-pointer flex items-center gap-2">
        Read the Docs <span class="material-symbols-outlined text-sm">arrow_forward</span>
      </a>
    </div>

    <div class="md:col-span-4 bg-surface-container-low border border-outline-variant p-8 flex flex-col justify-between hover:border-secondary transition-colors group">
      <div class="space-y-4">
        <span class="material-symbols-outlined text-secondary text-4xl">speed</span>
        <h3 class="font-headline-lg text-headline-lg text-on-surface">High Throughput</h3>
        <p class="text-on-tertiary-container">Thousands of requests per second on a modest 2-core VPS. Up to 12,000+ with the Coil runtime.</p>
      </div>
    </div>

    <div class="md:col-span-8 bg-surface-container border border-outline-variant p-8 relative group overflow-hidden">
      <div class="absolute inset-0 opacity-10 pointer-events-none">
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-secondary rounded-full blur-[100px]"></div>
      </div>
      <div class="flex flex-col md:flex-row gap-8 items-center h-full">
        <div class="flex-1 space-y-4">
          <span class="material-symbols-outlined text-secondary text-4xl">database</span>
          <h3 class="font-headline-lg text-headline-lg text-on-surface">Low RAM Footprint</h3>
          <p class="text-on-tertiary-container">Peak performance in under 15MB per worker — efficient enough for edge, containers, and modest boxes alike.</p>
        </div>
        <div class="flex-shrink-0 w-full md:w-1/3">
          <div class="flex items-end justify-between gap-1 h-32">
            <div class="w-full bg-secondary h-[10%]"></div>
            <div class="w-full bg-secondary h-[25%]"></div>
            <div class="w-full bg-secondary h-[15%]"></div>
            <div class="w-full bg-secondary h-[60%]"></div>
            <div class="w-full bg-secondary h-[45%]"></div>
            <div class="w-full bg-secondary h-[100%] animate-pulse"></div>
          </div>
          <div class="mt-2 text-center font-label-caps text-on-tertiary-container">Resource Usage</div>
        </div>
      </div>
    </div>
  </div>
</section>

{# Runs anywhere #}
<section class="bg-surface-container-lowest py-24 border-y border-outline-variant">
  <div class="max-w-[1280px] mx-auto px-gutter text-center space-y-12">
    <div class="space-y-4">
      <h2 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">Runs Where You Do</h2>
      <p class="text-on-tertiary-container max-w-2xl mx-auto">From the $5/mo VPS you already own to Docker and managed panels — TAVP feels right at home everywhere.</p>
    </div>
    <div class="flex flex-wrap justify-center gap-8 items-center opacity-60 grayscale hover:grayscale-0 transition-all duration-500">
      <div class="flex items-center gap-3"><span class="material-symbols-outlined text-4xl">dns</span><span class="font-label-caps text-xl">Any VPS</span></div>
      <div class="flex items-center gap-3"><span class="material-symbols-outlined text-4xl">deployed_code</span><span class="font-label-caps text-xl">Docker</span></div>
      <div class="flex items-center gap-3"><span class="material-symbols-outlined text-4xl">dashboard</span><span class="font-label-caps text-xl">HestiaCP</span></div>
      <div class="flex items-center gap-3"><span class="material-symbols-outlined text-4xl">dns</span><span class="font-label-caps text-xl">Shared Hosting</span></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-12">
      <div class="p-6 bg-background border border-outline-variant text-left space-y-4">
        <div class="text-secondary font-code-sm uppercase tracking-tighter">Response Time</div>
        <div class="text-4xl font-headline-xl text-on-surface">&lt;5ms</div>
        <div class="text-on-tertiary-container text-sm">P95 latency on a 2-core VPS with PHP-FPM.</div>
      </div>
      <div class="p-6 bg-background border border-outline-variant text-left space-y-4">
        <div class="text-secondary font-code-sm uppercase tracking-tighter">Throughput</div>
        <div class="text-4xl font-headline-xl text-on-surface">12,000+</div>
        <div class="text-on-tertiary-container text-sm">Requests per second with the Coil (Swoole) runtime.</div>
      </div>
      <div class="p-6 bg-background border border-outline-variant text-left space-y-4">
        <div class="text-secondary font-code-sm uppercase tracking-tighter">Memory</div>
        <div class="text-4xl font-headline-xl text-on-surface">&lt;15MB</div>
        <div class="text-on-tertiary-container text-sm">Per worker, at peak performance.</div>
      </div>
    </div>
  </div>
</section>

{# Final CTA #}
<section class="py-32 bg-background relative overflow-hidden">
  <div class="absolute inset-0 performance-grid-pattern opacity-20"></div>
  <div class="max-w-[1280px] mx-auto px-gutter text-center relative z-10 space-y-10">
    <h2 class="font-headline-xl text-headline-xl md:text-5xl text-on-surface">Less config, more craft.<br/><span class="text-secondary">Start building your product.</span></h2>
    <div class="flex flex-col md:flex-row justify-center gap-4">
      <a href="https://docs.tavp.web.id/guide/installation.html" class="px-12 py-5 bg-secondary text-on-secondary font-bold font-headline-lg text-xl hard-shadow">
        Get Started
      </a>
      <a href="https://docs.tavp.web.id/index.html" class="px-12 py-5 bg-surface-container border border-outline-variant text-on-surface font-bold font-headline-lg text-xl hover:bg-surface-container-high transition-colors">
        Documentation
      </a>
    </div>
  </div>
</section>

{% endblock %}
