<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use App\Models\ShortURL\ShortURL;


class AppServiceProvider extends ServiceProvider
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

        Route::bind('shorturl', function (string $value) {
            return ShortURL::where('url_key', $value)->firstOrFail();
        });
    }
}
