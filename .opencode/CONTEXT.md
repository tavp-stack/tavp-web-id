# tavp.web.id — AI Context Cheat Sheet

> BACA FILE INI DI AWAL SETIAP SESSION. Berisi semua info yang dibutuhkan AI untuk manage project ini.

---

## 1. Gitea API Access

```
Host: git.glotama.com
User: jtdoank (admin)
Token: 0e6b86795bb32063035b69a49784a2a438b93e96
Repo: tavp-stack/tavp-web-id
```

**Cara pakai di PowerShell:**
```powershell
$h = @{Authorization = "Basic " + [Convert]::ToBase64String([Text.Encoding]::ASCII.GetBytes("jtdoank:0e6b86795bb32063035b69a49784a2a438b93e96")); "Content-Type" = "application/json"}

# Get issues
Invoke-RestMethod -Uri "https://git.glotama.com/api/v1/repos/tavp-stack/tavp-web-id/issues" -Headers $h

# Create issue
$body = @{ title = "judul"; body = "isi" } | ConvertTo-Json
Invoke-RestMethod -Uri "https://git.glotama.com/api/v1/repos/tavp-stack/tavp-web-id/issues" -Method Post -Headers $h -Body $body

# Create release
$body = @{ tag_name = "v0.1.0"; name = "v0.1.0 - Judul"; body = "changelog" } | ConvertTo-Json -Depth 3
Invoke-RestMethod -Uri "https://git.glotama.com/api/v1/repos/tavp-stack/tavp-web-id/releases" -Method Post -Headers $h -Body $body
```

**Wiki endpoints (Gitea v1.26.4 — API bermasalah, pakai web form):**
```powershell
# Wiki via web form (bukan API)
$session = $null
$loginPage = Invoke-WebRequest -Uri "https://git.glotama.com/user/login" -SessionVariable session -UseBasicParsing
$loginBody = @{ user_name = "jtdoank"; password = "0e6b86795bb32063035b69a49784a2a438b93e96" }
Invoke-WebRequest -Uri "https://git.glotama.com/user/login" -Method Post -Body $loginBody -WebSession $session -UseBasicParsing

# Create/update wiki page
$wikiNew = Invoke-WebRequest -Uri "https://git.glotama.com/tavp-stack/tavp-web-id/wiki/_new" -WebSession $session -UseBasicParsing
$csrf = ""; if ($wikiNew.Content -match 'name="_csrf" content="([^"]+)"') { $csrf = $matches[1] }
$wikiBody = @{ _csrf = $csrf; title = "Page-Title"; content = "content here"; message = "Create page" }
Invoke-WebRequest -Uri "https://git.glotama.com/tavp-stack/tavp-web-id/wiki/_new" -Method Post -Body $wikiBody -WebSession $session -UseBasicParsing
```

**Label IDs:**
- bug=35, feature=36, enhancement=37, priority-high=38, blocked=39, docs=40, devops=41, tavpbox=42

---

## 2. Git Remote

```
gitea  = https://git.glotama.com/tavp-stack/tavp-web-id.git (primary)
github = git@github.com:tavp-stack/tavp-web-id.git (mirror)
```

**Rule:** Selalu push ke DUA remote. Development di Gitea, GitHub hanya README + releases + clean code.

---

## 3. Container (TAVPBox)

```
Container name: tavp-tavp-web-id
Image: ghcr.io/tavp-stack/tavpbox-php:latest
DB: MariaDB, user=tavp, pass=tavp, database=tavp
Webroot: /var/www/html
PHP entry: /var/www/html/public/index.php
```

**Commands:**
```powershell
# Akses database
podman exec tavp-tavp-web-id mysql -u tavp -ptavp tavp -e "SELECT ..."

# Check pages
podman exec tavp-tavp-web-id curl -s -o /dev/null -w "%{http_code}" http://localhost/
podman exec tavp-tavp-web-id curl -s http://localhost/ | Select-String -Pattern "Warning|Error|Fatal"

# Clear Volt cache
podman exec tavp-tavp-web-id rm -rf /tmp/storage/compiled/volt/*

# Check container status
podman ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"
```

---

## 4. Project Structure

