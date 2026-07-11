{% extends 'layouts/app.volt' %}

{% block content %}
<article class="py-24 max-w-[800px] mx-auto px-gutter">
  <header class="mb-12 space-y-4">
    <a href="/blog" class="inline-flex items-center gap-2 text-sm text-on-tertiary-container hover:text-secondary transition-colors">
      <span class="material-symbols-outlined text-sm">arrow_back</span>
      Back to Blog
    </a>
    <h1 class="font-headline-xl text-headline-xl text-headline-xl text-on-surface">{{ content['title'] }}</h1>
    <div class="flex items-center gap-4 text-sm text-on-tertiary-container">
      {% if content['published_at'] is defined and content['published_at'] %}
        <span class="font-code-sm text-code-sm">{{ content['published_at'] | date('d M Y') }}</span>
      {% endif %}
      {% if content['categories'] is defined and content['categories'] %}
        {% for cat in content['categories'] %}
          <span class="px-2 py-0.5 rounded-full bg-secondary-container text-on-secondary text-xs font-label-caps text-label-caps">{{ cat['name'] }}</span>
        {% endfor %}
      {% endif %}
    </div>
    {% if content['excerpt'] is defined and content['excerpt'] %}
      <p class="text-lg text-on-surface-variant leading-relaxed">{{ content['excerpt'] }}</p>
    {% endif %}
  </header>

  {% if content['featured_image'] is defined and content['featured_image'] %}
    <div class="mb-12 overflow-hidden rounded-xl border border-outline-variant">
      <img src="/uploads/{{ content['featured_image'] }}" alt="{{ content['title'] }}" class="w-full h-auto">
    </div>
  {% endif %}

  <div class="prose prose-invert max-w-none">
    {{ content['body'] }}
  </div>

  {% if content['tags'] is defined and content['tags'] %}
    <div class="mt-12 pt-8 border-t border-outline-variant">
      <div class="flex flex-wrap gap-2">
        {% for tag in content['tags'] %}
          <span class="px-3 py-1 rounded-full bg-surface-container-low border border-outline-variant text-sm text-on-tertiary-container">#{{ tag['name'] }}</span>
        {% endfor %}
      </div>
    </div>
  {% endif %}
</article>
{% endblock %}
