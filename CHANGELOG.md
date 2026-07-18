# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Added
- Admin Messages inbox: contact-form messages with 2-column layout (list + preview), unread/read/archive/delete, filter tabs, `messages` table migration. Contact form now persists submissions to DB. Refs tavp-web-id#4.
- Admin SEO management: registered missing `/admin/seo*` routes (dashboard/settings/redirects/analyzer/ping) and redesigned all SEO templates to the Kinetic Developer Logic design system. Refs tavp-web-id#5.

### Fixed
- Restore production `app.volt` with full features (OG tags, critical CSS, self-hosted assets, Google Analytics, CMS-driven nav/footer)
- Graceful fallback for `site_layout` content type in `app.volt` - try-catch prevents500 error when content type doesn't exist in DB
- Admin OTP login dead-end: "Use a different e-mail" on `/admin/verify` changed from POST form (empty email) to a GET link to `/login`, preventing a broken login form (missing `adminPrefix`) that made "Send code" unresponsive. Hardened `AuthController::sendOtp()` to redirect to login when email is empty and pass `adminPrefix` to the error partial. Fix shipped via `tavp/cms` (refs issue #3, tavp-cms PR #1).
- Admin Messages & SEO pages returned 404 because their routes were dropped during the dynamic admin-prefix refactor. Routes re-registered in `AdminModule`. Refs tavp-web-id#4, tavp-web-id#5.

### Changed
- Update `.gitignore` - exclude temp files, backups, IDE config, public deployment artifacts

### Fixed
- Reconciled 20 divergent working-directory files back to git HEAD (Issue #1): restored debug-simplified templates, `config/cms.php` (148 content-type fields), `bootstrap/app.php` (DatabaseManager), `AppServiceProvider` (tavpid OtpService), `package.json`, `logo.png`, `public/index.php`. Working dir now matches HEAD.

### Known Issues
- None outstanding from the divergent-files debugging session (reconciled in this release).
