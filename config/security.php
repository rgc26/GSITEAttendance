<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains security-related configuration options for your
    | Laravel application. These settings help protect against common
    | security vulnerabilities and attacks.
    |
    */

    'headers' => [
        'x-frame-options' => 'DENY',
        'x-content-type-options' => 'nosniff',
        'x-xss-protection' => '1; mode=block',
        'referrer-policy' => 'strict-origin-when-cross-origin',
        'permissions-policy' => 'geolocation=(), microphone=(), camera=()',
        'strict-transport-security' => 'max-age=31536000; includeSubDomains',
    ],

    'cors' => [
        'allowed_origins' => env('CORS_ALLOWED_ORIGINS', ['http://localhost:8000']),
        'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
        'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With'],
        'exposed_headers' => [],
        'max_age' => 0,
        'supports_credentials' => false,
    ],

    'rate_limiting' => [
        'enabled' => true,
        'max_attempts' => 60,
        'decay_minutes' => 1,
        'throttle_requests' => [
            'login' => 5,
            'register' => 3,
            'password_reset' => 3,
        ],
    ],

    'file_uploads' => [
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'],
        'max_size' => 10240, // 10MB in KB
        'scan_for_viruses' => true,
        'quarantine_suspicious' => true,
    ],

    'session' => [
        'secure' => env('SESSION_SECURE_COOKIE', true),
        'http_only' => true,
        'same_site' => 'lax',
        'lifetime' => 120, // 2 hours
        'expire_on_close' => true,
    ],

    'authentication' => [
        'password_min_length' => 12,
        'require_special_chars' => true,
        'require_numbers' => true,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'max_login_attempts' => 5,
        'lockout_duration' => 15, // minutes
        'two_factor_required' => false,
    ],

    'csrf' => [
        'enabled' => true,
        'token_lifetime' => 60, // minutes
        'regenerate_on_login' => true,
    ],

    'logging' => [
        'security_events' => true,
        'failed_logins' => true,
        'suspicious_activity' => true,
        'file_access' => true,
        'database_queries' => false,
    ],

    'firewall' => [
        'enabled' => true,
        'blocked_ips' => [],
        'allowed_ips' => [],
        'block_suspicious_requests' => true,
        'block_common_attacks' => true,
    ],

    'encryption' => [
        'algorithm' => 'AES-256-CBC',
        'key_rotation' => true,
        'key_rotation_interval' => 30, // days
    ],

    'backup' => [
        'enabled' => true,
        'encrypt_backups' => true,
        'backup_frequency' => 'daily',
        'retention_days' => 30,
    ],
];





