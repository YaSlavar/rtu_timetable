<?php
/**
 * Created by PhpStorm.
 * User: yasla
 * Date: 01.09.2019
 * Time: 21:39
 */

$db = new SQLite3("table.db");

// Глобальные переменные

$title = "Расписание РТУ МИРЭА"; # Заголовок сайта

$start_day = "02.09.2019"; # Первый день семестра День должен быть понедельником!
$delta = 35; # Поправка на количество недель
$etalon_group = "БНБО-02-16"; # Группа отображаемая по умолчанию

$semestr_week_count = 16;

function get_today_name($days)
{
    switch ($days) {
        case 1:
            return "Сегодня:  ";
            break;
        case 2:
            return "Завтра:  ";
            break;
        case 3:
            return "Послезавтра:  ";
            break;
    }
}

function get_day_name($day)
{
    switch ($day) {
        case 1:
            return "Понедельник";
            break;
        case 2:
            return "Вторник";
            break;
        case 3:
            return "Среда";
            break;
        case 4:
            return "Четверг";
            break;
        case 5:
            return "Пятница";
            break;
        case 6:
            return "Суббота";
            break;
        case 0:
        case 7:
            return "Воскресенье";
            break;
    }
}

function get_para_time($para)
{
    switch ($para) {
        case 1:
            return "9:00";
            break;
        case 2:
            return "10:40";
            break;
        case 3:
            return "13:10";
            break;
        case 4:
            return "14:50";
            break;
        case 5:
            return "16:30";
            break;
        case 6:
            return "18:10";
            break;
        case 7:
            return "19:50";
            break;
        case 8:
            return "20:10";
            break;
    }
}
