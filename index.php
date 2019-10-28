<? include('config.php'); ?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="theme-color" content="#283593">

    <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <meta name="yandex-verification" content="6990c8ac978b974f"/>

    <script type="text/javascript" src="lib/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="lib/jquery-ui.min.js"></script>
    <script type="text/javascript" src="lib/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="lib/select2/select2.full.min.js"></script>
    <script type="text/javascript" src="lib/select2/ru.js"></script>
    <script type="text/javascript" src="js/main.js"></script>

    <title><? echo($title); ?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="lib/select2/select2.css?version=2">
    <link rel="stylesheet" href="css/main.css?version=25">

</head>
<body>

<?

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
    while ($res = $select->fetchArray()) {
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

?>

<header class="header fixed-top">
    <div class="container">
        <div class="row">
            <div id="logo" class="col-lg">
                <? echo($title); ?>
            </div>
        </div>
    </div>
</header>

<section class="cards">
    <? if ($_GET['group'] == '') { ?>
        <div class="container">
            <div class="row start">
                <section class="col-lg-5 col-md-7 col-sm-9">
                    <div class="donat_container">
                        <div class="feedback d-flex justify-content-center">
                            Для обратной связи: <a href="mailto:ya.slavar@yandex.ru">ya.slavar@yandex.ru</a>
                        </div>
                    </div>
                </section>
                <div class="col-lg-6 col-md-8 col-sm-10 none-group d-flex justify-content-center align-items-center">
                    <div>
                        Добро пожаловать на сайт!
                        <br>
                        Пожалуйста, выберите Вашу учебную группу.
                    </div>
                </div>
            </div>
        </div>
        <?
    } else {
        $gr = $_GET['group'];

        $max_para_count = get_max_para_count(get_all_info($db, $gr));

        if ($_GET['view'] !== 'table') {
            $today = date('Y-m-d');
            ?>
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-md-7 col-sm-9 col-7s start_week">
                        <div id="border_num_week">
                            <div id="num_week"><? echo((date("W", strtotime($today)) - $delta) . ' неделя'); ?></div>
                        </div>
                        <?
                        for ($days = 1; $days < 25; $days++) {
                            $week = (date("W", strtotime($today))) - $delta;
                            if ($week % 2 == 0) {
                                $n_week = 2;
                            } else {
                                $n_week = 1;
                            }
                            $day = strftime("%w", strtotime($today));

                            $today_date = date('Y-m-d');
                            if ($day == 1 and $today != $today_date) { ?>
                                <div id="border_num_week">
                                    <div id="num_week"><? echo((date("W", strtotime($today)) - $delta) . ' неделя'); ?></div>
                                </div>
                            <? } ?>

                            <div id="card">
                                <div id="date">
                                    <?
                                    echo get_today_name($days);
                                    echo get_day_name($day);
                                    echo("(" . strftime("%d.%m.%Y", strtotime($today)) . ")");
                                    ?>
                                </div>
                                <?
                                $item = get_day_info($db, $gr, $day, $n_week, $week);
                                ?>
                                <div id="day">
                                    <? if ($item == array()) {
                                        echo('<div id="sun">Выходной</div>');
                                    } else {
                                        include('day_table.php');
                                    } ?>
                                </div>
                            </div>

                            <? $today = date("Y-m-d", strtotime($today) + 86400);
                        }
                        ?>
                    </div>
                    <section class="col-lg-5 col-md-7 col-sm-9">
                        <div class="donat_container">
                            <div class="feedback d-flex justify-content-center">
                                Для обратной связи: <a href="mailto:ya.slavar@yandex.ru">ya.slavar@yandex.ru</a>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        <? } else { ?>
            <? $list_info = get_all_info($db, $gr); ?>
            <div class="cards_table container-fluid">
                <div class="work_zone">
                    <? $today = date('Y-m-d'); ?>

                    <? for ($days = 1; $days < 8; $days++) { ?>
                        <div class="one_day">
                            <?
                            $temp_date = $start_day;
                            for ($week = 1; $week < $semestr_week_count + 1; $week++) {
                                if ($week % 2 == 0) {
                                    $n_week = 2;
                                } else {
                                    $n_week = 1;
                                }

                                $item = array();
                                foreach ($list_info[$days][$n_week] as $res) {
                                    $result_array = result_fetch_array($res, $week);
                                    if ($result_array["show"] != "") {
                                        $item[$res['para']] = $result_array;
                                    }
                                }
                                ?>

                                <div id="card">
                                    <div id="date">
                                        <? if ($days == 1) { ?>
                                            <div class="num_week">
                                                <? echo($week . " неделя"); ?>
                                            </div>
                                        <? } ?>
                                        <?
                                        echo get_day_name($days);
                                        echo("(");
                                        echo(strftime("%d.%m.%Y", strtotime($temp_date)));
                                        echo(")");
                                        ?>
                                    </div>
                                    <? if ($temp_date == $today) {
                                        echo '<a name="today"></a>';
                                    } ?>

                                    <div id="day"
                                        <? if ($temp_date == $today) {
                                            echo 'style="box-shadow: 0px 1px 15px 0px #ff1700;"';
                                        } ?>>
                                        <? if ($item == array()) {
                                            echo('<div id="sun">Выходной</div>');
                                        } else {
                                            include('day_table.php');
                                        } ?>
                                    </div>
                                </div>

                                <? $temp_date = date("Y-m-d", strtotime($temp_date) + 604800); ?>
                            <? } ?>
                        </div>
                        <?
                        $start_day = date("Y-m-d", strtotime($start_day) + 86400);
                    }
                    ?>
                </div>
            </div>
        <? }
    } ?>
</section>

<? if ($_GET['view'] === 'table') { ?>
    <div class="zoom_bottoms fix-middle-right padding-right-20">
        <button id="zoom_plus" class="btn-light light_bottom round_bottom-50">
            <svg class="btn_icon_blue" xmlns="http://www.w3.org/2000/svg" fill="white" width="30" height="30"
                 viewBox="0 0 24 24">
                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" fill="#3f51b5"></path>
                <path d="M0 0h24v24H0z" fill="none"></path>
            </svg>
        </button>
        <button id="zoom_minus" class="btn-light light_bottom round_bottom-50">
            <svg class="btn_icon_blue" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 8 24 24">
                <path d="M6 19h12v2H6z" fill="#3f51b5"></path>
                <path fill="none" d="M0 0h24v24H0V0z"></path>
            </svg>
        </button>
        <button id="undo_zoom" class="btn-light light_bottom round_bottom-50">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path d="M12 6v3l4-4-4-4v3c-4.42 0-8 3.58-8 8 0 1.57.46 3.03 1.24 4.26L6.7 14.8c-.45-.83-.7-1.79-.7-2.8 0-3.31 2.69-6 6-6zm6.76 1.74L17.3 9.2c.44.84.7 1.79.7 2.8 0 3.31-2.69 6-6 6v-3l-4 4 4 4v-3c4.42 0 8-3.58 8-8 0-1.57-.46-3.03-1.24-4.26z"
                      fill="#3f51b5"></path>
                <path d="M0 0h24v24H0z" fill="none"></path>
            </svg>
        </button>
    </div>
<? } ?>

<footer class="bottoms fixed-bottom">
    <div class="container d-flex justify-content-end">
        <form action="" method="get" name="select_group">
            <? $group_list = $db->query('SELECT tbl_name FROM sqlite_master'); ?>
            <div id="bg">
                <div class="group_container">
                    <select name="group" title="" id="select" class="group_selector"
                            onchange="document.forms['select_group'].submit()">
                        <?
                        if (!isset($gr)) {
                            $gr = $etalon_group;
                        }
                        while ($row = $group_list->fetchArray()) {
                            $group_name = str_replace("_", "-", $row[0])

                            ?>
                            <option <? if ($group_name == $gr) {
                                echo("selected");
                            } ?> value="<? echo(urldecode($group_name)); ?>"><b><? echo($group_name); ?></b></option>
                        <? } ?>
                    </select>
                </div>
                <div id="bottom_gr">
                    <input type="submit" value="" id="group_submit">
                </div>
            </div>
        </form>

        <? if ($_GET['view'] !== 'table') { ?>
            <a href="?view=table&group=<? echo($gr); ?>#today">
                <div class="bottom">
                    <svg fill="white" height="40" viewBox="0 0 24 24" width="40" class="svg"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"></path>
                    </svg>
                </div>
            </a>
        <? } else { ?>
            <a href="index.php?group=<? echo($gr); ?>">
                <div class="bottom">
                    <svg fill="white" height="40" viewBox="0 0 24 24" width="40" class="svg"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 14h4v-4H4v4zm0 5h4v-4H4v4zM4 9h4V5H4v4zm5 5h12v-4H9v4zm0 5h12v-4H9v4zM9 5v4h12V5H9z"></path>
                        <path d="M0 0h24v24H0z" fill="none"></path>
                    </svg>
                </div>
            </a>
        <? } ?>
    </div>
</footer>


<script>
    $(document).ready(function () {

        let work_zone = $('.work_zone');

        work_zone.draggable();

        $("#select").select2({
            'width': '185px',
            'language': 'ru'
        });

        $('#zoom_plus').click(function () {
            zoom_element(work_zone, 1, 0.1, 200);
        });

        $('#zoom_minus').click(function () {
            zoom_element(work_zone, -1, 0.1, 200);
        });

        $('#undo_zoom').click(function () {
            undo_zoom(work_zone);
        });

    });
</script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript"> (function (m, e, t, r, i, k, a) {
        m[i] = m[i] || function () {
            (m[i].a = m[i].a || []).push(arguments)
        };
        m[i].l = 1 * new Date();
        k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
    })(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
    ym(50639506, "init", {clickmap: true, trackLinks: true, accurateTrackBounce: true, trackHash: true}); </script>
<noscript>
    <div><img src="https://mc.yandex.ru/watch/50639506" style="position:absolute; left:-9999px;" alt=""/></div>
</noscript> <!-- /Yandex.Metrika counter -->

</body>
</html>