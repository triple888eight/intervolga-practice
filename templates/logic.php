<?php

use App\Connection;
use App\reviewStorage;

if($_GET) $page = $_GET['page'];
else $page = 1;

$pdo = (new Connection())->connect(); // Подключение к БД
$sqlite = new reviewStorage;

// Все отзывы для таблицы, чтобы наглядно видеть какие есть отзывы
$reviews = $sqlite->getNavReviews($page, $pdo);
$reviews = json_decode($reviews, true);