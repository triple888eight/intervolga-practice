<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Exception\NotFoundException;

require __DIR__ . '/../vendor/autoload.php'; // Автозагрузка

$app = AppFactory::create();

$app->setBasePath("/composer/public/index.php"); // Указываю базовый путь, иначе ошибка

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name!");
    return $response;
});

// try {
//     $app->run();     
// } catch (Exception $e) {    
//     // We display a error message
//     die( json_encode(array("status" => "failed", "message" => "This action is not allowed"))); 
// }

$app->run();