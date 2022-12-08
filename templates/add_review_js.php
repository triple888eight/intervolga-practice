<!DOCTYPE html>
<html lang="ru-en">
<head>
    <meta charset="UTF-8">
    <title>Страница добавления</title>
    <style>
        <?php include "css/style.css" ?>
    </style>
</head>
<body>
    <h1>Вы попали на страницу добавления отзывов</h1>
    <form method ="POST" action="/api/add" class="form" id="addPost">
        <fieldset>
            <legend>ВВЕДИТЕ ОТЗЫВ</legend>
            <p>
                <label for = "guest_id">Номер гостя</label>
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

    <table class="table">
        <caption>Отзывы. Отсортированы по дате добавления</caption>
        <thead>
        <tr>
            <th>id</th>
            <th>Номер гостя</th>
            <th>Рейтинг</th>
            <th>Отзыв</th>
            <th>Дата добавления</th>
        </tr>
        </thead>

        <tbody id = "tbody">
        <tr>

        </tr>
        </tbody>
    </table>

    <form method = "GET" action="/api/feedbacks/" id = "getPages">
        <input type = "hidden" id = "page" value = "0" name = "page">
    </form>

    <div class="btn-container">
        <button class="btn" type="submit" id = "previous">Предыдущая страница</button>
        <button class="btn" type="submit" id = "next">Следующая страница</button>
    </div>

    <script>
        <?php require_once("../scripts/jquery_min.js");?>
        <?php require_once("../scripts/script.js");?>
    </script>
</body>