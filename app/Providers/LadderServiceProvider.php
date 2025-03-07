<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Ladder\Ladder;

class LadderServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();
    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Ladder::role('admin', 'Administrator', [
            'user:create',
            'user:read',
            'user:update',
            'user:delete',
            'bank:create',
            'bank:read',
            'bank:update',
            'bank:delete',
            'transaction-category:create',
            'transaction-category:read',
            'transaction-category:update',
            'transaction-category:delete',
            'transaction:create',
            'transaction:read',
            'transaction:update',
            'transaction:delete',
            'loan:create',
            'loan:read',
            'loan:update',
            'loan:delete',
        ]);

        Ladder::role('standard', 'Standard', [
            'user:read',
        ])->description('Standard users have the ability to read user.');

    }
}
