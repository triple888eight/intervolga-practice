<?php

namespace App\Controllers;
use App\Connection;
use App\reviewStorage;

class DeletingController {

    public function deleteReviewById($request, $response){
        $pdo = Connection::connect(); // Подключение к БД

        $sqlite = new reviewStorage;

        // Получаю значения с формы в массив
        $data = $request->getParsedBody();

        $result = $sqlite->deleteReview($pdo, $data);
        $response->getBody()->write($result);

        return $response;
    }
}