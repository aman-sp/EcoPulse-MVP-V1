<?php

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', __DIR__);
define('STORAGE_PATH', BASE_PATH . '/storage');

$scriptName = $_SERVER['SCRIPT_NAME'];
$basePath = dirname($scriptName);
$basePath = str_replace('\\', '/', $basePath);
if ($basePath === '/') {
    $basePath = '';
}

define('BASE_URL', $basePath);
define('ASSET_URL', BASE_URL . '/assets');

require_once APP_PATH . '/Config/config.php';

spl_autoload_register(function($class) {
    $paths = [
        APP_PATH . '/Core/' . $class . '.php',
        APP_PATH . '/Controllers/' . $class . '.php',
        APP_PATH . '/Models/' . $class . '.php',
        APP_PATH . '/Helpers/' . $class . '.php',
        APP_PATH . '/Middleware/' . $class . '.php',
        APP_PATH . '/Services/' . $class . '.php',
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

Session::start();
require_once BASE_PATH . '/routes/web.php';
Router::dispatch();
