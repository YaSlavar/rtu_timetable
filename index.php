<?
include "config.php";
include "functions.php";
?>
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

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.css?version=<? echo $version; ?>">
    <link rel="stylesheet" href="lib/select2/select2.css?version=<? echo $version; ?>">
    <link rel="stylesheet" href="lib/bootstrap-datepicker/css/bootstrap-datepicker.css?version=<? echo $version; ?>">
    <link rel="stylesheet" href="css/main.css?version=<? echo $version; ?>">

    <script type="text/javascript" src="lib/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="lib/jquery-ui.min.js"></script>
    <script type="text/javascript" src="lib/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="lib/select2/select2.full.min.js?version=<? echo $version; ?>"></script>
    <script type="text/javascript" src="lib/select2/ru.js?version=<? echo $version; ?>"></script>
    <script type="text/javascript" src="js/messageBoxController.js?version=<? echo $version; ?>"></script>
    <script type="text/javascript"
            src="lib/bootstrap-datepicker/js/bootstrap-datepicker.js?version=<? echo $version; ?>"></script>
    <script type="text/javascript"
            src="lib/bootstrap-datepicker/locales/bootstrap-datepicker.ru.min.js?version=<? echo $version; ?>"></script>
    <script type="text/javascript" src="lib/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/main.js?version=<? echo $version; ?>"></script>

    <title><? echo($title); ?></title>

</head>
<body>

<header class="header fixed-top">
    <div class="container">
        <div class="row align-items-center">
            <a name="top"></a>
            <div id="logo" class="col-10">
                <a class="logo" href="#<? echo(date("d.m.Y")); ?>"><? echo($title); ?></a>
            </div>
            <div id="calendar_button" class="col-2 col-sm-auto justify-content-end">
                <svg class="calendar-icon" xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                     viewBox="-7 0 40 25">
                    <path d="M0 0h24v24H0z" fill="none"/>
                    <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"
                          fill="white"/>
                </svg>
            </div>
            <div class="top_calendar"></div>
        </div>
    </div>
</header>

<section class="cards">
    <? if (empty($_GET['group']) and empty($_COOKIE['group'])) {
        include("views/main.php");
    } else {

        if (!empty($_GET['group']) and !empty($_COOKIE['group'])) {
            if ($_GET['group'] !== $_COOKIE['group']) {
                $group = $_GET['group'];
                setcookie('group', urlencode($_GET['group']), time() + 60 * 60 * 24 * 30 * 12, '/');
            } else {
                $group = $_GET['group'];
            }
        } elseif (empty($_GET['group']) and !empty($_COOKIE['group'])) {
            $group = urldecode($_COOKIE['group']);
            $_GET['group'] = $group;
        } else {
            setcookie('group', urlencode($_GET['group']), time() + 60 * 60 * 24 * 30 * 12, '/');
            $group = $_GET['group'];
        }


        $all_info = get_all_info($db, $group);

        if ($all_info) {
            $max_para_count = get_max_para_count($all_info);
            $postfix = get_group_postfix($group);
            $group_type = get_group_type($postfix);
            $course_num = get_course_num($group);
            $semestr_week_count = get_semester_week_count($group_type, $course_num, $week_count_array, $semester_count);


            if ($_GET['view'] === 'table') {
                include("views/table_view.php");
            } else {
                include("views/list_view.php");
            }

        } else {
            include("views/main.php");
        }

    } ?>

</section>

<? if ($_GET['view'] === 'table') {
    include("views/components/zoom_buttons.php");
} ?>

<footer class="bottoms fixed-bottom">

    <?
    include("views/components/notification_tail.php");
    ?>

    <div class="container d-flex justify-content-end">
        <form action="" method="get" name="select_group">
            <? $group_list = get_group_list($db); ?>
            <div id="bg">
                <div class="group_container">
                    <select name="group" title="" id="select" class="group_selector"
                            onchange="document.forms['select_group'].submit()">
                        <?
                        if (!isset($group)) {
                            $group = $etalon_group;
                        }
                        while ($row = $group_list->fetchArray()) {
                            $group_name = str_replace("_", "-", $row[0]);
                            ?>
                            <option <? if ($group_name == $group) {
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
            <a href="?view=table&group=<? echo($group); ?>#today">
                <div class="bottom">
                    <svg fill="white" height="40" viewBox="0 0 24 24" width="40" class="svg"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"></path>
                    </svg>
                </div>
            </a>
        <? } else { ?>
            <a href="index.php?group=<? echo($group); ?>">
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

        window.onload = function () {
            location.href = "#top";
            location.href = "#" + formatDate(new Date);
        };

        $('#calendar_button').click(function () {
            $('.top_calendar').toggle();
        });

        $('.calendar').datepicker({
            format: "dd.mm.yyyy",
            language: "ru",
            todayHighlight: true
        }).on('changeDate', function (e) {
            location.href = "#" + formatDate(e.date);
        });

        $('.top_calendar').datepicker({
            format: "dd.mm.yyyy",
            language: "ru",
            todayHighlight: true
        }).on('changeDate', function (e) {
            $('.top_calendar').toggle();
            location.href = "#" + formatDate(e.date);
        });

        $("#select").select2({
            'width': '185px',
            'language': 'ru'
        });

        let work_zone = $('.work_zone');
        work_zone.draggable();

        $('#zoom_plus').click(function () {
            zoom_element(work_zone, 1, 0.1, 200);
        });

        $('#zoom_minus').click(function () {
            zoom_element(work_zone, -1, 0.1, 200);
        });

        $('#undo_zoom').click(function () {
            undo_zoom(work_zone);
        });

        message_box_control(<?echo($notification);?>);

    });
</script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (m, e, t, r, i, k, a) {
        m[i] = m[i] || function () {
            (m[i].a = m[i].a || []).push(arguments)
        };
        m[i].l = 1 * new Date();
        k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
    })
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(50639506, "init", {
        clickmap: true,
        trackLinks: true,
        accurateTrackBounce: true,
        webvisor: true,
        trackHash: true
    });
</script>
<noscript>
    <div><img src="https://mc.yandex.ru/watch/50639506" style="position:absolute; left:-9999px;" alt=""/></div>
</noscript>
<!-- /Yandex.Metrika counter -->

</body>
</html>