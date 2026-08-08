# NEXT_STEPS.md - tavp.web.id

**Last updated:** 2026-08-08
**Branch:** `main` (HEAD: `c1d4979`)

**ATURAN WAJIB:** Setiap pembuatan issue Gitea WAJIB langsung apply label (POST `/issues/{n}/labels` dengan ID). Jangan pernah shared tanpa label.

---

## Production (tavp.web.id)
- **URL:** https://tavp.web.id
- **Admin:** https://tavp.web.id/admin
- **Deploy:** `git pull origin main` di VPS `~/web/tavp.web.id/private` + hapus `storage/cms/cache`. `public/themes` = symlink ke `themes`.
- **DB:** `jtdoank_idtavpweb` / `jtdoank_userwebtavpid`
- **Email:** PHPMailer workaround (MailService SMTP broken, masih investigasi)

## Local Dev (TavpBox)
- **Container:** `tavp-tavp-web-id` (Podman), PHP 8.3.32, DB `tavp`/`tavp`/`tavp`, admin prefix `admin`

---

## Status Milestone (Gitea)
- **v1.2.0** (id=5): CLOSED - 6/6 issue; tag+release `v1.2.0`
- **v1.3.0** (id=6): CLOSED - 7/7 issue; tag+release `v1.3.0`
- Open issue di repo: 0 (semua closed)

## Issue Tracker
- **Semua 13 issue closed** (#1-#13). Status history: #1-#6 di v1.2.0, #7-#13 di v1.3.0.

---

## TODO Prioritas (Next Session)

### MEDIUM
1. **Release GitHub (opsional)** — `gh release create` untuk v1.2.0 & v1.3.0 agar sinkron (opsional, gitea sudah jadi kanon).
2. **Pindahkan Issue #6 ke repo `tavpbox`** — bug nginx root /var/www/html/public.
3. **Investigate custom MailService SMTP** — sukses "SENT OK" tapi email tidak terkirim (MAIL_PORT 465 se default).

### LOW
4. **RSS `/feed` mati** — `public/index.php` tidak memanggil `$cms->loadRoutes()` → 404.
5. **Clean up branch `feat/database-connection`** — tidak ada commit di depan main, bisa dihapus.
6. **Update production nginx template** — Phalcon template belum diterapkan (masih default proxy).

---

## Commits Sesi Ini

| Hash | Message |
|------|---------|
| `02aa3f0` | chore(prod): default mail port 465 (dari VPS) + tag v1.2.0 & v1.3.0, release gitea |
| `c1d4979` | fix(cms): add site_layout content type + restore contact messages persistence |

**Sesi sebelumnya (relevan):** `dcd08f9` (fix blog post meta fallback), `ce9c9fa` (reconcile 20 divergen, Issue #1), `82a10c1` (restore app.volt), `1d62968` (.gitignore), `f031dce` (CHANGELOG).