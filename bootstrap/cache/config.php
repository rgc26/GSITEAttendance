<?php return array (
  'app' => 
  array (
    'env' => 'production',
    'debug' => false,
    'url' => 'https://yourdomain.com',
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'key' => 'base64:CWq6boi5z7eiYiWWZO47oZJ09hugfTIWYWPh9w7nL24=',
    'name' => 'WebSys',
    'version' => '1.0.0',
    'secure' => 
    array (
      'force_https' => true,
      'session_secure' => true,
      'session_http_only' => true,
      'session_same_site' => 'lax',
      'csrf_protection' => true,
      'xss_protection' => true,
      'content_type_nosniff' => true,
      'frame_options' => 'DENY',
    ),
    'cipher' => 'AES-256-CBC',
    'maintenance' => 
    array (
      'driver' => 'file',
    ),
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Cookie\\CookieServiceProvider',
      6 => 'Illuminate\\Database\\DatabaseServiceProvider',
      7 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      8 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      9 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      10 => 'Illuminate\\Hashing\\HashServiceProvider',
      11 => 'Illuminate\\Mail\\MailServiceProvider',
      12 => 'Illuminate\\Notifications\\NotificationServiceProvider',
      13 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      14 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      15 => 'Illuminate\\Queue\\QueueServiceProvider',
      16 => 'Illuminate\\Redis\\RedisServiceProvider',
      17 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      18 => 'Illuminate\\Session\\SessionServiceProvider',
      19 => 'Illuminate\\Translation\\TranslationServiceProvider',
      20 => 'Illuminate\\Validation\\ValidationServiceProvider',
      21 => 'Illuminate\\View\\ViewServiceProvider',
      22 => 'App\\Providers\\AppServiceProvider',
      23 => 'App\\Providers\\AuthServiceProvider',
      24 => 'App\\Providers\\EventServiceProvider',
      25 => 'App\\Providers\\RouteServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Arr' => 'Illuminate\\Support\\Arr',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Broadcast' => 'Illuminate\\Support\\Facades\\Broadcast',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'Date' => 'Illuminate\\Support\\Facades\\Date',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Http' => 'Illuminate\\Support\\Facades\\Http',
      'Js' => 'Illuminate\\Support\\Js',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Notification' => 'Illuminate\\Support\\Facades\\Notification',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Process' => 'Illuminate\\Support\\Facades\\Process',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'RateLimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'Str' => 'Illuminate\\Support\\Str',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'Vite' => 'Illuminate\\Support\\Facades\\Vite',
    ),
  ),
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
      ),
      'sanctum' => 
      array (
        'driver' => 'sanctum',
        'provider' => NULL,
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\Models\\User',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'table' => 'password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
      ),
    ),
    'password_timeout' => 10800,
  ),
  'cache' => 
  array (
    'default' => 'file',
    'stores' => 
    array (
      'apc' => 
      array (
        'driver' => 'apc',
      ),
      'array' => 
      array (
        'driver' => 'array',
        'serialize' => false,
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
        'lock_connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => 'C:\\xampp\\htdocs\\webSys\\storage\\framework/cache/data',
        'lock_path' => 'C:\\xampp\\htdocs\\webSys\\storage\\framework/cache/data',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'persistent_id' => NULL,
        'sasl' => 
        array (
          0 => NULL,
          1 => NULL,
        ),
        'options' => 
        array (
        ),
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'cache',
        'lock_connection' => 'default',
      ),
      'dynamodb' => 
      array (
        'driver' => 'dynamodb',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'table' => 'cache',
        'endpoint' => NULL,
      ),
      'octane' => 
      array (
        'driver' => 'octane',
      ),
    ),
    'prefix' => 'websys_cache_',
  ),
  'database' => 
  array (
    'default' => 'mysql',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'url' => NULL,
        'database' => 'websys_db',
        'prefix' => '',
        'foreign_key_constraints' => true,
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'websys_db',
        'username' => 'websys_user',
        'password' => 'your_secure_password_here',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'websys_db',
        'username' => 'websys_user',
        'password' => 'your_secure_password_here',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'search_path' => 'public',
        'sslmode' => 'prefer',
      ),
      'sqlsrv' => 
      array (
        'driver' => 'sqlsrv',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'websys_db',
        'username' => 'websys_user',
        'password' => 'your_secure_password_here',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'client' => 'phpredis',
      'options' => 
      array (
        'cluster' => 'redis',
        'prefix' => 'websys_database_',
      ),
      'default' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '0',
      ),
      'cache' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '1',
      ),
    ),
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => 'C:\\xampp\\htdocs\\webSys\\storage\\app',
        'throw' => false,
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => 'C:\\xampp\\htdocs\\webSys\\storage\\app/public',
        'url' => 'https://yourdomain.com/storage',
        'visibility' => 'public',
        'throw' => false,
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'bucket' => '',
        'url' => NULL,
        'endpoint' => NULL,
        'use_path_style_endpoint' => false,
        'throw' => false,
      ),
    ),
    'links' => 
    array (
      'C:\\xampp\\htdocs\\webSys\\public\\storage' => 'C:\\xampp\\htdocs\\webSys\\storage\\app/public',
    ),
  ),
  'logging' => 
  array (
    'default' => 'stack',
    'deprecations' => 
    array (
      'channel' => NULL,
      'trace' => false,
    ),
    'channels' => 
    array (
      'stack' => 
      array (
        'driver' => 'stack',
        'channels' => 
        array (
          0 => 'single',
        ),
        'ignore_exceptions' => false,
      ),
      'single' => 
      array (
        'driver' => 'single',
        'path' => 'C:\\xampp\\htdocs\\webSys\\storage\\logs/laravel.log',
        'level' => 'error',
        'replace_placeholders' => true,
      ),
      'daily' => 
      array (
        'driver' => 'daily',
        'path' => 'C:\\xampp\\htdocs\\webSys\\storage\\logs/laravel.log',
        'level' => 'error',
        'days' => 14,
        'replace_placeholders' => true,
      ),
      'slack' => 
      array (
        'driver' => 'slack',
        'url' => NULL,
        'username' => 'Laravel Log',
        'emoji' => ':boom:',
        'level' => 'error',
        'replace_placeholders' => true,
      ),
      'papertrail' => 
      array (
        'driver' => 'monolog',
        'level' => 'error',
        'handler' => 'Monolog\\Handler\\SyslogHandler',
        'handler_with' => 
        array (
          'host' => NULL,
          'port' => NULL,
          'connectionString' => 'tls://:',
        ),
        'processors' => 
        array (
          0 => 'Monolog\\Processor\\PsrLogMessageProcessor',
        ),
      ),
      'stderr' => 
      array (
        'driver' => 'monolog',
        'level' => 'error',
        'handler' => 'Monolog\\Handler\\StreamHandler',
        'formatter' => NULL,
        'formatter_with' => 
        array (
          'format' => '[%datetime%] %channel%.%level_name%: %message% %context% %extra%
',
        ),
        'handler_with' => 
        array (
          'stream' => 'php://stderr',
        ),
        'processors' => 
        array (
          0 => 'Monolog\\Processor\\PsrLogMessageProcessor',
        ),
      ),
      'syslog' => 
      array (
        'driver' => 'syslog',
        'level' => 'error',
        'facility' => 8,
        'replace_placeholders' => true,
      ),
      'errorlog' => 
      array (
        'driver' => 'errorlog',
        'level' => 'error',
        'replace_placeholders' => true,
      ),
      'null' => 
      array (
        'driver' => 'monolog',
        'handler' => 'Monolog\\Handler\\NullHandler',
      ),
      'emergency' => 
      array (
        'path' => 'C:\\xampp\\htdocs\\webSys\\storage\\logs/laravel.log',
      ),
    ),
  ),
  'mail' => 
  array (
    'default' => 'smtp',
    'mailers' => 
    array (
      'smtp' => 
      array (
        'transport' => 'smtp',
        'url' => NULL,
        'host' => 'mailpit',
        'port' => '1025',
        'encryption' => NULL,
        'username' => NULL,
        'password' => NULL,
        'timeout' => NULL,
        'local_domain' => NULL,
      ),
      'ses' => 
      array (
        'transport' => 'ses',
      ),
      'mailgun' => 
      array (
        'transport' => 'mailgun',
      ),
      'postmark' => 
      array (
        'transport' => 'postmark',
      ),
      'sendmail' => 
      array (
        'transport' => 'sendmail',
        'path' => '/usr/sbin/sendmail -bs -i',
      ),
      'log' => 
      array (
        'transport' => 'log',
        'channel' => NULL,
      ),
      'array' => 
      array (
        'transport' => 'array',
      ),
      'failover' => 
      array (
        'transport' => 'failover',
        'mailers' => 
        array (
          0 => 'smtp',
          1 => 'log',
        ),
      ),
      'roundrobin' => 
      array (
        'transport' => 'roundrobin',
        'mailers' => 
        array (
          0 => 'smtp',
          1 => 'log',
        ),
      ),
    ),
    'from' => 
    array (
      'address' => 'hello@example.com',
      'name' => 'WebSys',
    ),
    'markdown' => 
    array (
      'theme' => 'default',
      'paths' => 
      array (
        0 => 'C:\\xampp\\htdocs\\webSys\\resources\\views/vendor/mail',
      ),
    ),
  ),
  'rate_limiting' => 
  array (
    'defaults' => 
    array (
      'max_attempts' => 60,
      'decay_minutes' => 1,
    ),
    'throttles' => 
    array (
      'login' => 
      array (
        'max_attempts' => 5,
        'decay_minutes' => 15,
        'message' => 'Too many login attempts. Please try again in :seconds seconds.',
      ),
      'register' => 
      array (
        'max_attempts' => 3,
        'decay_minutes' => 15,
        'message' => 'Too many registration attempts. Please try again in :seconds seconds.',
      ),
      'password_reset' => 
      array (
        'max_attempts' => 10,
        'decay_minutes' => 15,
        'message' => 'Too many password reset attempts. Please try again in :seconds seconds.',
      ),
      'password_update' => 
      array (
        'max_attempts' => 5,
        'decay_minutes' => 15,
        'message' => 'Too many password update attempts. Please try again in :seconds seconds.',
      ),
      'email_verification' => 
      array (
        'max_attempts' => 6,
        'decay_minutes' => 1,
        'message' => 'Too many verification email requests. Please try again in :seconds seconds.',
      ),
      'api' => 
      array (
        'max_attempts' => 60,
        'decay_minutes' => 1,
        'message' => 'Too many API requests. Please try again in :seconds seconds.',
      ),
      'general' => 
      array (
        'max_attempts' => 100,
        'decay_minutes' => 1,
        'message' => 'Too many requests. Please try again in :seconds seconds.',
      ),
    ),
    'storage' => 'cache',
    'cache_store' => 'default',
    'redis_database' => 0,
    'exempt_ips' => 
    array (
    ),
    'headers' => 
    array (
      0 => 'X-RateLimit-Limit',
      1 => 'X-RateLimit-Remaining',
      2 => 'X-RateLimit-Reset',
      3 => 'Retry-After',
    ),
    'response_format' => 'json',
    'logging' => 
    array (
      'enabled' => true,
      'channel' => 'stack',
      'level' => 'warning',
    ),
  ),
  'security' => 
  array (
    'headers' => 
    array (
      'x-frame-options' => 'DENY',
      'x-content-type-options' => 'nosniff',
      'x-xss-protection' => '1; mode=block',
      'referrer-policy' => 'strict-origin-when-cross-origin',
      'permissions-policy' => 'geolocation=(), microphone=(), camera=()',
      'strict-transport-security' => 'max-age=31536000; includeSubDomains',
    ),
    'cors' => 
    array (
      'allowed_origins' => 'https://yourdomain.com',
      'allowed_methods' => 
      array (
        0 => 'GET',
        1 => 'POST',
        2 => 'PUT',
        3 => 'PATCH',
        4 => 'DELETE',
      ),
      'allowed_headers' => 
      array (
        0 => 'Content-Type',
        1 => 'Authorization',
        2 => 'X-Requested-With',
      ),
      'exposed_headers' => 
      array (
      ),
      'max_age' => 0,
      'supports_credentials' => false,
    ),
    'rate_limiting' => 
    array (
      'enabled' => true,
      'max_attempts' => 60,
      'decay_minutes' => 1,
      'throttle_requests' => 
      array (
        'login' => 5,
        'register' => 3,
        'password_reset' => 10,
      ),
    ),
    'file_uploads' => 
    array (
      'allowed_extensions' => 
      array (
        0 => 'jpg',
        1 => 'jpeg',
        2 => 'png',
        3 => 'gif',
        4 => 'pdf',
        5 => 'doc',
        6 => 'docx',
      ),
      'max_size' => 10240,
      'scan_for_viruses' => true,
      'quarantine_suspicious' => true,
    ),
    'session' => 
    array (
      'secure' => true,
      'http_only' => true,
      'same_site' => 'lax',
      'lifetime' => 120,
      'expire_on_close' => true,
    ),
    'authentication' => 
    array (
      'password_min_length' => 12,
      'require_special_chars' => true,
      'require_numbers' => true,
      'require_uppercase' => true,
      'require_lowercase' => true,
      'max_login_attempts' => 5,
      'lockout_duration' => 15,
      'two_factor_required' => false,
    ),
    'csrf' => 
    array (
      'enabled' => true,
      'token_lifetime' => 60,
      'regenerate_on_login' => true,
    ),
    'logging' => 
    array (
      'security_events' => true,
      'failed_logins' => true,
      'suspicious_activity' => true,
      'file_access' => true,
      'database_queries' => false,
    ),
    'firewall' => 
    array (
      'enabled' => true,
      'blocked_ips' => 
      array (
      ),
      'allowed_ips' => 
      array (
      ),
      'block_suspicious_requests' => true,
      'block_common_attacks' => true,
    ),
    'encryption' => 
    array (
      'algorithm' => 'AES-256-CBC',
      'key_rotation' => true,
      'key_rotation_interval' => 30,
    ),
    'backup' => 
    array (
      'enabled' => true,
      'encrypt_backups' => true,
      'backup_frequency' => 'daily',
      'retention_days' => 30,
    ),
  ),
  'services' => 
  array (
    'mailgun' => 
    array (
      'domain' => NULL,
      'secret' => NULL,
      'endpoint' => 'api.mailgun.net',
      'scheme' => 'https',
    ),
    'postmark' => 
    array (
      'token' => NULL,
    ),
    'ses' => 
    array (
      'key' => '',
      'secret' => '',
      'region' => 'us-east-1',
    ),
    'hcaptcha' => 
    array (
      'site_key' => NULL,
      'secret_key' => NULL,
    ),
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => '120',
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => 'C:\\xampp\\htdocs\\webSys\\storage\\framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'store' => NULL,
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'websys_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => true,
    'http_only' => true,
    'same_site' => 'lax',
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => 'C:\\xampp\\htdocs\\webSys\\resources\\views',
    ),
    'compiled' => 'C:\\xampp\\htdocs\\webSys\\storage\\framework\\views',
    'debug' => false,
  ),
  'sanctum' => 
  array (
    'stateful' => 
    array (
      0 => 'localhost',
      1 => 'localhost:3000',
      2 => '127.0.0.1',
      3 => '127.0.0.1:8000',
      4 => '::1',
      5 => 'yourdomain.com',
    ),
    'guard' => 
    array (
      0 => 'web',
    ),
    'expiration' => NULL,
    'token_prefix' => '',
    'middleware' => 
    array (
      'authenticate_session' => 'Laravel\\Sanctum\\Http\\Middleware\\AuthenticateSession',
      'encrypt_cookies' => 'App\\Http\\Middleware\\EncryptCookies',
      'verify_csrf_token' => 'App\\Http\\Middleware\\VerifyCsrfToken',
    ),
  ),
  'tinker' => 
  array (
    'commands' => 
    array (
    ),
    'alias' => 
    array (
    ),
    'dont_alias' => 
    array (
      0 => 'App\\Nova',
    ),
  ),
);
