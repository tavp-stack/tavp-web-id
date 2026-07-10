{% extends 'layouts/app.volt' %}

{% block content %}
<article class="prose max-w-none">
    <h1 class="text-4xl font-bold tracking-tight">{{ content['title'] }}</h1>
    <div class="mt-6">
        {{ content['body'] }}
    </div>
</article>
{% endblock %}
