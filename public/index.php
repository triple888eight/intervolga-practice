<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Tuupola\Middleware\HttpBasicAuthentication as Auth;
use App\DB\Config;

require __DIR__ . '/../vendor/autoload.php'; // Автозагрузка

$app = AppFactory::create();

/*$app->setBasePath("/composer/public/index.php"); // Указываю базовый путь, иначе ошибка*/

require __DIR__ . '/../routes/routes.php';

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

try {
    $app->run();
} catch (Exception $e) {
    // Сообщение об ошибке
    die( json_encode(array("status" => "failed", "message" => "This action is not allowed")));
}
