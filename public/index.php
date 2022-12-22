<?php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;
use Tuupola\Middleware\HttpBasicAuthentication as Auth;

require __DIR__ . '/../vendor/autoload.php'; // Автозагрузка

$config = include(__DIR__ . '/../config/config.php');

try {
    $connection = new \PDO("sqlite:" . $config['dataBasePath']);
} catch (\PDOException $e) {
    throw new ConnectionException('Ошибка при подключении к базе данных', 0, $e);
}

$app = AppFactory::create();

/*$app->setBasePath("/composer/public/index.php"); // Указываю базовый путь, иначе ошибка*/

// Hello world
$app->get('/hello', \App\Controllers\HelloController::class . ':hello');

// Hello, {name}
$app->get('/hello/{name}', \App\Controllers\HelloController::class . ':helloName');

$reviewStorage = new \App\ReviewStorage($connection);
$feedbackController = new \App\Controllers\FeedbackController($reviewStorage);

// Получение отзыва по id
$app->get('/api/feedbacks/{id}/', array($feedbackController, 'getFeedbackById'));

// Постраничный вывод отзывов, страницы указывается как page=...
$app->get('/api/feedbacks/', array($feedbackController, 'getFeedbacksPageByPage'));

// Отображение страницы для добавления пользователя
$app->get('/api/add', function (Request $request, Response $response){
    header('Content-type: text/html; charset=utf-8');

    $renderer = new PhpRenderer("../templates");
    return $renderer->render($response,"add_review.php");
});

// Добавление отзыва с помощью Js и AJAX
$app->post('/api/adding', array($feedbackController, 'AddingReviewByJs'));

$config = include(__DIR__ . '/../config/config.php');

// Basic аутентификация
$app->add(new Auth([
    "path" => "/admin/delete",
    "realm" => "Protected",
    "users" => [
        $config['adminLogin'] => $config['adminPassword']
    ]
]));

// Отображение страницы удаления
$app->get('/admin/delete', function (Request $request, Response $response){
    header('Content-type: text/html; charset=utf-8');

    $renderer = new PhpRenderer("../templates");
    return $renderer->render($response,"delete_review.php");
});

$app->post('/admin/deleting', array($feedbackController, 'deleteReviewById'));

$app->run();