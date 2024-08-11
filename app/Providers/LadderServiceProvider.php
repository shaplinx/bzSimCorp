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
        ])->description('Administrator users can perform any action to the user.');

        Ladder::role('standard', 'Standard', [
            'user:read',
        ])->description('Standard users have the ability to read user.');

    }
}
