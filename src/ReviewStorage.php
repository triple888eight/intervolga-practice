<?php

namespace App;

// Запросы к БД
use DateTime;

class ReviewStorage
{
    private \PDO $connection;

    /**
     * @param \PDO $connection
     */
    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    // Функция для вывода отзыва по id
    public function getReviewById(int $id): Review
    {
        $sql = 'SELECT * 
                FROM reviews 
                WHERE id = :id;';
        // Подготовка запроса
        $stmt = $this->connection->prepare($sql);

        $stmt->execute([':id' => $id]);
        // Массив для отзывов

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {
            throw new \Exception('Not found');
        }

        return new Review($result['id'], $result['guest_id'], $result['rating'], $result['review'], DateTime::createFromFormat('Y-m-d', $result['date']));
    }

    // Функция отображения базы данных постранично
    public function getFeedbackByPage($page)
    {
        $rows = $this->connection->query('SELECT count(*) FROM reviews;')->fetchColumn(); // Получаем количество записей

        // Проверяем GET запрос,
        if (($page - 1 > $rows / 20) || ($page <= 0)) {
            throw new \Exception('Not found');
        }

        $records = 20; // Сколько выводим записей
        $page = $page - 1;
        $page = $page * $records; // Определяем какая страницы

        $sql = 'SELECT * 
                FROM reviews 
                ORDER BY date asc
                LIMIT :page, :records;';
        $stmt = $this->connection->prepare($sql);

        $stmt->execute([':page' => $page, ':records' => $records]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function addReview(Review $review): void
    {

        $sql = 'INSERT INTO reviews (guest_id, rating, review, date) VALUES (:guest_id, :rating, :review, :date);';

        $stmt = $this->connection->prepare($sql);

        if (!$stmt)
        {
            throw new \Exception('Review was not added');
        }

        $result = $stmt->execute([':guest_id' => $review->guestId, ':rating' => $review->rating, ':review' => $review->review, ':date' => $review->date->format('Y-m-d')]);

        $review->id = $this->connection->lastInsertId();
    }

    public function deleteReview(Review $review): void
    {
        if(!isset($review->id)) {
            throw new \Exception('Not found');
        }

        $sql = 'DELETE FROM reviews WHERE id = :id;';

        $stmt = $this->connection->prepare($sql);

        if (!$stmt)
        {
            throw new \Exception('Review was not deleted');
        }

        $stmt->execute([':id' => $review->id]);

    }
}