```
app/
  AppServiceProvider.php    # Service registration
  Support/Markdown.php      # Markdown converter
bootstrap/app.php           # Application bootstrap
config/cms.php              # CMS configuration (content types, mail, admin)
public/
  index.php                 # Entry point
  assets/                   # CSS, fonts, images
  js/                       # Alpine.js, Prism.js, tracker.js
  uploads/                  # User uploads (symlink to /tmp/uploads)
routes/web.php              # All route definitions
themes/tavp/
  layouts/app.volt          # Main layout (nav, footer, head)
  home.volt                 # Landing page
  blog.volt                 # Blog index
  post.volt                 # Single post
  contact.volt              # Contact form
  documentation.volt        # Docs page
  performance.volt          # Benchmarks
  get-started.volt          # Getting started
  page.volt                 # Generic page
  taxonomy.volt             # Category/tag archive
  404.volt                  # Not found
  500.volt                  # Server error
vendor/tavp/
  core/                     # TAVP Core (routing, view, kernel)
  cms/                      # TAVP CMS (content, admin, auth)
  cli/                      # TAVP CLI tools
.gitea/workflows/ci.yml    # Gitea Actions CI workflow
.tavpbox.yml                # TavpBox configuration
```

---

## 5. Key Conventions

- **Framework:** Phalcon PHP 8.3 (C-extension) + Volt templates
- **CMS:** TAVP CMS (database storage)
- **Styling:** Tailwind CSS (offline build)
- **Interactivity:** Alpine.js (self-hosted)
- **Database:** MariaDB (27 tables)
- **Auth:** OTP via email (PHPMailer)
- **Versioning:** ZeroVer (0.x.y)
- **Language:** Bahasa Indonesia untuk UI, English untuk code

---

## 6. Database (27 tables)

```sql
-- Core content
contents, content_revisions, content_types, content_taxonomy
-- Taxonomy
taxonomy_terms
-- Users & Auth
users, user_sessions, user_roles, roles, role_permissions, permissions, otp_codes
-- Navigation
menus, menu_items
-- Media & SEO
media, seo_meta
-- Analytics
analytics_sessions, analytics_page_visits, analytics_events, analytics_fraud_events
-- System
settings, api_tokens, webhooks, webhook_deliveries, contact_messages, redirects, outbound_links
```

**Key settings:**
```sql
SELECT * FROM settings WHERE key='route_prefix';  -- admin prefix
SELECT * FROM settings WHERE key='site_name';     -- site name
SELECT * FROM settings WHERE key='tagline';       -- tagline
```

---

## 7. Wiki Structure

| Halaman | Isi |
|---------|-----|
| Home | Index page, links ke semua halaman |
| Architecture | System architecture, directory structure, DB schema |
| Development-History | Timeline dari awal sampai sekarang |
| Session-Log | Histori semua session |
| Deployment-Guide | Cara deploy ke production |
| Known-Issues | Semua masalah + status |
| Decision-Log | Keputusan penting + konteks |

**Update Wiki Rules:**
1. Setiap session close: Append entry ke Session-Log
2. Ada perubahan schema? Update Architecture
3. Bug baru? Update Known-Issues
4. Keputusan baru? Update Decision-Log
5. Jangan rewrite total — cukup tambah/edit section yang berubah

---

## 8. Issue Template

```json
{
  "title": "type: judul singkat",
  "body": "## Description\n...\n## Tasks\n- [ ] ...\n## Location\nfile.php",
  "labels": [35, 36]
}
```

---

## 9. Release Template

```json
{
  "tag_name": "v0.x.y",
  "name": "v0.x.y - Judul",
  "body": "## What's Changed\n### Added\n- ...\n### Fixed\n- ..."
}
```

---

## 10. Common Pitfalls

- **PowerShell backticks:** Jangan pakai backtick di dalam string yang sudah pakai backtick
- **JSON labels:** Labels harus array of integers (ID), bukan strings
- **Wiki API:** Gitea v1.26.4 wiki API bermasalah — pakai web form POST
- **Database:** User=tavp, Pass=tavp, DB=tavp (di container)
- **app.volt:** PHP block harus SEBELUM `<head>` untuk define $defaultTitle
- **site_layout:** Content type belum ada — pakai try-catch fallback
- **Vendor:** Tidak bisa composer install di VPS — harus SCP dari local
- **Email:** Custom MailService broken — pakai PHPMailer

---

## 11. Open Issues

| # | Title | Labels |
|---|-------|--------|
| 1 | Reconcile20 divergent template files | bug |
| 2 | Create site_layout content type | feature |

---

## 12. Production Info

```
VPS: HestiaCP,4 CPU,8GB RAM,400GB SSD
Domain: tavp.web.id
User: jtdoank
DB: jtdoank_idtavpweb / jtdoank_userwebtavpid
Admin: https://tavp.web.id/admin (prefix: admin)
Email: PHPMailer (not custom MailService)
Analytics: G-LTCMDNNHSB
```
