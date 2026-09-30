<?php


class Database
{
    public static function connect()
    {
        $config = require BASE_PATH . '/config/database.php';

        $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'] . ';charset=' . $config['charset'];

        $conn = new PDO($dsn, $config['username'], $config['password']);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }
}
