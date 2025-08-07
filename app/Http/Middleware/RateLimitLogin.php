<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class RateLimitLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $this->resolveRequestSignature($request);

        if (RateLimiter::tooManyAttempts($key, $this->maxAttempts())) {
            $seconds = RateLimiter::availableIn($key);

            return response()->json([
                'message' => 'Too many login attempts. Please try again in ' . $seconds . ' seconds.',
                'retry_after' => $seconds
            ], 429)->withHeaders([
                'Retry-After' => $seconds,
                'X-RateLimit-Reset' => time() + $seconds,
            ]);
        }

        RateLimiter::hit($key, $this->decayMinutes() * 60);

        $response = $next($request);

        return $response->withHeaders([
            'X-RateLimit-Limit' => $this->maxAttempts(),
            'X-RateLimit-Remaining' => RateLimiter::remaining($key, $this->maxAttempts()),
        ]);
    }

    /**
     * Resolve request signature.
     */
    protected function resolveRequestSignature(Request $request): string
    {
        return sha1(implode('|', [
            $request->ip(),
            $request->userAgent(),
            $request->input('email', ''),
        ]));
    }

    /**
     * Get the maximum number of attempts for the rate limiter.
     */
    protected function maxAttempts(): int
    {
        return 5;
    }

    /**
     * Get the number of minutes to decay the rate limiter.
     */
    protected function decayMinutes(): int
    {
        return 15;
    }
}
