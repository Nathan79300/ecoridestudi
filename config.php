<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'ecoride');
define('DB_USER', 'root');
define('DB_PASS', '');

$host = $_SERVER['HTTP_HOST'] ?? '';

if (
    $host === 'localhost' ||
    str_starts_with($host, 'localhost:') ||
    $host === '127.0.0.1' ||
    str_starts_with($host, '127.0.0.1:')
) {
    // SITE EN LOCAL
    define('BASE_URL', '/ecoridestudi/ecoride/public/');
} else {
    // SITE SUR RENDER
    define('BASE_URL', '/');
}