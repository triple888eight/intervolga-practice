<!DOCTYPE html>
<html lang="ru-en">
<head>
    <meta charset="UTF-8">
    <title>Страница добавления</title>
    <style>
        <?php include "css/style.css" ?>
    </style>
    <?php require_once('logic.php');?>
</head>
<body>
<div>
    <h1>Вы попали на страницу добавления отзывов</h1>
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

            <tbody>
            <?php foreach($reviews as $review): ?>
                <tr>
                    <td><?= $review['id']?></td>
                    <td><?= $review['guest_id']?></td>
                    <td><?= $review['rating']?></td>
                    <td><?= $review['review']?></td>
                    <td><?= $review['date']?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</div>
</body>