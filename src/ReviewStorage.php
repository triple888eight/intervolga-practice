<?php

namespace App;

// Запросы к БД
class ReviewStorage
{
    private \PDO $connection;
    // Функция для вывода отзыва по id

    /**
     * @param \PDO $connection
     */
    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getReviewById($id)
    {
        $sql = 'SELECT * 
                FROM reviews 
                WHERE id = :id;';
        // Подготовка запроса
        $stmt = $this->connection->prepare($sql);

        if(!$stmt){
            echo "Prepare failed";
        }

        $stmt->execute([':id' => $id]);
        // Массив для отзывов

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {
            throw new \Exception('Not found');
        }

        // JSON_UNESCAPED_UNICODE необходим для кириллицы
        return $result;
    }

    // Функция отображения базы данных постранично
    public function getFeedbackByPage($page)
    {
        $rows = $this->connection->query('SELECT count(*) FROM reviews')->fetchColumn(); // Получаем количество записей

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

    public function deleteReview($data)
    {
        $id = $data['id'];

        $sql = 'DELETE FROM reviews
                WHERE id = :id;';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':id' => $id]);

        $response = 'Запись удалена, проверяйте!';

        return $response;
    }

    public function addReview($data)
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

        $stmt = $this->connection->prepare($sql);

        $result = $stmt->execute([':guest_id' => $guest_id, ':rating' => $rating, ':review' => $review, ':date' => $date]);

        // Если не получился запрос
        if(!$result)
        {
            return "Ошибка при добавлении в базу данных";
        }

        return "Запись успешно добавлена. Проверяйте";
    }
}




