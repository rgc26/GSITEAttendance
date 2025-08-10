<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class Firewall
{
    /**
     * Suspicious patterns to block
     */
    protected $suspiciousPatterns = [
        'eval\s*\(', 'exec\s*\(', 'system\s*\(', 'shell_exec\s*\(',
        'passthru\s*\(', 'proc_open\s*\(', 'popen\s*\(', 'curl_exec\s*\(',
        'file_get_contents\s*\(', 'file_put_contents\s*\(', 'fopen\s*\(',
        'include\s*\(', 'require\s*\(', 'include_once\s*\(', 'require_once\s*\(',
        'union\s+select', 'select\s+.*\s+from', 'insert\s+into', 'update\s+.*\s+set',
        'delete\s+from', 'drop\s+table', 'create\s+table', 'alter\s+table',
        'grant\s+.*\s+to', 'revoke\s+.*\s+from', 'backup\s+database',
        'javascript:', 'vbscript:', 'onload\s*=', 'onerror\s*=', 'onclick\s*=',
        'document\.cookie', 'window\.location', 'innerHTML', 'outerHTML'
    ];

    /**
     * Blocked user agents
     */
    protected $blockedUserAgents = [
        'bot', 'crawler', 'spider', 'scraper', 'curl', 'wget', 'python',
        'java', 'perl', 'ruby', 'php', 'go-http-client', 'masscan'
    ];

    /**
     * Rate limiting settings
     */
    protected $rateLimit = [
        'max_requests' => 100,
        'time_window' => 60, // seconds
        'block_duration' => 300 // seconds
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $uri = $request->getRequestUri();
        $queryString = $request->getQueryString();

        // Check if IP is blocked
        if ($this->isIpBlocked($ip)) {
            Log::warning('Blocked request from blocked IP', ['ip' => $ip]);
            return response('Access Denied', 403);
        }

        // Check user agent
        if ($this->isUserAgentBlocked($userAgent)) {
            Log::warning('Blocked request with suspicious user agent', [
                'ip' => $ip,
                'user_agent' => $userAgent
            ]);
            return response('Access Denied', 403);
        }

        // Check for suspicious patterns in URI
        if ($this->containsSuspiciousPatterns($uri)) {
            $this->blockIp($ip);
            Log::warning('Blocked suspicious URI pattern', [
                'ip' => $ip,
                'uri' => $uri,
                'user_agent' => $userAgent
            ]);
            return response('Access Denied', 403);
        }

        // Check for suspicious patterns in query string
        if ($queryString && $this->containsSuspiciousPatterns($queryString)) {
            $this->blockIp($ip);
            Log::warning('Blocked suspicious query string', [
                'ip' => $ip,
                'query_string' => $queryString,
                'user_agent' => $userAgent
            ]);
            return response('Access Denied', 403);
        }

        // Check rate limiting
        if (!$this->checkRateLimit($ip)) {
            Log::warning('Rate limit exceeded', ['ip' => $ip]);
            return response('Too Many Requests', 429);
        }

        // Check for common attack patterns
        if ($this->isCommonAttack($request)) {
            $this->blockIp($ip);
            Log::warning('Blocked common attack pattern', [
                'ip' => $ip,
                'uri' => $uri,
                'user_agent' => $userAgent
            ]);
            return response('Access Denied', 403);
        }

        return $next($request);
    }

    /**
     * Check if IP is blocked
     */
    protected function isIpBlocked($ip): bool
    {
        return Cache::has("blocked_ip_{$ip}");
    }

    /**
     * Block an IP address
     */
    protected function blockIp($ip): void
    {
        Cache::put("blocked_ip_{$ip}", true, $this->rateLimit['block_duration']);
    }

    /**
     * Check if user agent is blocked
     */
    protected function isUserAgentBlocked($userAgent): bool
    {
        if (!$userAgent) {
            return true; // Block requests without user agent
        }

        $userAgent = strtolower($userAgent);
        foreach ($this->blockedUserAgents as $blocked) {
            if (strpos($userAgent, $blocked) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check for suspicious patterns
     */
    protected function containsSuspiciousPatterns($string): bool
    {
        $string = strtolower($string);
        foreach ($this->suspiciousPatterns as $pattern) {
            if (preg_match("/{$pattern}/i", $string)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check rate limiting
     */
    protected function checkRateLimit($ip): bool
    {
        $key = "rate_limit_{$ip}";
        $requests = Cache::get($key, 0);

        if ($requests >= $this->rateLimit['max_requests']) {
            return false;
        }

        Cache::put($key, $requests + 1, $this->rateLimit['time_window']);
        return true;
    }

    /**
     * Check for common attack patterns
     */
    protected function isCommonAttack(Request $request): bool
    {
        $uri = strtolower($request->getRequestUri());
        $queryString = strtolower($request->getQueryString() ?? '');

        // Check for common attack paths
        $attackPaths = [
            '/admin', '/wp-admin', '/phpmyadmin', '/mysql', '/sql',
            '/config', '/backup', '/db', '/database', '/phpinfo',
            '/shell', '/cmd', '/exec', '/system'
        ];

        foreach ($attackPaths as $path) {
            if (strpos($uri, $path) !== false) {
                return true;
            }
        }

        // Check for suspicious file extensions in URI
        if (preg_match('/\.(php|php3|php4|php5|phtml|pl|py|jsp|asp|sh|cgi|exe|bat|cmd)$/i', $uri)) {
            return true;
        }

        // Check for suspicious parameters
        $suspiciousParams = ['cmd', 'exec', 'system', 'shell', 'eval', 'code'];
        foreach ($suspiciousParams as $param) {
            if ($request->has($param)) {
                return true;
            }
        }

        return false;
    }
}
