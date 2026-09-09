<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'ecoride');
define('DB_USER', 'root');
define('DB_PASS', '');

$server = $_SERVER['SERVER_NAME'] ?? '';

if (in_array($server, ['localhost', '127.0.0.1'], true)) {
    define('BASE_URL', '/ecoridestudi/ecoride/');
} else {
    define('BASE_URL', '/');
}