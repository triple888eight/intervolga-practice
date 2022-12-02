<?php

namespace App;

// Запросы к БД
class reviewStorage
{

    public function getReviewById($id, $pdo)
    {
        $sql = 'SELECT * FROM reviews WHERE id = :id;';
        // подготовка запроса
        $stmt = $pdo->prepare($sql);

        if(!$stmt){
            echo "Prepare failed";
        }

        $stmt->execute([':id' => $id]);
        // Массив для отзывов
        $reviews = $stmt->fetch(\PDO::FETCH_ASSOC);

        return json_encode($reviews,JSON_UNESCAPED_UNICODE);;
    }
}




