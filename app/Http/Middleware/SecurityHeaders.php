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
        
        // Content Security Policy - TEMPORARILY DISABLED FOR TESTING
        // $csp = "default-src 'self'; " .
        //        "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://js.hcaptcha.com https://*.hcaptcha.com; " .
        //        "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://*.hcaptcha.com; " .
        //        "font-src 'self' https://cdnjs.cloudflare.com; " .
        //        "img-src 'self' data: https: https://*.hcaptcha.com; " .
        //        "connect-src 'self' https://hcaptcha.com https://*.hcaptcha.com; " .
        //        "frame-src 'self' https://hcaptcha.com https://*.hcaptcha.com; " .
        //        "frame-ancestors 'none'; " .
        //        "base-uri 'self'; " .
        //        "form-action 'self'; " .
        //        "upgrade-insecure-requests;";
        
        // $response->headers->set('Content-Security-Policy', $csp);
        
        // HSTS (HTTP Strict Transport Security)
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        
        // Cache Control for sensitive pages
        if ($request->is('login*') || $request->is('register*') || $request->is('password*')) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }
        
        // Remove server information
        $response->headers->remove('Server');
        $response->headers->remove('X-Powered-By');
        
        return $response;
    }
}
