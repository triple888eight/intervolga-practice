<?php 

namespace App;

// Подключение к базе данных
class Connection {

    private $pdo;
   
    public function connect() {

      try {
          $this->pdo = new \PDO("sqlite:" . Config::dataBasePath);
      } catch (\PDOException $e) {
           //
      }
      return $this->pdo;
    }

}