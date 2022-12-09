<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;
use Tuupola\Middleware\HttpBasicAuthentication as Auth;
use App\DB\Config;

// Отображение страницы для добавления пользователя
$app->get('/api/add', function (Request $request, Response $response){
    header('Content-type: text/html; charset=utf-8');

    $renderer = new PhpRenderer("../templates");
    return $renderer->render($response,"add_review_js.php");
});

// Добавление отзыва с помощью Js и AJAX
$app->post('/api/adding', \App\Controllers\AddingController::class . ':AddingReviewByJs');

// Basic аутентификация
$app->add(new Auth([
    "path" => "/delete",
    "realm" => "Protected",
    "users" => [
        Config::logAdmin => Config::passAdmin
    ]
]));

// Отображение страницы удаления
$app->get('/admin/delete', function (Request $request, Response $response){
    header('Content-type: text/html; charset=utf-8');

    $renderer = new PhpRenderer("../templates");
    return $renderer->render($response,"delete_review.php");
});

$app->post('/admin/deleting', \App\Controllers\DeletingController::class . ':deleteReviewById');