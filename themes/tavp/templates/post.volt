{% extends 'layouts/app.volt' %}

{% block content %}
<article class="prose max-w-none">
    <h1 class="text-4xl font-bold tracking-tight">{{ content['title'] }}</h1>
    {% if content['published_at'] is defined and content['published_at'] %}
        <p class="mt-2 text-sm text-gray-500">{{ content['published_at'] | date('d M Y') }}</p>
    {% endif %}
    <div class="mt-6">
        {{ content['body'] }}
    </div>
</article>
{% endblock %}
