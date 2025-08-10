<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Messaging\Message;
use App\Models\ShortURL\ShortURL;
use App\Policies\Messaging\MessagingPolicy;
use App\Policies\ShortURL\ShortURLPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Message::class => MessagingPolicy::class,
        ShortURL::class => ShortURLPolicy::class

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
