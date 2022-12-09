<?php

namespace App\Controllers;
use App\Connection;
use App\reviewStorage;

class FeedbackController {

    public function getFeedbackById ($request, $response, $args) {
        $pdo = Connection::connect(); // Подключение к БД

        $sqlite = new reviewStorage;
        $id = (int)$args['id'];

        $result = $sqlite->getReviewById($id, $pdo);

        $response->getBody()->write($result);

        return $response;
    }

    public function getFeedbacksPageByPage($request, $response) {
        $pdo = Connection::connect(); // Подключение к БД
        $sqlite = new reviewStorage;

        // Берем значение page из GET запроса, если его нет, то выводится 1 страница
        if($_GET) $page = $_GET['page'];
        else $page = 1;

        // Вызывается метод из reviewStorage
        $result = $sqlite->getNavReviews($page, $pdo);

        $response->getBody()->write($result);

        return $response;
    }
}