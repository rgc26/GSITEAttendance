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
                // Up to 15 attempts per minute per IP+email to avoid double submits causing 429
                Limit::perMinutes(1, 15)->by($compositeKey),
                // And a broader cap per IP to prevent abuse while allowing labs with shared IPs
                Limit::perMinutes(60, 200)->by('register-ip:'.$request->ip()),
                // Allow burst registrations for legitimate users
                Limit::perMinutes(5, 50)->by('register-burst:'.$request->ip()),
            ];
        });

        // Separate rate limiter for login attempts
        RateLimiter::for('login', function (Request $request) {
            $emailKey = strtolower((string) $request->input('email'));
            $compositeKey = sha1('login|'.$request->ip().'|'.$emailKey);

            return [
                Limit::perMinutes(1, 5)->by($compositeKey),
                Limit::perMinutes(15, 10)->by($compositeKey),
            ];
        });
    }
} 