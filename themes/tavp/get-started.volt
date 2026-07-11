{% extends 'layouts/app.volt' %}

{% block content %}
<div class="pt-16 pb-20 px-6 md:px-12 max-w-[1280px] mx-auto">

  <header class="mb-16">
    <div class="inline-flex items-center gap-2 px-3 py-1 bg-primary-container text-secondary font-label-caps text-label-caps text-label-caps border border-secondary/30 rounded-full mb-6">
      <span class="relative flex h-2 w-2">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
        <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary"></span>
      </span>
      STABLE RELEASE V1.0
    </div>
    <h1 class="font-headline-xl text-headline-xl text-headline-xl mb-4">Installation Guide</h1>
    <p class="text-on-tertiary-container max-w-2xl">
      Set up the TAVP stack on your local environment or production server in a few minutes. Thin, light, and low-latency by default.
    </p>
  </header>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
    <div class="lg:col-span-8 space-y-16">

      {# Step 1 #}
      <section class="relative pl-12 border-l border-outline-variant">
        <div class="absolute -left-6 top-0 w-12 h-12 bg-surface-container border border-outline-variant flex items-center justify-center font-headline-lg text-headline-lg text-headline-lg step-number">1</div>
        <div class="mb-6">
          <h2 class="font-headline-lg text-headline-lg text-headline-lg text-on-surface mb-2">Install the Phalcon Extension</h2>
          <p class="text-on-tertiary-container">Phalcon is a C-extension for PHP — the backbone of the stack. The TAVP CLI installs it for you.</p>
        </div>
        <div class="rounded-lg overflow-hidden border border-outline-variant code-glow">
          <div class="bg-surface-container px-4 py-2 flex justify-between items-center border-b border-outline-variant">
            <span class="font-label-caps text-label-caps text-label-caps text-on-tertiary-container">TERMINAL</span>
            <span class="material-symbols-outlined text-on-tertiary-container text-sm">content_copy</span>
          </div>
          <div class="p-4 font-code-sm text-code-sm text-code-sm bg-background">
            <code class="block">
              <span class="token-comment"># Install the TAVP CLI globally</span><br/>
              composer global require tavp/cli<br/><br/>
              <span class="token-comment"># Install the Phalcon C-extension</span><br/>
              tavp phalcon:install
            </code>
          </div>
        </div>
      </section>

      {# Step 2 #}
      <section class="relative pl-12 border-l border-outline-variant">
        <div class="absolute -left-6 top-0 w-12 h-12 bg-surface-container border border-outline-variant flex items-center justify-center font-headline-lg text-headline-lg text-headline-lg step-number">2</div>
        <div class="mb-6">
          <h2 class="font-headline-lg text-headline-lg text-headline-lg text-on-surface mb-2">Create Your Project</h2>
          <p class="text-on-tertiary-container">Bootstrap a new app with Composer, then start the development server.</p>
        </div>
        <div class="rounded-lg overflow-hidden border border-outline-variant code-glow">
          <div class="bg-surface-container px-4 py-2 flex justify-between items-center border-b border-outline-variant">
            <span class="font-label-caps text-label-caps text-label-caps text-on-tertiary-container">BASH</span>
            <span class="material-symbols-outlined text-on-tertiary-container text-sm">content_copy</span>
          </div>
          <div class="p-4 font-code-sm text-code-sm text-code-sm bg-background">
            <code class="block">
              composer create-project tavp/core my-app<br/>
              <span class="token-keyword">cd</span> my-app<br/>
              tavp serve
            </code>
          </div>
        </div>
      </section>

      {# Step 3 #}
      <section class="relative pl-12 border-l border-outline-variant">
        <div class="absolute -left-6 top-0 w-12 h-12 bg-surface-container border border-outline-variant flex items-center justify-center font-headline-lg text-headline-lg step-number">3</div>
        <div class="mb-6">
          <h2 class="font-headline-lg text-headline-lg text-on-surface mb-2">TAVPblocks — UI Components</h2>
          <p class="text-on-tertiary-container">No need to install Tailwind or Alpine manually. TAVPblocks includes 40+ pre-built UI components (buttons, modals, forms, cards, charts, etc.) that work out of the box with Tailwind CSS and Alpine.js.</p>
        </div>
        <div class="rounded-lg overflow-hidden border border-outline-variant code-glow">
          <div class="bg-surface-container px-4 py-2 flex justify-between items-center border-b border-outline-variant">
            <span class="font-label-caps text-label-caps text-on-tertiary-container">TERMINAL</span>
            <span class="material-symbols-outlined text-on-tertiary-container text-sm">content_copy</span>
          </div>
          <div class="p-4 font-code-sm text-code-sm bg-background">
            <code class="block">
              <span class="token-comment"># TAVPblocks is included via composer</span><br/>
              composer require tavp/tavpblocks<br/><br/>
              <span class="token-comment"># Components available out of the box:</span><br/>
              Button, Input, Select, Modal, Card,<br/>
              Chart, Datatable, Form, Pagination...
            </code>
          </div>
        </div>
      </section>

      {# Hello World #}
      <section class="mt-24 p-8 bg-surface-container-low border-2 border-secondary/20 rounded-xl">
        <div class="flex items-center gap-3 mb-8">
          <div class="w-10 h-10 bg-secondary/10 flex items-center justify-center rounded text-secondary">
            <span class="material-symbols-outlined">rocket_launch</span>
          </div>
          <h2 class="font-headline-lg text-headline-lg text-headline-lg">Hello World in Volt</h2>
        </div>
        <p class="text-on-tertiary-container mb-6">Volt compiles to plain PHP for speed. Here is a simple counter using Alpine.js inside a Volt template.</p>
        <div class="rounded-lg overflow-hidden border border-outline-variant code-glow bg-background">
          <div class="bg-surface-container-high px-4 py-2 border-b border-outline-variant flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-error/40"></div>
            <div class="w-3 h-3 rounded-full bg-secondary/40"></div>
            <div class="w-3 h-3 rounded-full bg-on-tertiary-container/40"></div>
            <span class="ml-auto font-label-caps text-label-caps text-label-caps text-on-tertiary-container">views/index.volt</span>
          </div>
          <div class="p-6 font-code-sm text-code-sm text-code-sm">
<pre class="border-none !bg-transparent"><span class="token-comment">&lt;!-- Volt + Alpine.js --&gt;</span>
&lt;div <span class="token-keyword">x-data</span>="{ count: 0 }" class="p-8 text-center"&gt;
    &lt;h1 class="text-3xl font-bold"&gt;
        &#123;&#123; <span class="token-string">"Hello, "</span> ~ user_name &#125;&#125;
    &lt;/h1&gt;

    &lt;button <span class="token-keyword">@click</span>="count++" class="bg-secondary text-black px-4 py-2 mt-4"&gt;
        Clicks: &lt;span <span class="token-keyword">x-text</span>="count"&gt;&lt;/span&gt;
    &lt;/button&gt;
&lt;/div&gt;</pre>
          </div>
        </div>
      </section>
    </div>

    {# Sidebar #}
    <aside class="lg:col-span-4">
      <div class="sticky top-24 space-y-8">
        <div class="p-6 bg-surface-container rounded-xl border-t-2 border-secondary">
          <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-secondary">memory</span>
            <h3 class="font-headline-lg text-headline-lg text-[20px] font-bold">VPS Optimization</h3>
          </div>
          <p class="text-sm text-on-tertiary-container mb-6">Running on a $5/mo droplet? TAVP is designed for exactly that. A few tips to squeeze out every drop:</p>
          <ul class="space-y-4">
            <li class="flex gap-3">
              <span class="material-symbols-outlined text-secondary text-sm shrink-0">check_circle</span>
              <div class="text-sm"><span class="block font-bold text-on-surface">Enable OPcache</span><span class="text-on-tertiary-container">Set <code>opcache.enable=1</code> in php.ini for script caching.</span></div>
            </li>
            <li class="flex gap-3">
              <span class="material-symbols-outlined text-secondary text-sm shrink-0">check_circle</span>
              <div class="text-sm"><span class="block font-bold text-on-surface">Disable View Stat</span><span class="text-on-tertiary-container">Turn off Volt file-existence checks in production.</span></div>
            </li>
            <li class="flex gap-3">
              <span class="material-symbols-outlined text-secondary text-sm shrink-0">check_circle</span>
              <div class="text-sm"><span class="block font-bold text-on-surface">Lean Memory</span><span class="text-on-tertiary-container">Phalcon is a C-extension, so PHP's memory_limit can stay low.</span></div>
            </li>
            <li class="flex gap-3">
              <span class="material-symbols-outlined text-secondary text-sm shrink-0">check_circle</span>
              <div class="text-sm"><span class="block font-bold text-on-surface">Serve Static Assets</span><span class="text-on-tertiary-container">Let Nginx serve compiled CSS/JS directly.</span></div>
            </li>
          </ul>
        </div>

        <div class="p-6 bg-surface-container-high rounded-xl border border-outline-variant overflow-hidden relative">
          <div class="relative z-10">
            <h4 class="font-headline-lg text-headline-lg text-[18px] mb-2">Need Help?</h4>
            <p class="text-sm text-on-tertiary-container mb-4">Read the full documentation or join the community.</p>
            <a href="https://docs.tavp.web.id/index.html" class="block text-center w-full py-2 bg-on-surface text-background font-bold text-sm rounded hover:opacity-90 transition-all">Open the Docs</a>
          </div>
          <span class="material-symbols-outlined absolute -bottom-4 -right-4 text-9xl opacity-5 select-none">forum</span>
        </div>
      </div>
    </aside>
  </div>
</div>
{% endblock %}
