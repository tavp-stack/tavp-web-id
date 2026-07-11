<?php

declare(strict_types=1);

namespace App;

use Tavp\Core\Module\ServiceProvider;

/**
 * Local service provider — minimal overrides for tavp.web.id.
 *
 * The vendor CmsServiceProvider handles all core module registration.
 * This provider only adds project-specific customizations.
 */
class AppServiceProvider implements ServiceProvider
{
    public function register(): void
    {
        // All CMS modules are registered by the vendor CmsServiceProvider.
        // Add project-specific bindings here if needed.
    }

    public function boot(): void
    {
        // Nothing to boot.
    }

    public function loadRoutes(): void
    {
        // Routes are loaded directly in routes/web.php.
    }

    public function loadMigrations(): void
    {
        // Migrations are discovered from database/migrations/.
    }

    public function loadViews(): void
    {
        // Views are managed by the ThemeManager.
    }
}
