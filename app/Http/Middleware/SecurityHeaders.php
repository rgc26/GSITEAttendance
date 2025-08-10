<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security Headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        // HSTS Header (only for HTTPS)
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Content Security Policy (temporarily disabled for local development)
        // $csp = "default-src 'self'; " .
        //        "script-src 'self' 'unsafe-inline' 'unsafe-eval'; " .
        //        "style-src 'self' 'unsafe-inline'; " .
        //        "img-src 'self' data: https:; " .
        //        "font-src 'self' https:; " .
        //        "connect-src 'self'; " .
        //        "media-src 'self'; " .
        //        "object-src 'none'; " .
        //        "frame-src 'none'; " .
        //        "base-uri 'self'; " .
        //        "form-action 'self';";
        
        // $response->headers->set('Content-Security-Policy', $csp);

        // Remove server information
        $response->headers->remove('Server');
        $response->headers->remove('X-Powered-By');

        return $response;
    }
}
