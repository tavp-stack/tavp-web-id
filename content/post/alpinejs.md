---
title: 'Alpine.js: Interaktivitas Tanpa Kompleksitas'
slug: alpinejs
excerpt: 'Alpine.js memberikan interaktivitas JavaScript tanpa perlu build step atau framework berat. Tulis langsung di HTML, hasilnya ringan dan cepat.'
body: 'Alpine.js adalah JavaScript framework yang ringan dan deklaratif. Berbeda dengan React atau Vue, Alpine.js tidak butuh build step — cukup tulis atribut di HTML.'
---

# Alpine.js: Interaktivitas Tanpa Kompleksitas

Alpine.js adalah JavaScript framework yang ringan dan deklaratif. Berbeda dengan React atau Vue, Alpine.js tidak butuh build step — cukup tulis atribut di HTML.

## Apa itu Alpine.js?

Alpine.js memberikan interaktivitas JavaScript tanpa kompleksitas framework berat. ukurannya hanya ~15KB, dan tidak perlu npm install atau build process.

```html
<div x-data="{ count: 0 }">
  <button @click="count++">Klik: <span x-text="count"></span></button>
</div>
```

## Kenapa Alpine.js?

1. **Tanpa build step** — Tulis langsung di HTML, tidak perlu compile
2. **Ringan** — Hanya ~15KB, jauh lebih kecil dari React/Vue
3. **Deklaratif** — Baca HTML, langsung pahami logikanya
4. **Kompatibel** — Bekerja dengan framework CSS apapun (termasuk Tailwind)

## Fitur Utama

### Reactivity
```html
<div x-data="{ open: false }">
  <button @click="open = !open">Toggle</button>
  <div x-show="open" x-transition>
    Content muncul/hilang dengan animasi
  </div>
</div>
```

### HTTP Requests
```html
<div x-data="{ data: [] }" x-init="data = await (await fetch('/api/data')).json()">
  <template x-for="item in data">
    <p x-text="item.name"></p>
  </template>
</div>
```

### Keyboard Shortcuts
```html
<div x-data @keydown.escape="open = false">
  <!-- Modal akan tutup saat ESC ditekan -->
</div>
```

## Alpine.js vs React vs Vue

| Aspek | Alpine.js | React | Vue |
|-------|-----------|-------|-----|
| Ukuran | ~15KB | ~40KB | ~30KB |
| Build step | Tidak | Wajib | Opsional |
| Learning curve | Rendah | Sedang | Sedang |
| Cocok untuk | Progressive enhancement | SPA | SPA |

## Kesimpulan

Alpine.js adalah pilihan sempurna untuk proyek yang butuh interaktivitas tanpa kompleksitas. Cocok untuk website statis yang butuh sedikit interaktivitas, atau sebagai pengganti jQuery.

Pelajari lebih lanjut di [docs.tavp.web.id](https://docs.tavp.web.id).
