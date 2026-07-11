{% extends 'layouts/app.volt' %}

{% block content %}
<section class="min-h-[80vh] flex flex-col items-center justify-center text-center px-gutter">
  <div class="space-y-8 max-w-lg">
    <div class="font-headline-xl text-[120px] font-bold text-error leading-none">500</div>
    <h1 class="font-headline-xl text-headline-xl text-on-surface">Server Error</h1>
    <p class="font-body-md text-body-md text-on-tertiary-container">Something went wrong on our end. We're working to fix it.</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
      <a href="/" class="bg-secondary text-on-secondary font-bold font-headline-lg py-3 px-8 hard-shadow hover:translate-y-[-2px] transition-all">
        Go Home
      </a>
      <a href="javascript:location.reload()" class="border border-outline-variant text-on-surface font-bold font-headline-lg py-3 px-8 hover:bg-surface-container-high transition-colors">
        Try Again
      </a>
    </div>
  </div>
</section>
{% endblock %}
