<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php'; // Автозагрузка

$app = AppFactory::create();

/*$app->setBasePath("/composer/public/index.php"); // Указываю базовый путь, иначе ошибка*/

require __DIR__ . '/../routes/routes.php';

require __DIR__ . '/../views/views.php';

try {
    $app->run();
} catch (Exception $e) {
    echo "Адрес не поддерживается";
}
