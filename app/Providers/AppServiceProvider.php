<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <--- 1. ADDED THIS IMPORT

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
        // 2. Force HTTPS when running on Railway (Production)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}