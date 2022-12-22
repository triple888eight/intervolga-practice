<?php

namespace App\Controllers;

// Контроллеры, которые возвращают Hello
class HelloController {

    public function hello($request, $response) {
        $response->getBody()->write("Hello world!");

        return $response;
    }

    public function helloName($request, $response, $args) {
        $name = $args['name'];
        $response->getBody()->write("Hello, $name!");
        return $response;
    }
}