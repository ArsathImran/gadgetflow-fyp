<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Force all generated URLs (including links inside notification emails) to use
        // APP_URL consistently, instead of following whichever host actually served the
        // request. Without this, the same notification links to a different address
        // depending on whether it fired via `php artisan serve` or XAMPP's Apache.
        URL::forceRootUrl(config('app.url'));
    }
}
