<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitRegistration
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $emailKey = strtolower((string) $request->input('email'));
        $compositeKey = sha1('register|'.$request->ip().'|'.$emailKey);
        $ipKey = 'register-ip:'.$request->ip();
        $burstKey = 'register-burst:'.$request->ip();

        // Check if any of the rate limits are exceeded
        if (RateLimiter::tooManyAttempts($compositeKey, 15)) {
            $seconds = RateLimiter::availableIn($compositeKey);
            return $this->rateLimitResponse($seconds, 'Too many registration attempts for this email. Please try again in ' . $seconds . ' seconds.');
        }

        if (RateLimiter::tooManyAttempts($ipKey, 200)) {
            $seconds = RateLimiter::availableIn($ipKey);
            return $this->rateLimitResponse($seconds, 'Too many registration attempts from this IP. Please try again in ' . $seconds . ' seconds.');
        }

        if (RateLimiter::tooManyAttempts($burstKey, 50)) {
            $seconds = RateLimiter::availableIn($burstKey);
            return $this->rateLimitResponse($seconds, 'Too many rapid registration attempts. Please slow down and try again in ' . $seconds . ' seconds.');
        }

        // Increment all rate limiters
        RateLimiter::hit($compositeKey, 60); // 1 minute
        RateLimiter::hit($ipKey, 3600); // 1 hour
        RateLimiter::hit($burstKey, 300); // 5 minutes

        $response = $next($request);

        // Add rate limit headers for debugging
        return $response->withHeaders([
            'X-RateLimit-Limit-Composite' => 15,
            'X-RateLimit-Remaining-Composite' => RateLimiter::remaining($compositeKey, 15),
            'X-RateLimit-Limit-IP' => 200,
            'X-RateLimit-Remaining-IP' => RateLimiter::remaining($ipKey, 200),
            'X-RateLimit-Limit-Burst' => 50,
            'X-RateLimit-Remaining-Burst' => RateLimiter::remaining($burstKey, 50),
        ]);
    }

    /**
     * Create a rate limit response
     */
    protected function rateLimitResponse(int $seconds, string $message): Response
    {
        if (request()->expectsJson()) {
            return response()->json([
                'message' => $message,
                'retry_after' => $seconds
            ], 429)->withHeaders([
                'Retry-After' => $seconds,
                'X-RateLimit-Reset' => time() + $seconds,
            ]);
        }

        return back()->withErrors([
            'email' => $message,
        ])->withInput()->withHeaders([
            'Retry-After' => $seconds,
        ]);
    }
}
