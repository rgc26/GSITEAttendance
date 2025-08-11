<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Set default timezone for Carbon
        Carbon::setDefaultTimezone('Asia/Manila');
        
        // Helper function temporarily disabled to avoid conflicts
        // if (!function_exists('ph_time')) {
        //     function ph_time() {
        //         return now()->setTimezone('Asia/Manila');
        //     }
        // }
    }
} 