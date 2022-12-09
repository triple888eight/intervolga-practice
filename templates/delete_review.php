<!DOCTYPE html>
<html lang="ru-en">
<head>
    <meta charset="UTF-8">
    <title>Страница удаления</title>
    <style>
        <?php include "css/style.css" ?>
    </style>
    <?php require_once('logic.php');?>
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
    </div>
</body>