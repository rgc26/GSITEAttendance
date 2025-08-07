<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains security-related configuration options for the application.
    |
    */

    'session' => [
        'secure' => env('SESSION_SECURE_COOKIE', false),
        'http_only' => true,
        'same_site' => 'lax',
        'lifetime' => env('SESSION_LIFETIME', 120),
    ],

    'password' => [
        'min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => true,
    ],

    'rate_limiting' => [
        'login_attempts' => 5,
        'login_decay_minutes' => 15,
        'registration_attempts' => 3,
        'registration_decay_minutes' => 15,
        'password_reset_attempts' => 3,
        'password_reset_decay_minutes' => 15,
    ],

    'csp' => [
        'enabled' => true,
        'report_only' => false,
        'report_uri' => null,
    ],

    'headers' => [
        'x_frame_options' => 'DENY',
        'x_content_type_options' => 'nosniff',
        'x_xss_protection' => '1; mode=block',
        'referrer_policy' => 'strict-origin-when-cross-origin',
        'permissions_policy' => 'geolocation=(), microphone=(), camera=()',
    ],

    'csrf' => [
        'enabled' => true,
        'except' => [
            // Add any routes that should be excluded from CSRF protection
        ],
    ],

    'input_sanitization' => [
        'enabled' => true,
        'strip_tags' => true,
        'trim_whitespace' => true,
    ],
];


