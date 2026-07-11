<?php

declare(strict_types=1);

namespace App;

use Tavp\Cms\Auth\CmsUserProvider;
use Tavp\Core\Auth\OtpService;
use Tavp\Core\Auth\TokenService;
use Tavp\Core\Module\ServiceProvider;
use Tavp\Tavpid\Rbac\AccessControl;

/**
 * Local service provider — registers auth + project-specific services.
 */
class AppServiceProvider implements ServiceProvider
{
    public function register(): void
    {
        $app = app();

        // --- User Provider (CMS) -------------------------------------------
        $app->bind('tavpid.user_provider', fn () => new CmsUserProvider());

        // --- OTP Service (core, database-backed) ---------------------------
        $app->bind('tavpid.otp', fn () => new OtpService(
            (int) config('cms.admin.otp_ttl_minutes', 10),
        ));

        // --- Token Service (JWT) -------------------------------------------
        $app->bind('tavpid.token', fn () => new TokenService(
            secret: (string) env('JWT_SECRET', 'tavp-default-secret'),
            accessTtlMinutes: 15,
            refreshTtlDays: 30,
        ));

        // --- RBAC (config-based) -------------------------------------------
        $app->bind('tavpid.rbac', function () {
            $roles = config('cms.admin.roles', []);
            $permissions = config('cms.admin.permissions', []);

            $rbac = new AccessControl();
            foreach ($permissions as $role => $perms) {
                $rbac->defineRole($role, $perms);
            }

            return $rbac;
        });
    }

    public function boot(): void {}

    public function loadRoutes(): void {}

    public function loadMigrations(): void {}

    public function loadViews(): void {}
}
