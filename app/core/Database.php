<?php

// include_once __DIR__ . '/../config/config.php';

// class Database
// {
//     private static $connection = null;

//     public static function connect()
//     {
//         if (self::$connection !== null) {
//             return self::$connection;
//         }

//         $config = require BASE_PATH . '/config/database.php';

//         $host = $config['host'];
//         $dbname = $config['dbname'];
//         $username = $config['username'];
//         $password = $config['password'];
//         $charset = $config['charset'];

//         $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname . ';charset=' . $charset;

//         $options = [
//             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//             PDO::ATTR_EMULATE_PREPARES => false,
//         ];

//         try {
//             $pdo = new PDO($dsn, $username, $password, $options);
//         } catch (PDOException $error) {
//             die('Cant connect' . $error->getMessage());
//         }

//         self::$connection = $pdo;

//         return self::$connection;
//     }

//     public static function query($sql, $params = [])
//     {
//         $pdo = self::connect();
//         $stmt = $pdo->prepare($sql);
//         $stmt->execute($params);

//         return $stmt;
//     }

//     // public static function getLastInsertId()
//     // {
//     //     $pdo = self::connect();
//     //     $lastId = $pdo->lastInsertId();

//     //     return $lastId;
//     // }
// }


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
