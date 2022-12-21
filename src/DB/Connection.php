<?php 

namespace App\DB;

// Подключение к базе данных
class Connection {
    private static $pdo = null;

    public static function connect() {

        $config = include(__DIR__ . '/../../config/config.php');

        if (static::$pdo == null) {
            try {
                static::$pdo = new \PDO("sqlite:" . $config['dataBasePath']);
            } catch (\PDOException $e) {
                echo "Ошибка при подключении к базе данных\r";
                echo $e;
            }
        }

        return static::$pdo;
    }
}