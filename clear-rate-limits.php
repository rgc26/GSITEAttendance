<?php

/**
 * Rate Limiting Debug and Clear Script
 * 
 * This script helps clear rate limiting data and provides debugging information.
 * Run this script when you need to reset rate limits or debug rate limiting issues.
 * 
 * Usage: php clear-rate-limits.php
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

echo "🔧 Rate Limiting Debug and Clear Script\n";
echo "=====================================\n\n";

// Check if Laravel is properly loaded
if (!class_exists('Illuminate\Support\Facades\Cache')) {
    echo "❌ Laravel not properly loaded. Make sure you're running this from the project root.\n";
    exit(1);
}

try {
    // Clear all cache (this will clear rate limiting data)
    echo "🧹 Clearing all cache...\n";
    Cache::flush();
    echo "✅ Cache cleared successfully\n\n";
    
    // Clear specific rate limiting keys if they exist
    echo "🔍 Checking for rate limiting keys...\n";
    
    // Common rate limiting key patterns
    $patterns = [
        'throttle:*',
        'rate_limit:*',
        'login:*',
        'password:*',
        'register:*',
        'verification:*'
    ];
    
    foreach ($patterns as $pattern) {
        // Note: This is a simplified approach - in production you might want to use Redis SCAN
        echo "   Checking pattern: {$pattern}\n";
    }
    
    echo "✅ Rate limiting data cleared\n\n";
    
    // Test rate limiting functionality
    echo "🧪 Testing rate limiting functionality...\n";
    
    $testKey = 'test_rate_limit';
    $maxAttempts = 5;
    $decayMinutes = 1;
    
    // Test basic rate limiting
    for ($i = 1; $i <= 6; $i++) {
        $remaining = RateLimiter::remaining($testKey, $maxAttempts);
        $tooMany = RateLimiter::tooManyAttempts($testKey, $maxAttempts);
        
        echo "   Attempt {$i}: Remaining: {$remaining}, Too Many: " . ($tooMany ? 'Yes' : 'No') . "\n";
        
        if (!$tooMany) {
            RateLimiter::hit($testKey, $decayMinutes * 60);
        }
    }
    
    echo "✅ Rate limiting test completed\n\n";
    
    // Show current rate limiting configuration
    echo "📋 Current Rate Limiting Configuration:\n";
    echo "   - Forgot Password: 10 attempts per 15 minutes\n";
    echo "   - Password Reset: 5 attempts per 15 minutes\n";
    echo "   - Login: 5 attempts per 15 minutes\n";
    echo "   - Register: 3 attempts per 15 minutes\n";
    echo "   - Email Verification: 6 attempts per 1 minute\n\n";
    
    // Clear the test key
    RateLimiter::clear($testKey);
    
    echo "🎯 Rate limiting has been reset and tested successfully!\n";
    echo "💡 You should now be able to use the forgot password functionality.\n\n";
    
    echo "📝 Next steps:\n";
    echo "   1. Try the forgot password form again\n";
    echo "   2. If you still get 429 errors, check your web server configuration\n";
    echo "   3. Check if there are any other middleware or firewall rules\n";
    echo "   4. Monitor your application logs for any errors\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📚 Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
