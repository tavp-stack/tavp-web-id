{% extends 'layouts/app.volt' %}

{% block content %}
<article class="prose max-w-none">
    <h1 class="font-headline-xl text-headline-xl tracking-tight">{{ content['title'] }}</h1>
    <div class="mt-6 prose prose-invert max-w-none">
        {% autoescape false %}{{ content['body'] }}{% endautoescape %}
    </div>
</article>
{% endblock %}
