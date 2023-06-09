<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind('rate_limit', function ($app) {
            return new RateLimiter(
                $app->make('cache.store'),
                $app->make('request')->ip(),
                $maxRequests = 60, // Maximum number of requests allowed per minute
                $decayMinutes = 1 // Time period in minutes for the rate limit
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
