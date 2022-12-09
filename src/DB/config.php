<?php 

namespace App\DB;

// Конфиг, хранит путь к базе данных, а также логин и пароль для админа
class Config {
     const dataBasePath = __DIR__ . "/../../db/reviews.db";
     const logAdmin = 'admin';
     const passAdmin = '07Admin2002';
}