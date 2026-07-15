{% extends 'layouts/app.volt' %}

{% block content %}
<section class="py-24 max-w-[800px] mx-auto px-gutter">
  <article class="space-y-8">
    <header class="space-y-4">
      <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">{{ content['title'] }}</h1>
    </header>
    <div class="content-body text-on-surface-variant leading-relaxed">
      {% autoescape false %}{{ content['body'] }}{% endautoescape %}
    </div>
  </article>
</section>
{% endblock %}
