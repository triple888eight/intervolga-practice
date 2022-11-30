<?php 

namespace App;

// Подключение к базе данных
class Connection {
    private $pdo;
   
    public function connect() {
      if ($this->pdo == null) {
        $this->pdo = new \PDO("sqlite:" . Config::dataBasePath);
      }
      return $this->pdo;
    }
  }