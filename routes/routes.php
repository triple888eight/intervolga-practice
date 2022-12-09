<?php

// Hello world
$app->get('/hello', \App\Controllers\HelloController::class . ':hello');

// Hello, {name}
$app->get('/hello/{name}', \App\Controllers\HelloController::class . ':helloName');

// Получение отзыва по id
$app->get('/api/feedbacks/{id}/', \App\Controllers\FeedbackController::class . ':getFeedbackById');

// Постраничный вывод отзывов, страницы указывается как page=...
$app->get('/api/feedbacks/', \App\Controllers\FeedbackController::class . ':getFeedbacksPageByPage');