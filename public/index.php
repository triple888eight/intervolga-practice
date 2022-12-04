<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use App\Connection;
use App\reviewStorage;

require __DIR__ . '/../vendor/autoload.php'; // Автозагрузка

$app = AppFactory::create();
$container = $app->getContainer();

/*$app->setBasePath("/composer/public/index.php"); // Указываю базовый путь, иначе ошибка*/

// Hello world
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

// Hello, {name}
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name!");
    return $response;
});

// Проверка на подключение к базе данных
$app->get('/connect', function (Request $request, Response $response, array $args) {
    $pdo = (new Connection())->connect();

    if ($pdo != null) { // Проверка, есть ли подключение к БД
        $response->getBody()->write('Connected to the SQLite database successfully!');
        return $response;
    }
    else {
        $response->getBody()->write('Whoops, could not connect to the SQLite database!');
        return $response;
    }

});

// Получение отзыва
$app->get('/api/feedbacks/{id}/', function (Request $request, Response $response, array $args){
    /*header('Content-type: application/json; charset=utf-8');*/

    $pdo = (new Connection())->connect(); // Подключение к БД

    $sqlite = new reviewStorage;
    $id = (int)$args['id'];

    $result = $sqlite->getReviewById($id, $pdo);

    $response->getBody()->write($result);

    // из документации
    /*return $response->withHeader('content-type','application/json');*/
    return $response;
});

// Постраничный вывод отзывов, страницы указывается как page=...
$app->get('/api/feedbacks/', function (Request $request, Response $response, array $args) {
    /*header('Content-type: application/json; charset=utf-8');*/

    $pdo = (new Connection())->connect(); // Подключение к БД
    $sqlite = new reviewStorage;

    // Берем значение page из GET запроса, если его нет, то выводится 1 страница
    if($_GET) $page = $_GET['page'];
    else $page = 1;

    // Вызывается метод из reviewStorage
    $result = $sqlite->getNavReviews($page, $pdo);

    /*$array = json_decode($result);
    print_r($array);*/

    $response->getBody()->write($result);

    // из документации
    /*return $response->withHeader('content-type','application/json');*/
    return $response;
});

$app->get('/add', function (Request $request, Response $response){
    header('Content-type: text/html; charset=utf-8');
    $renderer = new PhpRenderer("../templates");
    return $renderer->render($response,"add_review.php");
});

$app->post('/adding', function (Request $request, Response $response){
    header('Content-type: text/html; charset=utf-8');

    $pdo = (new Connection())->connect(); // Подключение к БД
    $sqlite = new reviewStorage;

    // Получаю значения с формы в массив
    $data = $request->getParsedBody();

    $result = $sqlite->addReview($pdo, $data);
    $response->getBody()->write($result);

    /*print_r($data);*/
    return $response;
});

$app->run();

/*try {
     $app->run();
 } catch (Exception $e) {
     // Сообщение об ошибке
     die( json_encode(array("status" => "failed", "message" => "This action is not allowed")));
 }*/
