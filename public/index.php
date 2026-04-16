<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Natom\Ecoride\Router;

$url = $_GET['url'] ?? "";

$router = new Router();
$router->run($url);
