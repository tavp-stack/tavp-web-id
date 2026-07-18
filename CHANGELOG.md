# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Fixed
- Restore production `app.volt` with full features (OG tags, critical CSS, self-hosted assets, Google Analytics, CMS-driven nav/footer)
- Graceful fallback for `site_layout` content type in `app.volt` - try-catch prevents500 error when content type doesn't exist in DB
- Admin OTP login dead-end: "Use a different e-mail" on `/admin/verify` changed from POST form (empty email) to a GET link to `/login`, preventing a broken login form (missing `adminPrefix`) that made "Send code" unresponsive. Hardened `AuthController::sendOtp()` to redirect to login when email is empty and pass `adminPrefix` to the error partial. Fix shipped via `tavp/cms` (refs issue #3, tavp-cms PR #1).

### Changed
- Update `.gitignore` - exclude temp files, backups, IDE config, public deployment artifacts

### Known Issues
- 20 modified files in working directory diverge from git HEAD - templates were simplified during production debugging (hardcoded URLs instead of CMS-driven). Needs reconciliation before next release. See NEXT_STEPS.md.
