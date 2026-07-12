{% extends "layouts/app.volt" %}

{% block content %}
<section class="py-24 max-w-[1280px] mx-auto px-gutter">
  <h1>Taxonomy Archive</h1>
  <p>Type: {{ type }}</p>
  <p>Term: {{ term["name"] }}</p>
  <p>Posts count: {{ posts|length }}</p>
  
  {% if posts is not empty %}
    <ul>
    {% for post in posts %}
      <li>{{ post["title"] }} - {{ post["slug"] }}</li>
    {% endfor %}
    </ul>
  {% endif %}
</section>
{% endblock %}
