<?php

namespace App\Controllers;
use App\Connection;
use App\reviewStorage;

class AddingController {

    public function AddingReviewByJs($request, $response) {
        $pdo = Connection::connect(); // Подключение к БД

        $sqlite = new reviewStorage;

        // Получаю значения с формы в массив
        $data = $request->getParsedBody();

        $result = $sqlite->addReviewByJs($pdo, $data);

        $response->getBody()->write(json_encode($result));

        return $response;
    }
}