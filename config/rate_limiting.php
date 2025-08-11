<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains rate limiting configurations for different types
    | of requests in your application. These settings help prevent abuse
    | while maintaining good user experience.
    |
    */

    'defaults' => [
        'max_attempts' => 60,
        'decay_minutes' => 1,
    ],

    'throttles' => [
        // Authentication routes
        'login' => [
            'max_attempts' => 5,
            'decay_minutes' => 15,
            'message' => 'Too many login attempts. Please try again in :seconds seconds.',
        ],
        
        'register' => [
            'max_attempts' => 3,
            'decay_minutes' => 15,
            'message' => 'Too many registration attempts. Please try again in :seconds seconds.',
        ],
        
        'password_reset' => [
            'max_attempts' => 10,
            'decay_minutes' => 15,
            'message' => 'Too many password reset attempts. Please try again in :seconds seconds.',
        ],
        
        'password_update' => [
            'max_attempts' => 5,
            'decay_minutes' => 15,
            'message' => 'Too many password update attempts. Please try again in :seconds seconds.',
        ],
        
        'email_verification' => [
            'max_attempts' => 6,
            'decay_minutes' => 1,
            'message' => 'Too many verification email requests. Please try again in :seconds seconds.',
        ],
        
        // API routes
        'api' => [
            'max_attempts' => 60,
            'decay_minutes' => 1,
            'message' => 'Too many API requests. Please try again in :seconds seconds.',
        ],
        
        // General routes
        'general' => [
            'max_attempts' => 100,
            'decay_minutes' => 1,
            'message' => 'Too many requests. Please try again in :seconds seconds.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Storage
    |--------------------------------------------------------------------------
    |
    | Configure where rate limiting data should be stored. The default
    | is 'cache' which uses your application's cache driver.
    |
    */

    'storage' => env('RATE_LIMITING_STORAGE', 'cache'),

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Cache Store
    |--------------------------------------------------------------------------
    |
    | When using the cache storage driver, you may specify which cache
    | store should be used for rate limiting data.
    |
    */

    'cache_store' => env('RATE_LIMITING_CACHE_STORE', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Redis Database
    |--------------------------------------------------------------------------
    |
    | When using the redis storage driver, you may specify which Redis
    | database should be used for rate limiting data.
    |
    */

    'redis_database' => env('RATE_LIMITING_REDIS_DB', 0),

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Exemptions
    |--------------------------------------------------------------------------
    |
    | You may specify IP addresses or ranges that should be exempt from
    | rate limiting. This is useful for trusted sources or development.
    |
    */

    'exempt_ips' => [
        // Add trusted IP addresses here
        // '127.0.0.1',
        // '192.168.1.0/24',
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Headers
    |--------------------------------------------------------------------------
    |
    | Configure which headers should be included in rate limited responses.
    | These headers help clients understand rate limiting status.
    |
    */

    'headers' => [
        'X-RateLimit-Limit',
        'X-RateLimit-Remaining',
        'X-RateLimit-Reset',
        'Retry-After',
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Response Format
    |--------------------------------------------------------------------------
    |
    | Configure the response format for rate limited requests. You can
    | choose between JSON and redirect responses.
    |
    */

    'response_format' => 'json', // 'json' or 'redirect'

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Logging
    |--------------------------------------------------------------------------
    |
    | Enable logging of rate limited requests for monitoring and debugging.
    |
    */

    'logging' => [
        'enabled' => env('RATE_LIMITING_LOGGING', true),
        'channel' => env('RATE_LIMITING_LOG_CHANNEL', 'stack'),
        'level' => env('RATE_LIMITING_LOG_LEVEL', 'warning'),
    ],
];
