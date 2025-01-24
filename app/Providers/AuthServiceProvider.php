<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Defined scopes here
        Passport::tokensCan([
            'admin' => 'Admin can only access this route.',
            'user' => 'User can only access this route.',
        ]);
    }
}
