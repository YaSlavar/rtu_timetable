<?php
include "../config.php";
include "../functions.php";

$db = new SQLite3("../table.db");

// Получение данных из тела запроса
function getFormData($method)
{

    // GET или POST: данные возвращаем как есть
    if ($method === 'GET') return $_GET;
    if ($method === 'POST') return $_POST;

    // PUT, PATCH или DELETE
    $data = array();
    $exploded = explode('&', file_get_contents('php://input'));

    foreach ($exploded as $pair) {
        $item = explode('=', $pair);
        if (count($item) == 2) {
            $data[urldecode($item[0])] = urldecode($item[1]);
        }
    }

    return $data;
}

// Определяем метод запроса
$method = $_SERVER['REQUEST_METHOD'];

// Получаем данные из тела запроса
$formData = getFormData($method);


// Разбираем url
$url = (isset($_GET['q'])) ? $_GET['q'] : '';
$url = rtrim($url, '/');
$urls = explode('/', $url);

// Определяем роутер и url data
$router = $urls[0];
$urlData = array_slice($urls, 1);

// Подключаем файл-роутер и запускаем главную функцию
if ($router != '') {
    include_once 'routers/' . $router . '.php';
    route($method, $urlData, $formData, $db);
} else {
    // Возвращаем ошибку
    header('HTTP/1.0 400 Bad Request');
    echo json_encode(array(
        'GET /group/{номер группы}' => 'Получение расписнаия группы на семестр',
        'GET /group/{номер группы}/{номер_дня_недели 1-6}/{четность_недели 1-2}/{номер_недели 1-16}' => 'Получение расписнаия группы на определенный день',
        'GET /group/{номер группы}/{дата "dd.mm.YYYY"}' => 'Получение расписнаия группы на определенную дату'
    ), JSON_UNESCAPED_UNICODE);
}

