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
- **v1.2.0** (id=5): semua 6 issue closed (#1, #2, #3, #4, #5, #6) — siap rilis
- **v1.3.0** (id=6): kosong — tempat untuk fitur berikutnya

## Issue Tracker
- **Closed:** #1, #2, #3, #4, #5, #6, #13
- **Open:** #12 (web installer — perlu diskusi dengan owner)
- #6 = bug milik repo `tavpbox` (telah diberi label `blocked`, perlu dipindah ke repo sana)

---

## TODO Prioritas (Next Session)

### HIGH
1. **Deploy `c1d4979` ke VPS** — pull + `rm -rf storage/cms/cache`. 
2. **Seed/isi `site_layout` di production** — buat record via admin agar nav/footer dinamis aktif (fallback masih jalan jika kosong).

### MEDIUM
3. **Atasi Issue #12** — web installer: putuskan scope dengan owner.
4. **Pindahkan Issue #6 ke repo `tavpbox`** — bug nginx root /var/www/html/public.
5. **Investigate custom MailService SMTP** — sukses "SENT OK" tapi email tidak terkirim.

### LOW
6. **RSS `/feed` mati** — `public/index.php` tidak memanggil `$cms->loadRoutes()` → 404.
7. **Clean up branch `feat/database-connection`** — tidak ada commit di depan main, bisa dihapus.
8. **Update production nginx template** — Phalcon template belum diterapkan (masih default proxy).

---

## Commits Sesi Ini

| Hash | Message |
|------|---------|
| `c1d4979` | fix(cms): add site_layout content type + restore contact messages persistence |

**Sesi sebelumnya (relevan):** `dcd08f9` (fix blog post meta fallback), `ce9c9fa` (reconcile 20 divergen, Issue #1), `82a10c1` (restore app.volt), `1d62968` (.gitignore), `f031dce` (CHANGELOG).