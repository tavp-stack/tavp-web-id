# NEXT_STEPS.md — tavp.web.id

**Last updated:** 2026-07-18 (session close)
**Branch:** `main`

---

## Current State

### Production (tavp.web.id)
- **URL:** https://tavp.web.id
- **Admin:** https://tavp.web.id/admin (prefix: `admin`)
- **VPS:**4 CPU,8GB RAM,400GB SSD, HestiaCP 1.9.6
- **DB:** `jtdoank_idtavpweb` / `jtdoank_userwebtavpid`
- **Email:** PHPMailer workaround (custom MailService SMTP broken)

### Local Dev (TavpBox)
- **Container:** `tavp-tavp-web-id` (Podman)
- **PHP:** 8.3.32
- **DB:** `tavp` / `tavp` / `tavp`
- **Admin prefix:** `admin`

---

## Files Changed This Session

| File | Change | Status |
|------|--------|--------|
| `themes/tavp/layouts/app.volt` | Restored production version with full features; added try-catch for `site_layout` | ✅ Committed (`82a10c1`) |
| `.gitignore` | Updated to exclude temp files, backups, IDE config, public deployment artifacts | ✅ Committed (`1d62968`) |
| `CHANGELOG.md` | Created with Unreleased entries | ✅ Committed (`f031dce`) |

##20 Modified Files (NOT committed — DIVERGENT from git HEAD)

These files in working directory differ significantly from git HEAD. They were simplified during production debugging sessions (hardcoded URLs, removed CMS-driven content). **Do NOT commit without reconciliation.**

| File | Divergence |
|------|------------|
| `themes/tavp/home.volt` | Removed dynamic hero logo, CTA URLs, feature icons |
| `themes/tavp/blog.volt` | Simplified author display |
| `themes/tavp/post.volt` | Medium-style header changes |
| `themes/tavp/contact.volt` | Removed dynamic content |
| `themes/tavp/documentation.volt` | Simplified |
| `themes/tavp/get-started.volt` | Simplified |
| `themes/tavp/performance.volt` | Simplified |
| `themes/tavp/page.volt` | Simplified |
| `themes/tavp/404.volt` | Simplified |
| `themes/tavp/500.volt` | Simplified |
| `themes/tavp/taxonomy.volt` | Simplified |
| `routes/web.php` | Removed531 lines of routes |
| `config/cms.php` | Removed148 lines of content types |
| `bootstrap/app.php` | Significant changes |
| `app/AppServiceProvider.php` | Minor change |
| `public/index.php` | Entry point changes |
| `.tavpbox.yml` | TavpBox config changes |
| `package.json` / `package-lock.json` | Dependency changes |
| `public/assets/logo.png` | Logo update (28KB →5KB) |

---

## Blocker

**No active blockers.** All pages return200 OK with zero warnings/errors.

---

## TODO Prioritas (Next Session)

### HIGH
1. **Reconcile20 divergent files** (Issue #1) — Decide: revert to git HEAD and port fixes, or commit working directory as-is
2. **Create `site_layout` content type** (Issue #2) — Add to `config/cms.php`, create migration/seed, remove try-catch fallback

### MEDIUM
3. **Investigate custom MailService SMTP** — Why does it say "SENT OK" but emails never arrive? PHPMailer works fine.
4. **Enable Gitea Wiki** — Wiki API returns405/500, needs admin enable

### LOW
5. **Clean up `feat/database-connection` branch** — No commits ahead of main, can be deleted
6. **Update production nginx template** — Phalcon template created but not properly applied (currently using default proxy template)

---

## Relevant Issues/PRs

- **Issue #1:** Reconcile20 divergent template files between working directory and git HEAD
- **Issue #2:** Create `site_layout` content type for dynamic nav/footer/logo

---

## Commits This Session

| Hash | Message |
|------|---------|
| `82a10c1` | fix: restore production app.volt + graceful fallback for site_layout |
| `1d62968` | chore: update .gitignore — exclude temp files, backups, IDE config, public deployment artifacts |
| `f031dce` | docs: add CHANGELOG.md with Unreleased entries |
