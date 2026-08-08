# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Fixed
- Restore production `app.volt` with full features (OG tags, critical CSS, self-hosted assets, Google Analytics, CMS-driven nav/footer)
- Graceful fallback for `site_layout` content type in `app.volt` - try-catch prevents500 error when content type doesn't exist in DB

### Changed
- Update `.gitignore` - exclude temp files, backups, IDE config, public deployment artifacts

### Reconciled (Issue #1)
- Committed the 20 divergent working-directory files as-is (templates simplified during production debugging, `config/cms.php`, `bootstrap/app.php` DB adapter, `public/index.php`, `routes/web.php`, README, logo). Decision: working dir IS the live production state.
