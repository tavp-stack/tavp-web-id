{% extends 'layouts/app.volt' %}

{% block head %}
<style>
  .performance-card-glow { position: relative; }
  .performance-card-glow::before { content: ''; position: absolute; top: -1px; left: 0; right: 0; height: 2px; background: #e6c446; z-index: 10; }
  .hard-step-shadow { box-shadow: 2px 2px 0px 0px #000000; }
  .chart-bar-transition { transition: width 1.5s cubic-bezier(0.34, 1.56, 0.64, 1); }
</style>
{% endblock %}

{% block content %}

{# Hero #}
<section class="relative py-24 overflow-hidden border-b border-outline-variant">
  <div class="max-w-[1280px] mx-auto px-gutter relative z-10">
    <div class="max-w-3xl">
      <h1 class="font-headline-xl text-headline-xl font-display text-headline-xl text-on-surface mb-6">{{ content['hero_title']|default('Built for Bare Metal Speed.') }}</h1>
      <p class="text-on-surface-variant text-lg mb-8 leading-relaxed">
        {{ content['hero_intro']|default('There are many roads to building great software. TAVP is the path for those who want bare-metal speed with modern ergonomics. Because Phalcon lives in memory as a C-extension, the same app runs comfortably on a tiny box or scales out to serve millions.') }}
      </p>
      <div class="flex gap-4">
        <a href="{{ content['cta1_url']|default('https://docs.tavp.web.id/runtimes/overview.html') }}" class="px-6 py-3 bg-secondary text-on-secondary font-bold rounded-lg hard-step-shadow hover:translate-y-[-2px] transition-all">{{ content['cta1_label']|default('Explore Runtimes') }}</a>
        <a href="{{ content['cta2_url']|default('https://docs.tavp.web.id/reference/performance.html') }}" class="px-6 py-3 border border-outline text-on-surface font-bold rounded-lg hover:bg-surface-container transition-all">{{ content['cta2_label']|default('Methodology') }}</a>
      </div>
    </div>
  </div>
</section>

{# Runtime throughput + Low-End Box #}
<section class="py-20 bg-surface-container-lowest">
  <div class="max-w-[1280px] mx-auto px-gutter">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

      {# Throughput by runtime — TAVP's own "many roads" #}
      <div class="lg:col-span-7 bg-surface-container p-8 rounded-xl performance-card-glow border border-outline-variant">
        <div class="flex justify-between items-start mb-10">
          <div>
            <h3 class="font-headline-lg text-headline-lg font-display text-on-surface">{{ content['runtime_heading']|default('Throughput by Runtime') }}</h3>
            <p class="text-on-surface-variant font-code-sm text-code-sm">{{ content['runtime_subtitle']|default('Same code, four runtimes — pick your road (2-core VPS).') }}</p>
          </div>
          <span class="material-symbols-outlined text-secondary text-4xl">bolt</span>
        </div>
        <div class="space-y-8">
          <div>
            <div class="flex justify-between mb-2"><span class="font-label-caps text-label-caps text-on-surface">{{ content['runtime_1_label']|default('TAVP COIL · SWOOLE') }}</span><span class="font-code-sm text-code-sm text-secondary">{{ content['runtime_1_value']|default('12,000+ RPS') }}</span></div>
            <div class="w-full bg-background rounded-full h-4 overflow-hidden"><div class="chart-bar-transition bg-secondary h-full" style="width: {{ content['runtime_1_width']|default('100%') }};"></div></div>
          </div>
          <div>
            <div class="flex justify-between mb-2"><span class="font-label-caps text-label-caps text-on-surface">{{ content['runtime_2_label']|default('TAVP RELAY · ROADRUNNER') }}</span><span class="font-code-sm text-code-sm text-secondary">{{ content['runtime_2_value']|default('9,000+ RPS') }}</span></div>
            <div class="w-full bg-background rounded-full h-4 overflow-hidden"><div class="chart-bar-transition bg-secondary h-full" style="width: {{ content['runtime_2_width']|default('75%') }};"></div></div>
          </div>
          <div>
            <div class="flex justify-between mb-2"><span class="font-label-caps text-label-caps text-on-surface">{{ content['runtime_3_label']|default('TAVP WEAVE · PHP FIBERS') }}</span><span class="font-code-sm text-code-sm text-secondary">{{ content['runtime_3_value']|default('6,000+ RPS') }}</span></div>
            <div class="w-full bg-background rounded-full h-4 overflow-hidden"><div class="chart-bar-transition bg-secondary h-full" style="width: {{ content['runtime_3_width']|default('50%') }};"></div></div>
          </div>
          <div>
            <div class="flex justify-between mb-2"><span class="font-label-caps text-label-caps text-on-surface">{{ content['runtime_4_label']|default('PHP-FPM · DEFAULT') }}</span><span class="font-code-sm text-code-sm text-secondary">{{ content['runtime_4_value']|default('5,000+ RPS') }}</span></div>
            <div class="w-full bg-background rounded-full h-4 overflow-hidden"><div class="chart-bar-transition bg-secondary h-full" style="width: {{ content['runtime_4_width']|default('42%') }};"></div></div>
          </div>
        </div>
        <p class="mt-12 font-code-sm text-code-sm text-on-tertiary-container italic leading-tight">{{ content['runtime_footer']|default('Only the runtime changes — zero code rewrite.') }}</p>
      </div>

      {# Low-End Box #}
      <div class="lg:col-span-5 bg-primary-container p-8 rounded-xl border border-secondary/30 relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity"><span class="material-symbols-outlined text-[120px]">memory</span></div>
        <h3 class="font-headline-lg text-headline-lg font-display text-secondary mb-4">{{ content['lowend_title']|default('The "Low-End Box" Test') }}</h3>
        <p class="text-on-surface mb-8">{{ content['lowend_desc']|default('We ran TAVP on a modest VPS to show how far efficient architecture goes.') }}</p>
        <div class="flex items-end gap-2 mb-2"><span class="font-headline-xl text-headline-xl font-display text-secondary">{{ content['lowend_p95_value']|default('&lt;5ms') }}</span><span class="font-label-caps text-label-caps text-on-surface-variant pb-2">{{ content['lowend_p95_label']|default('P95 LATENCY') }}</span></div>
        <p class="font-code-sm text-code-sm text-on-tertiary-container mb-10">{{ content['lowend_response_desc']|default('Response time under typical concurrent load.') }}</p>
        <div class="space-y-4">
          <div class="flex justify-between items-center py-3 border-b border-outline-variant"><span class="font-label-caps text-label-caps">{{ content['lowend_stat1_label']|default('Memory / Worker') }}</span><span class="font-code-sm text-code-sm text-secondary">{{ content['lowend_stat1_value']|default('&lt;15 MB') }}</span></div>
          <div class="flex justify-between items-center py-3 border-b border-outline-variant"><span class="font-label-caps text-label-caps">{{ content['lowend_stat2_label']|default('With Coil') }}</span><span class="font-code-sm text-code-sm text-secondary">{{ content['lowend_stat2_value']|default('&lt;8 MB') }}</span></div>
          <div class="flex justify-between items-center py-3 border-b border-outline-variant"><span class="font-label-caps text-label-caps">{{ content['lowend_stat3_label']|default('Concurrent') }}</span><span class="font-code-sm text-code-sm text-secondary">{{ content['lowend_stat3_value']|default('5,000+') }}</span></div>
        </div>
      </div>

      {# Memory + advantages #}
      <div class="lg:col-span-12 grid grid-cols-1 md:grid-cols-2 gap-8 mt-4">
        <div class="bg-surface-container p-8 rounded-xl border border-outline-variant">
          <div class="flex items-center gap-4 mb-6"><span class="material-symbols-outlined text-secondary">analytics</span><h4 class="font-headline-lg text-headline-lg font-display text-on-surface">{{ content['memory_heading']|default('Memory Per Worker') }}</h4></div>
          <div class="h-64 relative flex items-end justify-around gap-4 px-4 border-b border-outline-variant">
            <div class="flex flex-col items-center w-full max-w-[80px]"><div class="bg-secondary w-full rounded-t-lg transition-all duration-1000 delay-300" style="height: {{ content['memory_coil_height']|default('52%') }};"></div><span class="font-code-sm text-code-sm text-secondary mt-2">{{ content['memory_coil_value']|default('&lt;8MB') }}</span><span class="font-label-caps text-label-caps mt-1">{{ content['memory_coil_label']|default('Coil') }}</span></div>
            <div class="flex flex-col items-center w-full max-w-[80px]"><div class="bg-secondary w-full rounded-t-lg transition-all duration-1000 delay-300" style="height: {{ content['memory_fpm_height']|default('100%') }};"></div><span class="font-code-sm text-code-sm text-secondary mt-2">{{ content['memory_fpm_value']|default('&lt;15MB') }}</span><span class="font-label-caps text-label-caps mt-1">{{ content['memory_fpm_label']|default('PHP-FPM') }}</span></div>
          </div>
          <p class="mt-6 text-on-surface-variant">{{ content['memory_desc']|default('Phalcon\'s C-compiled kernel keeps PHP-level memory overhead low.') }}</p>
        </div>
        <div class="bg-surface-container-high p-8 rounded-xl border border-outline-variant flex flex-col justify-center">
          <h4 class="font-headline-lg text-headline-lg font-display text-on-surface mb-6">{{ content['why_title']|default('Why It\'s Fast') }}</h4>
          <ul class="space-y-4">
            <li class="flex gap-4"><span class="material-symbols-outlined text-secondary">check_circle</span><div><p class="font-bold text-on-surface">{{ content['why_1_title']|default('Memory Resident') }}</p><p class="text-on-surface-variant text-sm">{{ content['why_1_desc']|default('The framework is compiled into the PHP process — parsed once at start, not per request.') }}</p></div></li>
            <li class="flex gap-4"><span class="material-symbols-outlined text-secondary">check_circle</span><div><p class="font-bold text-on-surface">{{ content['why_2_title']|default('Compiled Templates') }}</p><p class="text-on-surface-variant text-sm">{{ content['why_2_desc']|default('Volt compiles to plain PHP, so rendering has no interpretation delay.') }}</p></div></li>
            <li class="flex gap-4"><span class="material-symbols-outlined text-secondary">check_circle</span><div><p class="font-bold text-on-surface">{{ content['why_3_title']|default('Direct C Data Flow') }}</p><p class="text-on-surface-variant text-sm">{{ content['why_3_desc']|default('Routing, ORM, and DI run as native C calls.') }}</p></div></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

{# Architecture focus #}
<section class="py-20">
  <div class="max-w-[1280px] mx-auto px-gutter grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
    <div>
      <div class="inline-flex items-center px-3 py-1 bg-secondary/10 border border-secondary/30 rounded-full mb-6">
        <span class="material-symbols-outlined text-secondary text-sm mr-2" style="font-variation-settings: 'FILL' 1;">electric_bolt</span>
        <span class="font-label-caps text-label-caps text-secondary">{{ content['arch_badge']|default('Architecture Focus') }}</span>
      </div>
      <h2 class="font-headline-xl text-headline-xl font-display text-on-surface mb-6">{{ content['arch_title']|default('Leaner Internals, Faster Deployment.') }}</h2>
      <p class="text-on-surface-variant mb-8 leading-relaxed">
        {{ content['arch_intro']|default('With Phalcon\'s shared-memory model, the framework is parsed once when the server starts — not on every request. That efficiency is why a full app can run happily on hardware that would otherwise feel cramped.') }}
      </p>
      <div class="p-6 bg-background rounded-lg border border-outline-variant font-code-sm text-code-sm">
        <div class="flex gap-2 mb-4"><div class="w-3 h-3 rounded-full bg-error/40"></div><div class="w-3 h-3 rounded-full bg-secondary/40"></div><div class="w-3 h-3 rounded-full bg-primary/40"></div></div>
{% autoescape false %}{{ content['arch_code']|default('<pre class="text-primary"><span class="token-comment"># TAVP footprint</span>
$ tavp phalcon:install
$ userland vendor: ~5MB
$ files parsed / request: <span class="token-function">12</span>
$ P95 latency: <span class="token-function">&lt;5ms</span></pre>') }}{% endautoescape %}
      </div>
    </div>
    <div class="relative aspect-square">
      <div class="absolute inset-0 bg-secondary/5 rounded-full blur-[100px]"></div>
      <div class="relative z-10 h-full w-full flex items-center justify-center">
        <img alt="TAVP" class="w-48 h-48 object-contain drop-shadow-[0_0_40px_rgba(230,196,70,0.25)]" src="/assets/logo.png"/>
      </div>
    </div>
  </div>
</section>

{% endblock %}

{% block scripts %}
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.querySelectorAll('.chart-bar-transition').forEach(function (bar) {
            var target = bar.style.width;
            bar.style.width = '0';
            setTimeout(function () { bar.style.width = target; }, 100);
          });
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.2 });
    document.querySelectorAll('.performance-card-glow').forEach(function (s) { observer.observe(s); });
  });
</script>
{% endblock %}
