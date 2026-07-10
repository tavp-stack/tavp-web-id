# Frontend Design — tavp.web.id

Put the frontend design here (HTML / CSS / assets / mockups).

This folder is the **design source**. From here we convert the markup into the
Volt theme at `themes/tavp/`:

```
docs/  (static design: HTML, Tailwind, assets)
   │  convert
   ▼
themes/tavp/
   ├── layouts/app.volt        ← page shell (header, footer, <head>)
   └── templates/
       ├── page.volt           ← generic page
       ├── post.volt           ← blog post
       └── blog.volt           ← blog index
```

## Workflow

1. Drop the design (e.g. `index.html`, `blog.html`, `assets/`) into this folder.
2. We split the shared shell into `layouts/app.volt`.
3. We turn each page into a `templates/*.volt` bound to CMS content fields
   (`{{ content['title'] }}`, `{{ content['body'] }}`, etc.).
4. Static assets (css/js/images) move to `public/assets/`.

Content itself lives in `content/` (flat-file Markdown + YAML) — editable
without touching the theme.
