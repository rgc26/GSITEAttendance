<?php
/**
 * Simple redirect to public directory
 * Working solution for now
 */

// Redirect to the working public directory
header("Location: /public/", true, 301);
exit;
