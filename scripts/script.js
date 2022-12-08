//Кнопка next
$('#next').on('click', function (e){
    e.preventDefault();

    // Получаю элемент с id get Pages, то есть форму
    var form = $('#getPages');

    // Меняю value у input'а с id #page на value + 1, То есть перелистываю страницу вперед
    $('#page')[0].value = parseInt($('#page')[0].value) + 1;

    $.ajax({
        type: "GET",
        url: "/api/feedbacks/",
        dataType: "json",
        data: form.serialize(), // Получаю данные с формы
        success: function(data)
        {
            document.getElementById("tbody").innerHTML = "";

            for (let i = 0; i < data.length; i++) {
                document.getElementById("tbody").innerHTML +=
                    "<tr>" +
                        "<td>" + data[i].id + "</td>" +
                        "<td>" + data[i].guest_id + "</td>" +
                        "<td>" + data[i].rating + "</td>" +
                        "<td>" + data[i].review + "</td>" +
                        "<td>" + data[i].date + "</td>" +
                    "</tr>";
            }
        },
        error: function() {
            $('#page')[0].value = parseInt($('#page')[0].value) - 1;
        }
    });
})

//Кнопка previous
$('#previous').on('click', function (e){
    e.preventDefault();

    // Получаю элемент с id get Pages, то есть форму
    var form = $('#getPages');

    // Меняю value у input'а с id #page на value - 1, То есть перелистываю страницу назад
    $('#page')[0].value = parseInt($('#page')[0].value) - 1;

    $.ajax({
        type: "GET",
        url: "/api/feedbacks/",
        dataType: "json",
        data: form.serialize(), // Получаю данные с формы
        success: function(data)
        {
            document.getElementById("tbody").innerHTML = "";

            for (let i = 0; i < data.length; i++) {
                document.getElementById("tbody").innerHTML +=
                    "<tr>" +
                    "<td>" + data[i].id + "</td>" +
                    "<td>" + data[i].guest_id + "</td>" +
                    "<td>" + data[i].rating + "</td>" +
                    "<td>" + data[i].review + "</td>" +
                    "<td>" + data[i].date + "</td>" +
                    "</tr>";
            }
        },
        error: function () {
            $('#page')[0].value = parseInt($('#page')[0].value) + 1;
        }
    });
})

//Кнопка добавления отзыва
$('#addPost').on('submit', function (e){
    e.preventDefault();

    //DOM элемент
    var form = $(this);

    $.ajax({
        type: "POST",
        url: "/api/adding",
        dataType: "json",
        data: form.serialize(), // Получаю данные с формы
        success: function(data)
        {
            alert("Запись добавлена, проверяйте!");
        }
    });
})



