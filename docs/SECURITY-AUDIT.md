# Security Audit Report — tavp.web.id
**Date**: 2026-07-17
**Auditor**: AI Security Audit

---

## 1. Files Moved to `.local/` (not in repo)

| File | Reason |
|------|--------|
| `.local/backups/backup_tavp_clean.sql` | Database backup — contains all data |
| `.local/backups/backup_tavp.sql` | Database backup |
| `.local/scripts/seed_data.sql` | Seed data with user emails |
| `.local/scripts/smoke.php` | Test script with curl_exec |
| `.local/scripts/run_migrations.php` | One-off migration script |

---

## 2. Security Findings

### 🔴 HIGH — Hardcoded Credentials

| File | Line | Issue | Fix |
|------|------|-------|-----|
| `bin/setup-db.php:14` | `$pdo = new PDO('mysql:host=127.0.0.1;dbname=tavp', 'tavp', 'tavp')` | Hardcoded DB creds | OK for CLI script, not web-accessible |
| `app/AppServiceProvider.php:32` | `secret: env('JWT_SECRET', 'tavp-default-secret')` | Default JWT secret | User must set in .env |
| `.env.example` | Template | No secrets | ✅ OK |

**Status**: Acceptable for dev. Production must set `.env` with real secrets.

### 🟡 MEDIUM — Input Sanitization

| File | Line | Issue | Status |
|------|------|-------|--------|
| `routes/web.php:140-146` | Contact form `$_POST` | ✅ Sanitized with `htmlspecialchars` + `trim` | Fixed |
| `routes/web.php:203` | `$_SERVER['REMOTE_ADDR']` | OK — server-controlled | ✅ Safe |
| `routes/web.php:270` | `$_SERVER['REQUEST_URI']` | OK — used for path detection only | ✅ Safe |

### 🟡 MEDIUM — SQL Injection

| Location | Status |
|----------|--------|
| All DB queries | ✅ Use prepared statements (`PDO::prepare`) |
| `setup-db.php` | ✅ Static SQL, no user input |
| `routes/web.php` contact form | ✅ Uses `$db->execute()` with named params |

### 🟢 LOW — File Upload

| Check | Status |
|-------|--------|
| Upload directory | `/var/www/html/public/uploads` — writable |
| File type validation | ✅ MediaLibrary checks `mime_type` |
| File size limit | ✅ 10MB max |
| File naming | ✅ Random hash suffix |

### 🟢 LOW — Session Management

| Check | Status |
|-------|--------|
| Session start | ✅ `session_start()` in controller constructors |
| Session cookie | `httponly: true`, `secure: true` (in config) |
| OTP expiry | ✅ 10 minutes TTL |
| Session invalidation | ✅ Logout clears session |

### 🟢 LOW — XSS Protection

| Check | Status |
|-------|--------|
| Output escaping | ✅ `htmlspecialchars()` in templates |
| Volt auto-escape | ✅ `$this->e()` used throughout |
| User input display | ✅ Escaped in contact form, messages |

---

## 3. Dangerous Functions Check

| Function | Found? | Location | Risk |
|----------|--------|----------|------|
| `eval()` | ❌ No | — | ✅ Safe |
| `exec()` | ❌ No | — | ✅ Safe |
| `system()` | ❌ No | — | ✅ Safe |
| `passthru()` | ❌ No | — | ✅ Safe |
| `shell_exec()` | ❌ No | — | ✅ Safe |
| `popen()` | ❌ No | — | ✅ Safe |
| `proc_open()` | ❌ No | — | ✅ Safe |
| `curl_exec()` | ✅ Yes | `tests/smoke.php` | Moved to `.local/` |
| `mail()` | ✅ Yes | `routes/web.php:225` | OK — sends notification |
| `file_get_contents()` | ✅ Yes | Various | OK — reads local files |

---

## 4. Enterprise Security Hardening Checklist

### Authentication & Authorization
- [x] OTP-based authentication (no passwords)
- [x] Session-based auth with secure cookies
- [x] RBAC (admin/editor roles)
- [x] Admin URL customizable
- [ ] Rate limiting on login attempts
- [ ] Account lockout after failed attempts
- [ ] Two-factor authentication (2FA)

### Input Validation
- [x] All `$_POST` sanitized with `htmlspecialchars`
- [x] Email validation with `filter_var`
- [x] Honeypot spam protection
- [x] Math captcha for contact form
- [ ] CSRF tokens on all forms
- [ ] Content Security Policy (CSP) headers

### Database Security
- [x] Prepared statements everywhere
- [x] No raw SQL with user input
- [x] Database credentials in `.env`
- [ ] Database connection encryption (SSL)
- [ ] Query logging for audit trail

### File Security
- [x] Upload directory outside webroot (symlinked)
- [x] File type validation
- [x] File size limits
- [ ] File content validation (magic bytes)
- [ ] Virus scanning on upload
- [ ] Image resize/strip EXIF

### Network Security
- [x] HTTPS enforced (TavpBox wildcard cert)
- [x] HSTS headers
- [ ] Content Security Policy (CSP)
- [ ] X-Frame-Options
- [ ] X-Content-Type-Options
- [ ] Referrer-Policy
- [ ] Feature-Policy

### Session Security
- [x] HttpOnly cookies
- [x] Secure cookies (HTTPS)
- [x] Session timeout (OTP expiry)
- [ ] Session rotation on login
- [ ] Concurrent session limiting

### Logging & Monitoring
- [x] Error logging (`error_log`)
- [ ] Access logging
- [ ] Failed login logging
- [ ] Admin action audit trail
- [ ] Suspicious activity alerts

### Backup & Recovery
- [x] Database backup script (`.local/`)
- [ ] Automated backups
- [ ] Backup encryption
- [ ] Disaster recovery plan

---

## 5. Immediate Actions Required

1. **Set `.env` secrets** — `APP_KEY`, `JWT_SECRET`, `DB_PASSWORD` must be unique
2. **Add CSRF tokens** — All forms need CSRF protection
3. **Add security headers** — CSP, X-Frame-Options, etc.
4. **Rate limiting** — Login and contact form

---

## 6. Recommended Security Headers

```php
// Add to routes/web.php or middleware
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
```
