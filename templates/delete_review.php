<!DOCTYPE html>
<html lang="ru-en">
<head>
    <meta charset="UTF-8">
    <title>Страница удаления</title>
    <style>
        <?php include "css/style.css" ?>
    </style>
</head>
<body>
    <div>
        <h1>Вы попали на страницу удаления отзывов</h1>
        <form method = "POST" action="/admin/deleting" class="form">
            <fieldset>
                <legend>ВВЕДИТЕ ID ДЛЯ УДАЛЕНИЯ</legend>
                <p>
                    <label for = "guest_id">id пользователя для удаления</label>
                    <input type = "number" name = "id" min = "1" class="num">
                </p>
                <p>
                    <button type="submit" class="btn">Удалить</button>
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
    </div>

    <script>
        <?php require_once("../scripts/jquery_min.js");?>
        <?php require_once("../scripts/script.js");?>
    </script>
</body>