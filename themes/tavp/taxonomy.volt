{% extends 'layouts/app.volt' %}

{% block content %}
<section class="py-24 max-w-[1280px] mx-auto px-gutter">
  <div class="space-y-8">
    <div class="space-y-4">
      <a href="/blog" class="inline-flex items-center gap-2 text-sm text-on-tertiary-container hover:text-secondary transition-colors">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        Back to Blog
      </a>
      <div class="inline-flex items-center gap-2 px-3 py-1 bg-surface-container-low border border-outline-variant rounded-full">
        <span class="font-label-caps text-label-caps text-secondary uppercase tracking-widest">{{ type }}</span>
      </div>
      <h1 class="font-headline-xl text-headline-xl text-on-surface">{{ term['name'] }}</h1>
      <p class="text-on-tertiary-container">{{ posts|length }} post{{ posts|length != 1 ? 's' : '' }} in this {{ type }}.</p>
    </div>

    {% if posts is empty %}
      <div class="py-16 text-center border border-outline-variant rounded-xl bg-surface-container">
        <span class="material-symbols-outlined text-secondary text-5xl mb-4">article</span>
        <p class="text-on-tertiary-container">No posts found in this {{ type }}.</p>
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
              {% if post['published_at'] is defined and post['published_at'] %}
                <?php
                $dt = new \DateTime($post['published_at']);
                $idMonths = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
                ?>
                <div class="flex items-center gap-2 text-xs text-on-surface-variant">
                  <span class="font-code-sm text-code-sm"><?= $dt->format('j') ?> <?= $idMonths[(int)$dt->format('n') - 1] ?> <?= $dt->format('Y') ?></span>
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
