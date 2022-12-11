<?php

namespace App\Controllers;
use App\DB\Connection;
use App\reviewStorage;

class AddingController {

    public function AddingReviewByJs($request, $response) {
        $pdo = Connection::connect(); // Подключение к БД

        if ($pdo == null) {
            return "Ошибка при подключении к базе данных";
        }

        $sqlite = new reviewStorage;

        // Получаю значения с формы в массив
        $data = $request->getParsedBody();

        $result = $sqlite->addReviewByJs($pdo, $data);

        $response->getBody()->write(json_encode($result));

        return $response;
    }
}