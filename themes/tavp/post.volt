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
         <?php
         $dt = new \DateTime($content['published_at']);
         $idMonths = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
         $day = $dt->format('j');
         $month = $idMonths[(int)$dt->format('n') - 1];
         $year = $dt->format('Y');
         $bodyText = strip_tags($content['body'] ?? '');
         $wordCount = str_word_count($bodyText);
         $readMin = max(1, ceil($wordCount / 200));
         $author = $content['author'] ?? 'Jeremy Cheng';
         ?>
         <span class="font-code-sm text-code-sm"><?= $day ?> <?= $month ?> <?= $year ?></span>
         <span class="font-code-sm text-code-sm">·</span>
         <span class="font-code-sm text-code-sm"><?= $readMin ?> min read</span>
         <span class="font-code-sm text-code-sm">·</span>
          <span class="font-code-sm text-code-sm"><?= htmlspecialchars($author, ENT_QUOTES) ?></span>
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

   <div class="max-w-none content-body">
     {% autoescape false %}{{ content['body'] }}{% endautoescape %}
   </div>

   <!-- Mermaid: convert <pre><code class="language-mermaid"> to <div class="mermaid"> -->
   <script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
   <script>
     document.querySelectorAll('pre code.language-mermaid').forEach(el => {
       const pre = el.closest('pre');
       if (pre) {
         const div = document.createElement('div');
         div.className = 'mermaid';
         div.textContent = el.textContent;
         pre.replaceWith(div);
       }
     });
     if (window.mermaid) {
       mermaid.initialize({ startOnLoad: false });
       mermaid.run();
     }
   </script>

<style>
.content-body h1, .content-body h2, .content-body h3, .content-body h4 {
  margin-top: 1.5em;
  margin-bottom: 0.5em;
  font-weight: 700;
  line-height: 1.3;
}
.content-body h1 { font-size: 2rem; }
.content-body h2 { font-size: 1.5rem; color: #E6C446; }
.content-body h3 { font-size: 1.25rem; }
.content-body p { margin-bottom: 1em; line-height: 1.7; }
.content-body ul { list-style: disc; padding-left: 1.5em; margin-bottom: 1em; }
.content-body ol { list-style: decimal; padding-left: 1.5em; margin-bottom: 1em; }
.content-body a { color: #E6C446; text-decoration: underline; }
.content-body strong { font-weight: 700; }
.content-body em { font-style: italic; }
.content-body blockquote { border-left: 3px solid #E6C446; padding-left: 1em; margin: 1em 0; color: #a0a0a0; }
.content-body code {
  background: #1a1a1a;
  color: #E6C446;
  padding: 0.2em 0.4em;
  border-radius: 4px;
  font-size: 0.9em;
  font-family: 'Courier New', monospace;
}
.content-body pre {
  background: #1a1a1a;
  color: #e0e0e0;
  padding: 1em;
  border-radius: 8px;
  overflow-x: auto;
  margin: 1em 0;
  border: 1px solid #333;
}
.content-body pre code {
  background: none;
  color: inherit;
  padding: 0;
  border-radius: 0;
  font-size: 0.85em;
}
.content-body img { max-width: 100%; border-radius: 8px; margin: 1em 0; }
.content-body table { border-collapse: collapse; width: 100%; margin: 1em 0; }
.content-body th, .content-body td { border: 1px solid #333; padding: 0.5em 0.75em; text-align: left; }
.content-body th { background: #1a1a1a; }
</style>

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
