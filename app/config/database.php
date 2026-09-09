<?php

$dbHost = $_ENV['DB_HOST'] ?? 'localhost';
$dbName = $_ENV['DB_NAME'] ?? 'dev_system';
$dbUser = $_ENV['DB_USER'] ?? 'root';
$dbPass = $_ENV['DB_PASS'] ?? '';
$dbCharset = 'utf8mb4';

$databaseConfig = [
    'host' => $dbHost,
    'dbname' => $dbName,
    'username' => $dbUser,
    'password' => $dbPass,
    'charset' => $dbCharset,
];

return $databaseConfig;