{% extends 'layouts/app.volt' %}

{% block content %}
<section class="py-24 max-w-[1280px] mx-auto px-gutter">
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">

    {# Left: Info #}
    <div class="space-y-8">
      <div class="space-y-4">
        <h1 class="text-4xl font-bold font-display font-display text-headline-xl text-on-surface">Contact</h1>
        <p class="text-on-tertiary-container text-lg">Have a question, suggestion, or want to contribute? We'd love to hear from you.</p>
      </div>

      <div class="space-y-6">
        <div class="flex items-start gap-4">
          <span class="material-symbols-outlined text-secondary text-2xl mt-1">code</span>
          <div>
            <h3 class="text-2xl font-semibold font-display font-display text-lg text-on-surface">GitHub</h3>
            <p class="text-on-tertiary-container">Open an issue or start a discussion.</p>
            <a href="https://github.com/tavp-stack" class="font-mono text-sm text-secondary hover:underline mt-2 inline-block">github.com/tavp-stack</a>
          </div>
        </div>

        <div class="flex items-start gap-4">
          <span class="material-symbols-outlined text-secondary text-2xl mt-1">mail</span>
          <div>
            <h3 class="text-2xl font-semibold font-display font-display text-lg text-on-surface">Email</h3>
            <p class="text-on-tertiary-container">For business inquiries or partnerships.</p>
            <a href="mailto:hello@tavp.web.id" class="font-mono text-sm text-secondary hover:underline mt-2 inline-block">hello@tavp.web.id</a>
          </div>
        </div>
      </div>
    </div>

    {# Right: Form #}
    <div class="bg-surface-container border border-outline-variant rounded-xl p-8">
      <form class="space-y-6" action="#" method="POST">
        <div>
          <label class="block text-xs font-semibold uppercase tracking-widest text-label-caps text-on-tertiary-container mb-2 uppercase tracking-widest">Name</label>
          <input type="text" name="name" required class="w-full bg-surface-container-low border border-outline-variant rounded px-4 py-3 text-on-surface focus:border-secondary focus:outline-none transition-colors" placeholder="Your name">
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-widest text-label-caps text-on-tertiary-container mb-2 uppercase tracking-widest">Email</label>
          <input type="email" name="email" required class="w-full bg-surface-container-low border border-outline-variant rounded px-4 py-3 text-on-surface focus:border-secondary focus:outline-none transition-colors" placeholder="you@example.com">
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-widest text-label-caps text-on-tertiary-container mb-2 uppercase tracking-widest">Subject</label>
          <input type="text" name="subject" required class="w-full bg-surface-container-low border border-outline-variant rounded px-4 py-3 text-on-surface focus:border-secondary focus:outline-none transition-colors" placeholder="What's this about?">
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-widest text-on-tertiary-container mb-2">Message</label>
          <textarea name="message" rows="5" required class="w-full bg-surface-container-low border border-outline-variant rounded px-4 py-3 text-on-surface focus:border-secondary focus:outline-none transition-colors resize-none" placeholder="Your message..."></textarea>
        </div>
        {# Honeypot — hidden field, bots will fill this #}
        <div class="absolute -left-[9999px]" aria-hidden="true">
          <input type="text" name="website" tabindex="-1" autocomplete="off">
        </div>
        {# Simple math captcha #}
        <div>
          <label class="block text-xs font-semibold uppercase tracking-widest text-on-tertiary-container mb-2">Verify: What is 3 + 4? (anti-spam)</label>
          <input type="number" name="captcha" required class="w-full bg-surface-container-low border border-outline-variant rounded px-4 py-3 text-on-surface focus:border-secondary focus:outline-none transition-colors" placeholder="7">
        </div>
        <button type="submit" class="w-full bg-secondary text-on-secondary font-bold py-4 hard-shadow hover:translate-y-[-2px] transition-all">
          Send Message
        </button>
      </form>
    </div>

  </div>
</section>
{% endblock %}
