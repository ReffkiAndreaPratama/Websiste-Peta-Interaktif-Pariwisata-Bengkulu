<?php

/**
 * INDEX.PHP untuk InfinityFree
 * 
 * Taruh file ini di htdocs/ (document root)
 * Folder Laravel ada di ../laravel/
 */

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Sesuaikan path ke folder Laravel
$laravelPath = __DIR__ . '/../laravel';

// Jika sedang maintenance
if (file_exists($maintenance = $laravelPath . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Composer autoloader
require $laravelPath . '/vendor/autoload.php';

// Bootstrap Laravel
/** @var Application $app */
$app = require_once $laravelPath . '/bootstrap/app.php';

// Tangani request
$app->handleRequest(Request::capture());
