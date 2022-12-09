<?php 

namespace App;

// Подключение к базе данных
class Connection {
    private static $pdo = null;

    public static function connect() {

        if (static::$pdo == null) {
            try {
                static::$pdo = new \PDO("sqlite:" . Config::dataBasePath);
            } catch (\PDOException $e) {
                echo "Ошибка при подключении к базе данных";
            }
        }

        return static::$pdo;
    }
}