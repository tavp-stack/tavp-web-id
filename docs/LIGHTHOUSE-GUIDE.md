# TAVP Stack — Lighthouse Optimization Guide

> Panduan lengkap untuk memaksimalkan skor Lighthouse (Performance, Accessibility, Best Practices, SEO) saat menggunakan TAVP Stack.

---

## Arsitektur & Kenapa TAVP Cepat

```
Client → Nginx → PHP-FPM → Phalcon (C-extension) → DB → Response
```

**Server-side**: Phalcon = C-extension, bukan PHP interpreter. Response time <5ms.

**Client-side**: Tailwind CSS + Alpine.js + Volt template = minimal JavaScript, no SPA overhead.

**Tapi**: Lighthouse mengukur **client-side performance**, bukan server speed. Jadi optimasi harus di sisi client.

---

## 📊 Lighthouse Metrics

| Metric | Weight | Target | Cara Capai |
|--------|--------|--------|------------|
| **TTFB** (Time to First Byte) | 30% | <200ms | Deploy ke production VPS, bukan local container |
| **FCP** (First Contentful Paint) | 20% | <1.8s | Inline critical CSS |
| **LCP** (Largest Contentful Paint) | 25% | <2.5s | Optimize images, font loading |
| **CLS** (Cumulative Layout Shift) | 15% | <0.1 | Set explicit dimensions, font-display: swap |
| **TTI** (Time to Interactive) | 10% | <3.8s | Defer non-critical JS |

---

## 1. Performance Optimization

### 1.1 Build Tailwind CSS Offline (WAJIB)

**JANGAN** pakai `<script src="https://cdn.tailwindcss.com">` — ini 300KB+ runtime compiler.

```bash
# Install
npm install tailwindcss @tailwindcss/typography --save-dev

# Build
npx tailwindcss -i resources/css/app.css -o public/assets/app.css --minify

# Watch mode (development)
npx tailwindcss -i resources/css/app.css -o public/assets/app.css --watch
```

**Config** (`tailwind.config.js`):
```javascript
module.exports = {
  content: ['./themes/**/*.volt', './public/**/*.html'],
  darkMode: 'class',
  theme: { extend: { /* DESIGN.md colors */ } },
  plugins: [require('@tailwindcss/typography')],
}
```

**Hasil**: 300KB+ → 20-30KB

### 1.2 Self-host Fonts (WAJIB)

**JANGAN** pakai Google Fonts CDN — render-blocking.

```html
<!-- ❌ JANGAN -->
<link href="https://fonts.googleapis.com/css2?family=Inter..." rel="stylesheet"/>

<!-- ✅ PAKAI -->
<link rel="stylesheet" href="/assets/fonts.css"/>
```

Download font files (.woff2), buat `fonts.css` dengan `@font-face`.

### 1.3 Inline Critical CSS

Extract CSS untuk above-the-fold content, inline di `<head>`:

```html
<style><?php readfile(base_path('public/assets/critical.css')); ?></style>
<link rel="stylesheet" href="/assets/app.css" media="print" onload="this.media='all'"/>
<noscript><link rel="stylesheet" href="/assets/app.css"/></noscript>
```

### 1.4 Defer Non-critical Scripts

```html
<!-- ✅ Defer semua script non-critical -->
<script defer src="/js/alpine.min.js"></script>
<script defer src="/js/prism-bundle.js"></script>

<!-- ❌ JANGAN tanpa defer -->
<script src="https://cdn.tailwindcss.com"></script>
```

### 1.5 Self-host JavaScript

```html
<!-- ❌ JANGAN dari CDN -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- ✅ PAKAI lokal -->
<script defer src="/js/alpine.min.js"></script>
```

### 1.6 Bundle Scripts

```bash
# Gabung Prism.js components jadi 1 file
cat node_modules/prismjs/prism.js \
    node_modules/prismjs/components/prism-php.min.js \
    node_modules/prismjs/components/prism-javascript.min.js \
    > public/js/prism-bundle.js
```

### 1.7 Image Optimization

```html
<!-- Explicit dimensions untuk hindari CLS -->
<img width="32" height="32" alt="Logo" src="/assets/logo.png"/>

<!-- Lazy load untuk images below the fold -->
<img loading="lazy" alt="..." src="..."/>

<!-- Convert ke WebP -->
<img src="/assets/logo.webp" alt="Logo"/>
```

### 1.8 Enable Gzip (Nginx)

```nginx
server {
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml text/javascript image/svg+xml;
    gzip_min_length 256;
    gzip_vary on;
}
```

### 1.9 Font Loading Strategy

