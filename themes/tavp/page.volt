{% extends 'layouts/app.volt' %}

{% block content %}
<article class="prose max-w-none">
    <h1 class="font-headline-xl text-headline-xl tracking-tight">{{ content['title'] }}</h1>
    <div class="mt-6">
        {{ content['body'] }}
    </div>
</article>
{% endblock %}
