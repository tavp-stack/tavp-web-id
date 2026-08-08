{% extends 'layouts/app.volt' %}

{% block content %}
<section class="py-24 max-w-[1280px] mx-auto px-gutter">
  <div class="space-y-8">
    <div class="space-y-4">
      <h1 class="font-headline-xl text-headline-xl text-headline-xl text-on-surface">Blog</h1>
      <p class="text-on-tertiary-container">Latest posts from the TAVP Stack.</p>
    </div>

    {% if posts is empty %}
      <div class="py-16 text-center border border-outline-variant rounded-xl bg-surface-container">
        <span class="material-symbols-outlined text-secondary text-5xl mb-4">article</span>
        <p class="text-on-tertiary-container">No posts published yet.</p>
      </div>
    {% else %}
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {% for post in posts %}
          <a href="/blog/{{ post['slug'] }}" class="block bg-surface-container border border-outline-variant rounded-xl p-6 hover:border-secondary transition-colors group">
            {% if post['featured_image'] is defined and post['featured_image'] %}
              <div class="mb-4 overflow-hidden rounded-lg">
                <img src="/uploads/{{ post['featured_image'] }}" alt="{{ post['title'] }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
              </div>
            {% endif %}
            <div class="space-y-3">
              <h2 class="font-headline-lg text-headline-lg text-lg text-on-surface group-hover:text-secondary transition-colors">{{ post['title'] }}</h2>
              {% if post['excerpt'] is defined and post['excerpt'] %}
                <p class="text-sm text-on-tertiary-container line-clamp-3">{{ post['excerpt'] }}</p>
              {% endif %}
                            <div class="flex items-center gap-2 text-xs text-on-surface-variant">
                <span class="font-code-sm text-code-sm">{{ post['slug'] }}</span>
              </div>
              {% if post['categories'] is defined and post['categories'] %}
                <div class="flex flex-wrap gap-1 mt-2">
                  {% for category in post['categories'] %}
                    <a href="/blog/category/{{ category }}" class="px-2 py-1 text-xs rounded-full bg-surface-container-low border border-outline-variant text-on-tertiary-container hover:border-secondary transition-colors">
                      {{ category }}
                    </a>
                  {% endfor %}
                </div>
              {% endif %}
              {% if post['tags'] is defined and post['tags'] %}
                <div class="flex flex-wrap gap-1 mt-1">
                  {% for tag in post['tags'] %}
                    <a href="/blog/tag/{{ tag }}" class="px-2 py-1 text-xs rounded-full bg-surface-container-low border border-outline-variant text-on-tertiary-container hover:border-secondary transition-colors">
                      #{{ tag }}
                    </a>
                  {% endfor %}
                </div>
              {% endif %}
            </div>
          </a>
        {% endfor %}
      </div>
    {% endif %}
  </div>
</section>
{% endblock %}
