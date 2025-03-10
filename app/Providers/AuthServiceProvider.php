<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Finance\Bank' => 'App\Policies\Finance\BankPolicy',
        'App\Models\Finance\TransactionCategory' => 'App\Policies\Finance\TransactionCategoryPolicy',
        'App\Models\Finance\Transaction' => 'App\Policies\Finance\TransactionPolicy',
        'App\Models\Finance\Loan' => 'App\Policies\Finance\LoanPolicy',
        


    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
