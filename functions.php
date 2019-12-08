<?php

function get_today_name($date)
{
    $today = date("d.m.Y");
    $date = date("d.m.Y", strtotime($date));
    if ($date == $today) {
        return "Сегодня:  ";
    } elseif ($date == $today + 1){
        return "Завтра:  ";
    }elseif ($date == $today + 2){
        return "Послезавтра:  ";
    }else{
        return "";
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


function get_day_info($db, $gr, $day, $n_week, $week)
{
    $item = array();
    $to_sql_group_name = str_replace("-", "_", $gr);
    $select = $db->query("SELECT * FROM $to_sql_group_name WHERE day = $day AND week = $n_week");
    while ($res = $select->fetchArray()) {
        $result_array = result_fetch_array($res, $week);
        if ($result_array["show"] != "") {
            $item[$res['para']] = $result_array;
        }
    }
    return $item;
}


function get_all_info($db, $gr)
{
    $item_list = array();
    $to_sql_group_name = str_replace("-", "_", $gr);
    $select = $db->query("SELECT * FROM $to_sql_group_name");
    for ($day = 1; $day < 8; $day++) {
        for ($week = 1; $week < 3; $week++) {
            $item_list[$day][$week] = array();
        }
    }

    while ($res = $select->fetchArray(SQLITE3_ASSOC)) {
        array_push($item_list[$res['day']][$res['week']], $res);
    }

    return $item_list;
}

function get_max_para_count($item_list)
{
    $para_count = 6;
    foreach ($item_list as $day) {
        foreach ($day as $week) {
            foreach ($week as $para) {
                if ($para['para'] > $para_count) {
                    $para_count = $para['para'];
                }
            }
        }
    }
    return $para_count;
}

function result_fetch_array($res, $week)
{

    if ($res['exception'] != '') {
        $res['exception'] = str_replace("'", "", $res['exception']);
        $res['exception'] = explode(", ", $res['exception']);
    }
    if ($res['include'] != '') {
        $res['include'] = str_replace("'", "", $res['include']);
        $res['include'] = explode(", ", $res['include']);
    }
    if ($res["include"] == "" and $res["exception"] == "") {
        $res["show"] = True;
    }
    if (is_array($res["include"])) {
        if (in_array((string)$week, $res["include"], true)) {
            $res["show"] = True;
        }
    }
    if (is_array($res["exception"])) {

        if (in_array((string)$week, $res["exception"], true)) {
            $res["show"] = False;
        } else {
            $res["show"] = True;
        }
    }

    return $res;
}