<?php
include_once('config.php');

function get_group_postfix($group)
{
    return mb_substr($group, 2, 2);
}

function get_group_type($postfix)
{
    global $group_postfix;
    $group_type = NAN;
    foreach ($group_postfix as $gr_postfix_array_key => $gr_postfix_array) {

        if (in_array($postfix, $gr_postfix_array)) {
            $group_type = $gr_postfix_array_key;
        }
    }

    return $group_type;
}

function get_course_num($group)
{
    global $first_course_year;
    $year_num = (int)"20" . mb_substr($group, 8, 2);
    $course_num = $first_course_year - $year_num + 1;

    return $course_num;
}

function get_semester_week_count($group_type, $course_num, $week_count, $semester_count)
{
    $semester_week_count = array();

    foreach ($week_count as $timetable_type => $group_type_array) {
        $count_week_in_timetable_type = $group_type_array[$group_type][$semester_count][$course_num];
        $semester_week_count[$timetable_type] = $count_week_in_timetable_type;
    }

    return $semester_week_count;
}

function get_all_week_count($semester_week_count)
{
    $all_week_count = 0;

    foreach ($semester_week_count as $timetable_type => $week_count) {
        $all_week_count += $week_count;
    }

    return $all_week_count;
}


function get_today_name($date)
{
    $today = date("d.m.Y");
    $date = date("d.m.Y", strtotime($date));
    if ($date == $today) {
        return "Сегодня:  ";
    } elseif ($date == $today + 1) {
        return "Завтра:  ";
    } elseif ($date == $today + 2) {
        return "Послезавтра:  ";
    } else {
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
        default:
            return "";
    }
}

function get_group_list($db){
    return $db->query('SELECT tbl_name FROM sqlite_master ORDER BY tbl_name');
}

function get_day_info($db, $gr, $day, $n_week, $week, $date = null, $timetable_type = null)
{
    $item = array();
    $to_sql_group_name = str_replace("-", "_", $gr);

    if ($date !== null) {
        $date = substr($date, 0, 5);
    }

    switch ($timetable_type) {
        case "semester":
            $timetable_type = 0;
            break;
        case "zach":
            $timetable_type = 1;
            break;
        case "exam":
            $timetable_type = 2;
            break;
    }

    $select = $db->query("SELECT * FROM $to_sql_group_name WHERE (day = $day AND week = $n_week) OR date = '$date'");
    while ($res = $select->fetchArray()) {
        $result_array = result_fetch_array($res, $week);
        if ($result_array["show"] != "" and $result_array["doc_type"] == $timetable_type) {
            $item[$res['para']] = $result_array;
        }
    }

    return $item;
}


function get_day_info_on_date($db, $gr, $date)
{
    global $delta;

    $date = date("d.m.Y", strtotime($date));

    $week = (date("W", strtotime($date))) - $delta;
    if ($week % 2 == 0) {
        $n_week = 2;
    } else {
        $n_week = 1;
    }
    $day = strftime("%w", strtotime($date));

    return get_day_info($db, $gr, $day, $n_week, $week, $date);
}


function get_all_info($db, $gr)
{
    $item_list = array();
    $to_sql_group_name = str_replace("-", "_", $gr);
    $select = $db->query("SELECT * FROM $to_sql_group_name");


    if ($select !== false){
        for ($day = 1; $day < 8; $day++) {
            for ($week = 1; $week < 3; $week++) {
                $item_list[$day][$week] = array();
            }
        }

        while ($res = $select->fetchArray(SQLITE3_ASSOC)) {
            if (is_array($item_list[$res['day']][$res['week']])) {
                array_push($item_list[$res['day']][$res['week']], $res);
            }
        }
        return $item_list;
    }
    return false;
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