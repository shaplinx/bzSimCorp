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
            'message:read',
            'message:create',
            'message:delete',
            'url:create',
            'url:read',
            'url:update',
            'url:delete',
        ])->description('Administrator users can perform any action to the user.');

        Ladder::role('standard', 'Standard', [
            'user:read',
            'message:read',
            'message:create',
            'message:delete',
            'url:create',
            'url:read',
            'url:update',
            'url:delete',
        ])->description('Standard users have the ability to read user.');

    }
}
