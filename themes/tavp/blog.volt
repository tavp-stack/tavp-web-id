{% extends 'layouts/app.volt' %}

{% block content %}
<h1 class="text-4xl font-bold tracking-tight">Blog</h1>
<div class="mt-8 grid gap-8">
    {% for post in posts %}
        <a href="/blog/{{ post['slug'] }}" class="block rounded-lg border border-gray-100 p-6 hover:border-gray-300">
            <h2 class="text-xl font-semibold">{{ post['title'] }}</h2>
            {% if post['excerpt'] is defined %}
                <p class="mt-2 text-gray-600">{{ post['excerpt'] }}</p>
            {% endif %}
        </a>
    {% endfor %}
</div>
{% endblock %}