```css
@font-face {
    font-family: 'Inter';
    src: url('/fonts/inter-latin.woff2') format('woff2');
    font-display: swap; /* Hindari FOUC */
    unicode-range: U+0000-00FF; /* Subset latin only */
}
```

---

## 2. Accessibility (Target: 100)

### 2.1 Color Contrast

Pastikan semua text punya contrast ratio ≥ 4.5:1 (WCAG AA).

| Text Color | Background | Ratio | Status |
|-----------|------------|-------|--------|
| `#dde2f3` | `#0d131f` | 12.6:1 | ✅ |
| `#95a0b5` | `#0d131f` | 4.5:1 | ⚠️ Borderline |
| `#e6c446` | `#0d131f` | 9.8:1 | ✅ |

**Fix**: Ganti `#95a0b5` → `#a0aab5` untuk contrast lebih tinggi.

### 2.2 ARIA Labels

```html
<!-- ✅ Icon buttons butuh aria-label -->
<button aria-label="Toggle menu">
    <svg>...</svg>
</button>

<!-- ✅ Images butuh alt text -->
<img alt="TAVP Stack Logo" src="/assets/logo.png"/>

<!-- ✅ Form inputs butuh labels -->
<label for="email">Email</label>
<input id="email" type="email"/>
```

### 2.3 Heading Hierarchy

```html
<!-- ✅ Satu h1 per page, h2-h6 berurutan -->
<h1>Page Title</h1>
  <h2>Section</h2>
    <h3>Subsection</h3>

<!-- ❌ JANGAN skip levels -->
<h1>Title</h1>
<h3>Section</h3> <!-- skip h2 -->
```

### 2.4 Keyboard Navigation

```html
<!-- ✅ Semua interactive elements bisa diakses keyboard -->
<a href="..." tabindex="0">Link</button>
<button tabindex="0">Button</button>

<!-- ❌ JANGAN pakai div sebagai button -->
<div onclick="...">Click me</div>
```

---

## 3. Best Practices (Target: 100)

### 3.1 HTTPS

```nginx
server {
    listen 443 ssl;
    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;
}
```

### 3.2 No Console Errors

Pastikan tidak ada JavaScript errors di browser console.

### 3.3 Proper Doctype

```html
<!DOCTYPE html> <!-- ✅ -->
```

### 3.4 Security Headers

```php
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
```

---

## 4. SEO (Target: 100)

### 4.1 Meta Tags

```html
<title>Page Title — Site Name</title>
<meta name="description" content="Page description (120-160 chars)"/>
<link rel="canonical" href="https://domain.com/page"/>
```

### 4.2 Open Graph

```html
<meta property="og:type" content="website"/>
<meta property="og:title" content="Page Title"/>
<meta property="og:description" content="Description"/>
<meta property="og:image" content="https://domain.com/image.jpg"/>
<meta property="og:url" content="https://domain.com/page"/>
```

### 4.3 Structured Data

```html
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "TAVP Stack",
    "url": "https://tavp.web.id",
    "logo": "https://tavp.web.id/assets/logo.png"
}
</script>
```

### 4.4 Sitemap & Robots

```
# robots.txt
User-agent: *
Allow: /
Disallow: /admin
Sitemap: https://domain.com/sitemap.xml
```

---

## 5. Deployment Checklist

### Before Deploy

- [ ] Build Tailwind CSS: `npm run build:css`
- [ ] Self-host all CDN resources (fonts, JS)
- [ ] Enable Gzip in nginx
- [ ] Set proper cache headers
- [ ] Test Lighthouse di production (bukan local container)

### Production VPS

```nginx
server {
    listen 443 ssl http2;
    server_name tavp.web.id;
    root /var/www/html/public;
    
    # Gzip
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml text/javascript image/svg+xml;
    gzip_min_length 256;
    
    # Cache static assets
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|woff2)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
    
    # PHP
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

---

## 6. Expected Scores

| Environment | Performance | Accessibility | Best Practices | SEO |
|-------------|-------------|---------------|----------------|-----|
| Local (TavpBox) | 70-80 | 93-100 | 100 | 100 |
| Production VPS | 90-100 | 93-100 | 100 | 100 |

**Note**: Lighthouse score di local container SELALU lebih rendah karena:
- Container overhead
- Proxy layer
- No CDN/edge caching
- PHP-FPM process spawning

**Test dari production** untuk score yang akurat.

---

## 7. Quick Reference

```bash
# Build CSS
npm run build:css

# Watch mode
npm run watch:css

# Clear cache
tavpbox tavp "cache:clear"

# Setup after rebuild
tavpbox setup
```
