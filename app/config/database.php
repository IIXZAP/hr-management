<?php

$dbHost = $_ENV['DB_HOST'] ?? 'localhost';
$dbName = $_ENV['DB_NAME'] ?? 'dev_system';
$dbUser = $_ENV['DB_USER'] ?? 'dev_system';
$dbPass = $_ENV['DB_PASS'] ?? 'Asdfghjkl1';
$dbCharset = 'utf8mb4';

$databaseConfig = [
    'host' => $dbHost,
    'dbname' => $dbName,
    'username' => $dbUser,
    'password' => $dbPass,
    'charset' => $dbCharset,
];

return $databaseConfig;