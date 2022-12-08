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

    // Функция отображения базы данных постранично
    public function getNavReviews($page, $pdo)
    {
        $rows = $pdo->query('SELECT count(*)
                             FROM reviews')->fetchColumn(); // Получаем количество записей

        // Проверяем GET запрос,
        if (($page - 1 > $rows / 20) || ($page <= 0)) {
            $reviews = [];
            return $reviews;
        }

        $records = 20; // Сколько выводим записей
        $page = $page - 1;
        $page = $page * $records; // Определяем какая страницы

        $sql = 'SELECT * 
                FROM reviews 
                ORDER BY date asc
                LIMIT :page, :records;';
        $stmt = $pdo->prepare($sql);

        $stmt->execute([':page' => $page, ':records' => $records]);
        $reviews = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // JSON_UNESCAPED_UNICODE необходим для кириллицы
        return json_encode($reviews,JSON_UNESCAPED_UNICODE);
    }

    // Функция добавления отзыва
    public function addReview($pdo, $data)
    {

        $guest_id = $data['guest_id'];
        $rating = $data['rating'];
        $review = $data['review'];
        $date = $data['date'];

        $sql = 'INSERT INTO reviews (guest_id, rating, review, date)
                VALUES (:guest_id, :rating, :review, :date);';

        $stmt = $pdo->prepare($sql);

        $result = $stmt->execute([':guest_id' => $guest_id, ':rating' => $rating, ':review' => $review, ':date' => $date]);

        $response = 'Запись добавлена, проверяйте!';

        return $response;
    }

    public function deleteReview($pdo, $data)
    {
        $id = $data['id'];

        $sql = 'DELETE FROM reviews
                WHERE id = :id;';

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([':id' => $id]);

        $response = 'Запись удалена, проверяйте!';

        return $response;
    }

    public function addReviewByJs ($pdo, $data)
    {
        $guest_id = $data['guest_id'];
        $rating = $data['rating'];
        $review = $data['review'];
        $date = $data['date'];

        // Количество не пустых элементов в массиве
        $filledValues = count(array_filter($data));

        // Если не все поля заполнены
        if(count($data) != $filledValues)
        {
            return "Ошибка. Заполните все поля";
        }

        $sql = 'INSERT INTO reviews (guest_id, rating, review, date)
                VALUES (:guest_id, :rating, :review, :date);';

        $stmt = $pdo->prepare($sql);

        $result = $stmt->execute([':guest_id' => $guest_id, ':rating' => $rating, ':review' => $review, ':date' => $date]);

        // Если не получился запрос
        if(!$result)
        {
            return "Ошибка при добавлении в базу данных";
        }

        return "Запись успешно добавлена. Проверяйте";
    }
}




