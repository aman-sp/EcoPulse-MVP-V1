<?php

require_once APP_PATH . '/Helpers/EnvLoader.php';
EnvLoader::load(BASE_PATH . '/.env');

define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'ecopulse_db');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('APP_NAME', $_ENV['APP_NAME'] ?? 'EcoPulse');
define('APP_DEBUG', ($_ENV['APP_DEBUG'] ?? 'false') === 'true');
define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost/EcoPulse/public');
