<!DOCTYPE html>
<html lang="ru-en">
<head>
    <meta charset="UTF-8">
    <title>Страница добавления</title>
    <style>
        <?php include "style.css" ?>
    </style>
</head>
<body>
<h1>Вы попали на страницу добавления отзывов</h1>
<div>
    <form method = "POST" action="/adding" class="form">
        <fieldset>
            <legend>ВВЕДИТЕ ОТЗЫВ</legend>
            <p>
                <label for = "guest_id">Ваш id пользователя</label>
                <input type = "number" name = "guest_id" min = "1" class="num">
            </p>
            <p>
                <label for = "rating">Рейтинг заведения</label>
                <input type = "number" name = "rating" min = "1" max = "5" class="num">
            </p>
            <p>
                <label for = "review">Отзыв</label>
                <input type = "text" name = "review">
            </p>
            <p>
                <label for = "date">Дата</label>
                <input type = "date" name = "date">
            </p>
            <p>
                <button type="submit" class="btn">Добавить</button>
            </p>
        </fieldset>
    </form>
</div>
</body>