<?php

// Роутер
function route($method, $urlData, $formData, $db)
{
    global $delta;

    $group = $urlData[0];

    // Получение расписнаия группы
    // GET /group/{имя группы}
    if ($method === 'GET' && count($urlData) === 1) {


        // Выводим ответ клиенту
        echo json_encode(get_all_info($db, $group), JSON_UNESCAPED_UNICODE);

        return;
    }


    // GET /group/{имя группы}/{номер_дня_недели}/{четность_недели}/{номер_недели}
    if ($method === 'GET' && count($urlData) === 4) {

        $day = $urlData[1];
        $n_week = $urlData[2];
        $week = $urlData[3];

        echo json_encode(get_day_info($db, $group, $day, $n_week, $week), JSON_UNESCAPED_UNICODE);

        return;
    }

    // GET /group/{номер группы}/{дата "dd.mm.YYYY"}
    if ($method === 'GET' && count($urlData) === 2) {

        $date = $urlData[1];

        $week = (date("W", strtotime($date))) - $delta;
        if ($week % 2 == 0) {
            $n_week = 2;
        } else {
            $n_week = 1;
        }
        $day = strftime("%w", strtotime($date));

        echo $delta;
        echo json_encode(get_day_info($db, $group, $day, $n_week, $week), JSON_UNESCAPED_UNICODE);

        return;
    }


    // Возвращаем ошибку
    header('HTTP/1.0 400 Bad Request');
    echo json_encode(array(
        'error' => 'Bad Request'
    ));

}
