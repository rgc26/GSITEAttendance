<?php
/**
 * Test file to check .htaccess functionality
 */

echo "<h1>HTAccess Test</h1>";

// Check if mod_rewrite is available
echo "<h2>Server Information:</h2>";
echo "<p><strong>Server Software:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";
echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";

// Check if .htaccess is being read
echo "<h2>HTAccess Test:</h2>";
echo "<p>If you can see this file, .htaccess is NOT working properly.</p>";
echo "<p>If you get redirected to public/, .htaccess IS working.</p>";

// Check available Apache modules
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    echo "<h2>Available Apache Modules:</h2>";
    echo "<ul>";
    foreach ($modules as $module) {
        $status = in_array($module, ['mod_rewrite', 'mod_alias']) ? ' <strong>(IMPORTANT)</strong>' : '';
        echo "<li>{$module}{$status}</li>";
    }
    echo "</ul>";
} else {
    echo "<p><strong>Note:</strong> Cannot check Apache modules (function not available)</p>";
}

echo "<hr>";
echo "<p><a href='./'>Go to root</a> | <a href='./public/'>Go to public</a></p>";
?>
