<?php 

namespace App\DB;

// Подключение к базе данных
class Connection {

    public static function connect() {

        $config = include(__DIR__ . '/../../config/config.php');

        try {
            return new \PDO("sqlite:" . $config['dataBasePath']);
        } catch (\PDOException $e) {
            throw new ConnectionException('Ошибка при подключении к базе данных', 0, $e);
        }
    }
}