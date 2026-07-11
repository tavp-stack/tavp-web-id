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

        // --- RBAC (config + database) --------------------------------------
        // Roles come from config (built-in admins) as a base, then from the
        // users table so accounts created in the admin panel take effect.
        $app->bind('tavpid.rbac', function () {
            $roles = (array) config('cms.admin.roles', []);
            $permissions = (array) config('cms.admin.permissions', []);

            $rbac = new AccessControl();
            foreach ($permissions as $role => $perms) {
                $rbac->defineRole($role, $perms);
            }
            foreach ($roles as $email => $role) {
                $rbac->setUserRole($email, $role);
            }

            // Database-managed users override the config defaults.
            try {
                $rows = app('db')->fetchAll(
                    'SELECT email, role FROM users WHERE role IS NOT NULL AND role <> ""',
                    \PDO::FETCH_ASSOC
                );
                foreach ($rows as $row) {
                    if (!empty($row['email']) && !empty($row['role'])) {
                        $rbac->setUserRole((string) $row['email'], (string) $row['role']);
                    }
                }
            } catch (\Throwable) {
                // Users table not migrated yet — config roles still apply.
            }

            return $rbac;
        });
    }

    public function boot(): void {}

    public function loadRoutes(): void {}

    public function loadMigrations(): void {}

    public function loadViews(): void {}
}
