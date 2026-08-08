{% extends 'layouts/app.volt' %}

{% block content %}
<section class="py-24 max-w-[1280px] mx-auto px-gutter">
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">

    {# Left: Info #}
    <div class="space-y-8">
      <div class="space-y-4">
        <h1 class="font-headline-xl text-headline-xl text-on-surface">{{ content['page_title']|default('Contact') }}</h1>
        <p class="text-on-tertiary-container text-lg">{{ content['intro']|default("Have a question, suggestion, or want to contribute? We'd love to hear from you.") }}</p>
      </div>

      <div class="space-y-6">
        <div class="flex items-start gap-4">
          <span class="material-symbols-outlined text-secondary text-2xl mt-1">code</span>
          <div>
            <h3 class="font-headline-lg text-headline-lg text-on-surface">{{ content['github_title']|default('GitHub') }}</h3>
            <p class="text-on-tertiary-container">{{ content['github_desc']|default('Open an issue or start a discussion.') }}</p>
            <a href="https://{{ content['github_url']|default('github.com/tavp-stack') }}" class="font-code-sm text-secondary hover:underline mt-2 inline-block">{{ content['github_url']|default('github.com/tavp-stack') }}</a>
          </div>
        </div>

        <div class="flex items-start gap-4" x-data="{ revealed: false, answer: '' }">
          <span class="material-symbols-outlined text-secondary text-2xl mt-1">mail</span>
          <div>
            <h3 class="font-headline-lg text-headline-lg text-on-surface">{{ content['email_title']|default('Email') }}</h3>
            <p class="text-on-tertiary-container">{{ content['email_desc']|default('For business inquiries or partnerships.') }}</p>
            <template x-if="!revealed">
              <div class="mt-2">
                <p class="font-code-sm text-on-surface-variant text-sm mb-2">Solve to reveal email:</p>
                <div class="flex items-center gap-2">
                  <span class="font-code-sm text-secondary" x-text="'What is 2 × 3?'"></span>
                  <input type="number" x-model="answer" class="w-20 bg-surface-container-low border border-outline-variant rounded px-2 py-1 text-on-surface text-sm focus:border-secondary outline-none" placeholder="?">
                  <button @click="if(answer == '6') revealed = true" class="text-secondary font-label-caps text-label-caps hover:underline">Reveal</button>
                </div>
              </div>
            </template>
            <template x-if="revealed">
              <a href="mailto:{{ content['email_address']|default('hello@tavp.web.id') }}" class="font-code-sm text-secondary mt-2 inline-block hover:underline">{{ content['email_address']|default('hello@tavp.web.id') }}</a>
            </template>
          </div>
        </div>
      </div>
    </div>

    {# Right: Form #}
    <div class="bg-surface-container border border-outline-variant rounded-xl p-8">
      <form class="space-y-6" action="/contact" method="POST">
        <div>
          <label class="block font-label-caps text-label-caps text-on-tertiary-container mb-2">NAME</label>
          <input type="text" name="name" required class="w-full bg-surface-container-low border border-outline-variant rounded px-4 py-3 text-on-surface focus:border-secondary focus:outline-none transition-colors" placeholder="Your name">
        </div>
        <div>
          <label class="block font-label-caps text-label-caps text-on-tertiary-container mb-2">EMAIL</label>
          <input type="email" name="email" required class="w-full bg-surface-container-low border border-outline-variant rounded px-4 py-3 text-on-surface focus:border-secondary focus:outline-none transition-colors" placeholder="you@example.com">
        </div>
        <div>
          <label class="block font-label-caps text-label-caps text-on-tertiary-container mb-2">SUBJECT</label>
          <input type="text" name="subject" required class="w-full bg-surface-container-low border border-outline-variant rounded px-4 py-3 text-on-surface focus:border-secondary focus:outline-none transition-colors" placeholder="What's this about?">
        </div>
        <div>
          <label class="block font-label-caps text-label-caps text-on-tertiary-container mb-2">MESSAGE</label>
          <textarea name="message" rows="5" required class="w-full bg-surface-container-low border border-outline-variant rounded px-4 py-3 text-on-surface focus:border-secondary focus:outline-none transition-colors resize-none" placeholder="Your message..."></textarea>
        </div>
        {# Honeypot — hidden field, bots will fill this #}
        <div class="absolute -left-[9999px]" aria-hidden="true">
          <input type="text" name="website" tabindex="-1" autocomplete="off">
        </div>
        {# Dynamic math captcha #}
        <div>
          <label class="block font-label-caps text-label-caps text-on-tertiary-container mb-2">VERIFY (anti-spam)</label>
          <div class="flex items-center gap-4">
            <span class="font-code-sm text-secondary" id="captcha-question">{{ captcha_question }}</span>
            <input type="number" name="captcha" required class="flex-1 bg-surface-container-low border border-outline-variant rounded px-4 py-3 text-on-surface focus:border-secondary focus:outline-none transition-colors" placeholder="Answer">
          </div>
          <input type="hidden" name="captcha_hash" value="{{ captcha_hash }}">
        </div>
        <button type="submit" class="w-full bg-secondary text-on-secondary font-bold py-4 hard-shadow hover:translate-y-[-2px] transition-all">
          {{ content['form_button']|default('Send Message') }}
        </button>
      </form>
    </div>

  </div>
</section>
{% endblock %}
