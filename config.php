<?php
/**
 * Created by PhpStorm.
 * User: yasla
 * Date: 01.09.2019
 * Time: 21:39
 */

global $db;
$db = new SQLite3("table.db");

// Глобальные переменные

$title = "Расписание РТУ МИРЭА"; # Заголовок сайта

$start_day = "02.09.2019"; # Первый день семестра День должен быть понедельником!
$delta = 35; # Поправка на количество недель

$semestr_week_count = 16; # Количество недель в семестре
$semestr_day_count = $semestr_week_count * 7; # Количество дней в семестре

$etalon_group = "БНБО-02-16"; # Группа отображаемая по умолчанию


$version = 28; # Версия