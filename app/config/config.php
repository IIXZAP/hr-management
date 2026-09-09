<?php

use Dotenv\Dotenv;

require_once __DIR__ . '/../../vendor/autoload.php';

// path project -> ถอยออกไป 2 ระดับจากโฟลเดอร์ config
$rootPath = dirname(__DIR__, 2);

$dotenv = Dotenv::createImmutable($rootPath);
$dotenv->load();

$appName = $_ENV['APP_NAME'];
$appUrl = $_ENV['APP_URL'];
$sessionLifetime = $_ENV['SESSION_LIFETIME'] ?? 7200; 

define('APP_NAME', $appName);
define('APP_URL', $appUrl);
define('SESSION_LIFETIME', (int) $sessionLifetime );
define('BASE_PATH', dirname(__DIR__));
define('UPLOAD_PATH', BASE_PATH . '/../public/uploads');

date_default_timezone_set('Asia/Bangkok');