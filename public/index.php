<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Tuupola\Middleware\HttpBasicAuthentication as Auth;
use App\Connection;
use App\reviewStorage;
use App\Config;

require __DIR__ . '/../vendor/autoload.php'; // Автозагрузка

$app = AppFactory::create();
$container = $app->getContainer();

/*$app->setBasePath("/composer/public/index.php"); // Указываю базовый путь, иначе ошибка*/

// Hello world
$app->get('/hello', \App\Controllers\HelloController::class . ':hello');

// Hello, {name}
$app->get('/hello/{name}', \App\Controllers\HelloController::class . ':helloName');

// Получение отзыва по id
$app->get('/api/feedbacks/{id}', \App\Controllers\FeedbackController::class . ':getFeedbackById');

// Постраничный вывод отзывов, страницы указывается как page=...
$app->get('/api/feedbacks/', \App\Controllers\FeedbackController::class . ':getFeedbacksPageByPage');

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
$app->get('/delete', function (Request $request, Response $response){
    header('Content-type: text/html; charset=utf-8');

    $renderer = new PhpRenderer("../templates");
    return $renderer->render($response,"delete_review.php");
});

$app->post('/deleting', function (Request $request, Response $response) {
    $pdo = Connection::connect(); // Подключение к БД

    $sqlite = new reviewStorage;

    // Получаю значения с формы в массив
    $data = $request->getParsedBody();

    $result = $sqlite->deleteReview($pdo, $data);
    $response->getBody()->write($result);

    return $response;
});

/*$app->run();*/

try {
    $app->run();
} catch (Exception $e) {
    // Сообщение об ошибке
    die( json_encode(array("status" => "failed", "message" => "This action is not allowed")));
}
