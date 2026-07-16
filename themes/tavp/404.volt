{% extends 'layouts/app.volt' %}

{% block content %}
<?php
$bread = app()->getService(\Tavp\Cms\Bread\BreadManager::class) ?? null;
$records = $bread ? $bread->browse('error_404') : [];
$err = $records[0] ?? [];
?>
<section class="min-h-[80vh] flex flex-col items-center justify-center text-center px-gutter">
  <div class="space-y-8 max-w-lg">
    <div class="font-headline-xl text-[120px] font-bold text-secondary leading-none"><?= htmlspecialchars($err['title'] ?? '404') ?></div>
    <h1 class="font-headline-xl text-headline-xl text-on-surface"><?= htmlspecialchars($err['subtitle'] ?? 'Page Not Found') ?></h1>
    <p class="font-body-md text-body-md text-on-tertiary-container"><?= htmlspecialchars($err['description'] ?? "The page you're looking for doesn't exist or has been moved.") ?></p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
      <a href="/" class="bg-secondary text-on-secondary font-bold font-headline-lg py-3 px-8 hard-shadow hover:translate-y-[-2px] transition-all">
        <?= htmlspecialchars($err['btn_home_text'] ?? 'Go Home') ?>
      </a>
      <a href="/" class="border border-outline-variant text-on-surface font-bold font-headline-lg py-3 px-8 hover:bg-surface-container-high transition-colors">
        <?= htmlspecialchars($err['btn_back_text'] ?? 'Back to Safety') ?>
      </a>
    </div>
  </div>
</section>
{% endblock %}
