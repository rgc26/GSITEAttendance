<?php

return [
    'app' => [
        'env' => 'production',
        'debug' => false, // Keep this false in production!
        'url' => 'https://your_actual_domain.com', // <-- IMPORTANT: Change this
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
                'host' => 'mysql.yourdomain.com', // <-- IMPORTANT: Get this from your DreamHost panel
                'port' => '3306',
                'database' => 'your_dreamhost_db_name',    // <-- IMPORTANT: Change this
                'username' => 'your_dreamhost_db_user',    // <-- IMPORTANT: Change this
                'password' => 'your_dreamhost_db_password',// <-- IMPORTANT: Change this
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => 'InnoDB',
            ],
        ],
    ],
    
    'cache' => [
        // IMPORTANT: Check if your DreamHost plan includes Redis. If not, use 'file'.
        'default' => 'file', 
        'stores' => [
            'file' => [
                'driver' => 'file',
                'path' => storage_path('framework/cache/data'),
            ],
        ],
    ],
    
    'session' => [
        // IMPORTANT: Check if your DreamHost plan includes Redis. If not, use 'file'.
        'driver' => 'file',
        'lifetime' => 120,
        'expire_on_close' => false,
        'encrypt' => true,
        'secure' => true, // This should be true if you are using HTTPS
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
                'host' => 'smtp.dreamhost.com', // Or your specific mail provider's host
                'port' => 587,
                'encryption' => 'tls',
                'username' => 'your_email_username', // <-- IMPORTANT: Change this
                'password' => 'your_email_password', // <-- IMPORTANT: Change this
                'timeout' => null,
            ],
        ],
        'from' => [
            'address' => 'noreply@your_actual_domain.com', // <-- IMPORTANT: Change this
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
