<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Ensure PHP and Laravel use the configured timezone (set in config/app.php)
        // Do not call non-existent Carbon::setDefaultTimezone
        date_default_timezone_set(config('app.timezone', 'Asia/Manila'));
    }
} 