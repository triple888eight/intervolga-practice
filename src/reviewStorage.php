<?php

namespace App;

// Запросы к БД
class reviewStorage
{
    // Функция для вывода отзыва по id
    public function getReviewById($id, $pdo)
    {
        $sql = 'SELECT * 
                FROM reviews 
                WHERE id = :id;';
        // Подготовка запроса
        $stmt = $pdo->prepare($sql);

        if(!$stmt){
            echo "Prepare failed";
        }

        $stmt->execute([':id' => $id]);
        // Массив для отзывов
        $reviews = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // JSON_UNESCAPED_UNICODE необходим для кириллицы
        return json_encode($reviews,JSON_UNESCAPED_UNICODE);
    }

    public function getNavReview($page, $pdo)
    {
        $rows = $pdo->query('SELECT count(*)
                             FROM reviews')->fetchColumn(); // Получаем количество записей

        // Проверяем GET запрос
        if (($page - 1 > $rows / 20) || ($page <= 0)) {
            $reviews = 'Такой страницы нет';
            return json_encode($reviews,JSON_UNESCAPED_UNICODE);
        }

        $records = 20; // Сколько выводим записей
        $page = $page - 1;
        $page = $page * $records; // Определяем какая страницы

        $sql = 'SELECT * FROM reviews LIMIT :page, :records;';
        $stmt = $pdo->prepare($sql);

        $stmt->execute([':page' => $page, ':records' => $records]);
        $reviews = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // JSON_UNESCAPED_UNICODE необходим для кириллицы
        return json_encode($reviews,JSON_UNESCAPED_UNICODE);
    }
}




