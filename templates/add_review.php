<!DOCTYPE html>
<html lang="ru-en">
<head>
    <meta charset="UTF-8">
    <title>Feedback</title>
</head>
<body>
<h1>Вы попали на страницу добавления отзывов</h1>
<div>
    <form method = "POST" action="/adding">
        <fieldset>
            <legend>Введите отзыв</legend>
            <p>
                <label for = "guest_id">Ваш id пользователя</label>
                <input type = "number" name = "guest_id" min = "1">
            </p>
            <p>
                <label for = "rating">Рейтинг заведения</label>
                <input type = "number" name = "rating" min = "1" max = "5">
            </p>
            <p>
                <label for = "review">Отзыв</label>
                <input type = "text" name = "review">
            </p>
            <p>
                <label for = "date">Дата</label>
                <input type = "date" name = "date">
            </p>
        </fieldset>
        <p>
            <input type="submit" value="Отправить">
        </p>
    </form>
</div>
</body>