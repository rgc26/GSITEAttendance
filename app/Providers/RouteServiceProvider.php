<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/student/dashboard';

    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // More generous and fair limiter for registration to reduce 429s
        RateLimiter::for('register', function (Request $request) {
            $emailKey = strtolower((string) $request->input('email'));
            $compositeKey = sha1('register|'.$request->ip().'|'.$emailKey);

            return [
                // Up to 10 attempts per minute per IP+email to avoid double submits causing 429
                Limit::perMinutes(1, 10)->by($compositeKey),
                // And a broader cap per IP to prevent abuse while allowing labs with shared IPs
                Limit::perMinutes(60, 100)->by('register-ip:'.$request->ip()),
            ];
        });
    }
} 