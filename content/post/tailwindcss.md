---
title: 'Tailwind CSS: Utility-First yang Mengubah Cara kita Menulis CSS'
slug: tailwindcss
excerpt: 'Tailwind CSS adalah CSS framework yang menggunakan pendekatan utility-first. Tulis CSS langsung di HTML tanpa perlu buat file CSS terpisah.'
body: 'Tailwind CSS adalah CSS framework yang menggunakan pendekatan utility-first. Setiap class adalah satu property CSS yang bisa dikombinasikan sesuai kebutuhan.'
---

# Tailwind CSS: Utility-First yang Mengubah Cara kita Menulis CSS

Tailwind CSS adalah CSS framework yang menggunakan pendekatan utility-first. Setiap class adalah satu property CSS yang bisa dikombinasikan sesuai kebutuhan.

## Apa itu Tailwind CSS?

Berbeda dengan Bootstrap yang memberikan class-component (seperti `.btn-primary`), Tailwind memberikan class-utility (seperti `bg-blue-500 text-white px-4 py-2`). Hasilnya: CSS yang lebih fleksibel dan tidak perlu override style.

```html
<!-- Bootstrap -->
<button class="btn btn-primary">Click</button>

<!-- Tailwind -->
<button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
  Click
</button>
```

## Kenapa Tailwind?

1. **Fleksibel** — Tidak ada default style yang perlu di-override
2. **Kecil** — Hanya class yang dipakai yang masuk ke production CSS
3. **Konsisten** — Design system terpusat di konfigurasi
4. **Responsive** — Mudah buat breakpoint: `md:text-lg lg:text-xl`

## Fitur Utama

### Responsive Design
```html
<div class="text-sm md:text-base lg:text-lg">
  Font size berubah sesuai layar
</div>
```

### Dark Mode
```html
<div class="bg-white dark:bg-gray-900 text-black dark:text-white">
  Automatis switch sesuai preferensi user
</div>
```

### Custom Utilities
```css
/* Di tailwind.config.js */
theme: {
  extend: {
    colors: {
      'primary': '#e6c446',
    }
  }
}
```

```html
<!-- Gunakan custom color -->
<button class="bg-primary text-black">Click</button>
```

## Tailwind vs Bootstrap vs CSS Biasa

| Aspek | Tailwind | Bootstrap | CSS Biasa |
|-------|----------|-----------|-----------|
| Pendekatan | Utility-first | Component-based | Manual |
| Ukuran CSS | Kecil (purge) | Besar | Sesuai kebutuhan |
| Fleksibilitas | Tinggi | Sedang | Tinggi |
| Learning curve | Sedang | Rendah | Rendah |

## Best Practices

1. **Gunakan `@apply`** untuk style berulang
2. **Konsisten** dengan spacing dan color palette
3. **Purge** class yang tidak dipakai di production
4. **Gunakan Prettier plugin** untuk format otomatis

## Kesimpulan

Tailwind CSS mengubah cara kita menulis CSS. Dengan pendekatan utility-first, kita bisa membuat desain yang konsisten tanpa perlu menulis CSS manual. Cocok untuk proyek apapun, dari landing page hingga web app kompleks.

Pelajari lebih lanjut di [docs.tavp.web.id](https://docs.tavp.web.id).
