<?php

return [
    'app' => [
        'env' => 'production',
        'debug' => false,
        'url' => 'https://yourdomain.com',
        'timezone' => 'UTC',
        'locale' => 'en',
        'fallback_locale' => 'en',
        'faker_locale' => 'en_US',
    ],
    
    'database' => [
        'default' => 'mysql',
        'connections' => [
            'mysql' => [
                'driver' => 'mysql',
                'host' => '127.0.0.1',
                'port' => '3306',
                'database' => 'websys_db',
                'username' => 'websys_user',
                'password' => 'your_secure_password_here',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => 'InnoDB',
            ],
        ],
    ],
    
    'cache' => [
        'default' => 'redis',
        'stores' => [
            'redis' => [
                'driver' => 'redis',
                'connection' => 'cache',
                'lock_connection' => 'default',
            ],
        ],
    ],
    
    'session' => [
        'driver' => 'redis',
        'lifetime' => 120,
        'expire_on_close' => false,
        'encrypt' => true,
        'secure' => true,
        'http_only' => true,
        'same_site' => 'strict',
    ],
    
    'queue' => [
        'default' => 'redis',
        'connections' => [
            'redis' => [
                'driver' => 'redis',
                'connection' => 'default',
                'queue' => 'default',
                'retry_after' => 90,
                'block_for' => null,
            ],
        ],
    ],
    
    'mail' => [
        'default' => 'smtp',
        'mailers' => [
            'smtp' => [
                'transport' => 'smtp',
                'host' => 'your_smtp_host',
                'port' => 587,
                'encryption' => 'tls',
                'username' => 'your_email_username',
                'password' => 'your_email_password',
                'timeout' => null,
                'local_domain' => null,
            ],
        ],
        'from' => [
            'address' => 'noreply@yourdomain.com',
            'name' => 'WebSys',
        ],
    ],
    
    'security' => [
        'force_https' => true,
        'session_secure_cookie' => true,
        'session_http_only' => true,
        'session_same_site' => 'strict',
        'csrf_protection' => true,
        'xss_protection' => true,
        'content_type_nosniff' => true,
        'frame_options' => 'DENY',
        'rate_limiting' => [
            'enabled' => true,
            'max_attempts' => 60,
            'decay_minutes' => 1,
            'throttle_requests' => [
                'login' => 5,
                'register' => 3,
                'password_reset' => 10,
                'password_update' => 5,
                'email_verification' => 6,
            ],
        ],
    ],
    
    'logging' => [
        'default' => 'stack',
        'channels' => [
            'stack' => [
                'driver' => 'stack',
                'channels' => ['single', 'daily'],
                'ignore_exceptions' => false,
            ],
            'single' => [
                'driver' => 'single',
                'path' => storage_path('logs/laravel.log'),
                'level' => 'error',
            ],
            'daily' => [
                'driver' => 'daily',
                'path' => storage_path('logs/laravel.log'),
                'level' => 'error',
                'days' => 14,
            ],
        ],
    ],
];
