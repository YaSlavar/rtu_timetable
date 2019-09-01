<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="cleartype" content="on">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0">
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
    <link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <meta name="yandex-verification" content="6990c8ac978b974f" />
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" src="lib/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="lib/select2/select2.full.min.js"></script>
    <script type="text/javascript" src="lib/select2/ru.js"></script>
    <script type="text/javascript" > (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter50639506 = new Ya.Metrika2({ id:50639506, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true, trackHash:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/tag.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks2"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/50639506" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
    <title>Расписание Занятий</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.css" >
    <link rel="stylesheet" href="lib/select2/select2.css">
    <link rel="stylesheet" href="css/main.css?version=20" >

</head>
<body>

    <?
    include('config.php');

    function get_day_info($db, $gr, $day, $n_week, $week){
        $to_sql_group_name = str_replace("-","_", $gr);
        $select = $db ->query("SELECT * FROM $to_sql_group_name WHERE day = $day AND week = $n_week");
        $item = '';
        while ($res = $select->fetchArray()) {
            if ($res['exception'] != '') {
                $res['exception'] = str_replace("'", "", $res['exception']);
                $res['exception'] = explode(", ", $res['exception']);
            }
            if ($res['include'] != '') {
                $res['include'] = str_replace("'", "", $res['include']);
                $res['include'] = explode(", ", $res['include']);
            }
            if ($res["include"] == "" and $res["exception"] == ""){
                $res["show"] = True;
            }
            if(is_array($res["include"])) {
                if (in_array((string)$week, $res["include"], true)) {
                    $res["show"] = True;
                }
            }
            if(is_array($res["exception"])){

                if(in_array((string)$week, $res["exception"], true)){
                    $res["show"] = False;
                }else{
                    $res["show"] = True;
                }
            }
            if ($res["show"] != "") {
                $item[$res['para']] = $res;
            }
        }
        return $item;
    }

    function get_today_name($days){
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

    function get_day_name($day){
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

    function get_para_time($para){
        switch ($para) {
            case 1:
                return "9:00";
                break;
            case 2:
                return "10:40";
                break;
            case 3:
                return "13:00";
                break;
            case 4:
                return "14:40";
                break;
            case 5:
                return "16:20";
                break;
            case 6:
                return "18:00";
                break;
        }
    }
    ?>

<header class="header fixed-top">
    <div class="container">
       <div class="row">
           <div id="logo" class="col-lg">
               Расписание занятий
           </div>
       </div>
    </div>
</header>

<section class="cards">
    <?
    if($_GET['group'] == ''){

    ?>
    <div class="container">
        <div class="row start">
<!--            <section class="col-lg-5 col-md-7 col-sm-9">-->
<!--                <div class="donat_container">-->
<!--                    <div class="donat">-->
<!--                        <div class="donat_lable"> На оплату хостинга</div>-->
<!--                        <form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml">-->
<!--                            <input type="hidden" name="receiver" value="410012002180482">-->
<!--                            <input type="hidden" name="formcomment" value="Расписание Занятий">-->
<!--                            <input type="hidden" name="short-dest" value="Расписание Занятий">-->
<!--                            <input type="hidden" name="quickpay-form" value="donate">-->
<!--                            <input type="hidden" name="targets" value="Пожертвование сайту Time-RTU.ru">-->
<!--                            <input class="donat_input" type="number" name="sum" value="" data-type="number"-->
<!--                                   placeholder="10 руб.">-->
<!--                            <textarea class="donat_input" type="text" name="comment" value=""-->
<!--                                      placeholder="Можете выразить пожелания или предложения!"></textarea>-->
<!--                            <input type="hidden" name="need-fio" value="false">-->
<!--                            <input type="hidden" name="need-email" value="false">-->
<!--                            <input type="hidden" name="need-phone" value="false">-->
<!--                            <input type="hidden" name="need-address" value="false">-->
<!--                            <div class="d-flex flex-column">-->
<!--                                <label><input class="radio_bottom" type="radio" name="paymentType" value="PC">Яндекс.Деньгами</label>-->
<!--                                <label><input class="radio_bottom" type="radio" name="paymentType" value="AC">Банковской-->
<!--                                    картой</label>-->
<!--                                <label><input class="radio_bottom" type="radio" name="paymentType" value="MC">C-->
<!--                                    баланса мобильного телефона</label>-->
<!--                            </div>-->
<!--                            <input class="donats_submit" type="submit" value="Перевести">-->
<!--                        </form>-->
<!--                    </div>-->
<!--                    <div class="feedback d-flex justify-content-center">-->
<!--                        Для обратной связи:  <a href="mailto:ya.slavar@yandex.ru">ya.slavar@yandex.ru</a>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </section>-->
            <div class="col-lg-6 col-md-8 col-sm-10 none-group d-flex justify-content-center align-items-center">
                <div>
                    Добро пожаловать на сайт!

                    Пожалуйста, выберите Вашу учебную группу:
                </div>
            </div>
        </div>
    </div>
    <?
    }else {
        $gr = $_GET['group'];

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
                            ?>

                            <?
                            $today_date = date('Y-m-d');
                            if ($day == 1 and $today != $today_date) {
                                ?>
                                <div id="border_num_week">
                                    <div id="num_week"><? echo((date("W", strtotime($today)) - $delta) . ' неделя'); ?></div>
                                </div>
                            <?
                            } ?>

                            <div id="card">
                                <div id="date">
                                    <?
                                    echo get_today_name($days);
                                    echo get_day_name($day);
                                    echo("(");
                                    echo(strftime("%d.%m.%Y", strtotime($today)));
                                    echo(")");
                                    ?>
                                </div>
                                <?
                                $item = get_day_info($db, $gr, $day, $n_week, $week);
                                ?>
                                <div id="day">
                                    <? if ($item == '') {
                                        echo('<div id="sun">Выходной</div>');
                                    } else {
                                        ?>
                                        <table cellspacing="0" id="maket">
                                            <? for ($para = 1; $para < 7; $para++) { ?>
                                                <tr>
                                                    <td id="time">
                                                        <? echo get_para_time($para); ?>
                                                    </td>
                                                    <td id="lesson">
                                                        <div id="dist">
                                                            <?
                                                            if ($item[$para]['except'] != 'False' and $item[$para]['include'] != 'False' or ($item[$para]['except'] == '' and $item[$para]['include'] == '')) {
                                                                echo($item[$para]['name']);
                                                            }
                                                            if ($item[$para]['name'] == '') {
                                                                echo "<div class='none'>- - - - - - - - - - - - - - - - - - - - - - -</div>";
                                                            }
                                                            ?>
                                                        </div>
                                                        <div id="aud">
                                                            <?
                                                            if ($item[$para]['except'] != 'False' and $item[$para]['include'] != 'False' or ($item[$para]['except'] == '' and $item[$para]['include'] == '')) {
                                                                if ($item[$para]['name'] != '') {
                                                                    echo('ауд.' . $item[$para]['room']);
                                                                    if ($item[$para]['type'] != '') {
                                                                        echo('  ( ' . $item[$para]['type'] . ' )');
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                        <div id="prepod">
                                                            <?
                                                            if ($item[$para]['except'] != 'False' and $item[$para]['include'] != 'False' or ($item[$para]['except'] == '' and $item[$para]['include'] == '')) {
                                                                echo($item[$para]['prepod']);
                                                            }
                                                            ?>
                                                        </div>
                                                        <? if ($para < 6 and $item['dist'] == "") { ?>
                                                            <div id="border"></div>
                                                        <? } ?>
                                                    </td>
                                                </tr>
                                            <? } ?>
                                        </table>
                                    <? } ?>
                                </div>
                            </div>

                            <? $today = date("Y-m-d", strtotime($today) + 86400);
                        }
                        ?>
                    </div>

                    <section class="col-lg-5 col-md-7 col-sm-9">
                        <div class="donat_container">
<!--                            <div class="donat">-->
<!--                                <div class="donat_lable"> На оплату хостинга</div>-->
<!--                                <form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml">-->
<!--                                    <input type="hidden" name="receiver" value="410012002180482">-->
<!--                                    <input type="hidden" name="formcomment" value="Расписание Занятий">-->
<!--                                    <input type="hidden" name="short-dest" value="Расписание Занятий">-->
<!--                                    <input type="hidden" name="quickpay-form" value="donate">-->
<!--                                    <input type="hidden" name="targets" value="Пожертвование сайту Time-RTU.ru">-->
<!--                                    <input class="donat_input" type="number" name="sum" value="" data-type="number"-->
<!--                                           placeholder="10 руб.">-->
<!--                                    <textarea class="donat_input" type="text" name="comment" value=""-->
<!--                                              placeholder="Можете выразить пожелания или предложения!"></textarea>-->
<!--                                    <input type="hidden" name="need-fio" value="false">-->
<!--                                    <input type="hidden" name="need-email" value="false">-->
<!--                                    <input type="hidden" name="need-phone" value="false">-->
<!--                                    <input type="hidden" name="need-address" value="false">-->
<!--                                    <div class="d-flex flex-column">-->
<!--                                        <label><input class="radio_bottom" type="radio" name="paymentType" value="PC">Яндекс.Деньгами</label>-->
<!--                                        <label><input class="radio_bottom" type="radio" name="paymentType" value="AC">Банковской-->
<!--                                            картой</label>-->
<!--                                        <label><input class="radio_bottom" type="radio" name="paymentType" value="MC">C-->
<!--                                            баланса мобильного телефона</label>-->
<!--                                    </div>-->
<!--                                    <input class="donats_submit" type="submit" value="Перевести">-->
<!--                                </form>-->
<!--                            </div>-->
                            <div class="feedback d-flex justify-content-center">
                                Для обратной связи:  <a href="mailto:ya.slavar@yandex.ru">ya.slavar@yandex.ru</a>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        <?} else {?>
            <div class="cards_table container-fluid">
                <div class="">
                    <? $today = date('Y-m-d'); ?>
                    <? for ($days = 1; $days < 8; $days++) { ?>
                        <div class="one_day">
                            <?
                            $temp_date = $start_day;
                            for ($week = 1; $week < 18 + 1; $week++) {
                                if ($week % 2 == 0) {
                                    $n_week = 2;
                                } else {
                                    $n_week = 1;
                                }
                                $item = get_day_info($db, $gr, $days, $n_week, $week);
                                ?>

                                <div id="card">

                                    <div id="date">
                                        <?if($days == 1){?>
                                            <div class="num_week">
                                                <?echo($week." неделя");?>
                                            </div>
                                        <?}?>
                                        <?
                                        echo get_day_name($days);
                                        echo("(");
                                        echo(strftime("%d.%m.%Y", strtotime($temp_date)));
                                        echo(")");
                                        ?>
                                    </div>
                                    <? if ($temp_date == $today) {
                                        echo '<a name="today"></a>';
                                    }
                                    ?>
                                    <div id="day" <? if ($temp_date == $today) {
                                        echo 'style="box-shadow: 0px 1px 15px 0px #ff1700;"';
                                    } ?>>
                                        <? if ($item == '') {
                                            echo('<div id="sun">Выходной</div>');
                                        } else {
                                            ?>
                                            <table cellspacing="0" id="maket">
                                                <? for ($para = 1; $para < 7; $para++) { ?>
                                                    <tr>
                                                        <td id="time">
                                                            <? echo get_para_time($para); ?>
                                                        </td>
                                                        <td id="lesson">
                                                            <div id="dist">
                                                                <?
                                                                if ($item[$para]['except'] != 'False' and $item[$para]['include'] != 'False' or ($item[$para]['except'] == '' and $item[$para]['include'] == '')) {
                                                                    echo($item[$para]['name']);
                                                                }
                                                                if ($item[$para]['name'] == '') {
                                                                    echo "<div class='none'>- - - - - - - - - - - - - - - - - - - - - -</div>";
                                                                }
                                                                ?>
                                                            </div>
                                                            <div id="aud">
                                                                <?
                                                                if ($item[$para]['except'] != 'False' and $item[$para]['include'] != 'False' or ($item[$para]['except'] == '' and $item[$para]['include'] == '')) {
                                                                    if ($item[$para]['name'] != '') {
                                                                        echo('ауд.' . $item[$para]['room']);
                                                                        if ($item[$para]['type'] != '') {
                                                                            echo('  ( ' . $item[$para]['type'] . ' )');
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                            <div id="prepod">
                                                                <?
                                                                if ($item[$para]['except'] != 'False' and $item[$para]['include'] != 'False' or ($item[$para]['except'] == '' and $item[$para]['include'] == '')) {
                                                                    echo($item[$para]['prepod']);
                                                                }
                                                                ?>
                                                            </div>
                                                            <? if ($para < 6 and $item['dist'] == "") { ?>
                                                                <div id="border"></div>
                                                            <? } ?>
                                                        </td>
                                                    </tr>
                                                <? } ?>
                                            </table>
                                        <? } ?>
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
    <?}}?>
</section>

<footer class="bottoms fixed-bottom">
    <div class="container d-flex justify-content-end">
    <form action="" method="get" name="select_group">
        <?$group_list = $db -> query('SELECT tbl_name FROM sqlite_master'); ?>
        <div id="bg">
            <div class="group_container">
                <select name="group" title="" id="select" class="group_selector" onchange="document.forms['select_group'].submit()">
                <?
                    if (!isset($gr)){$gr = $etalon_group;}
                    while ($row = $group_list->fetchArray()) {
                    $group_name = str_replace("_","-", $row[0])

                ?>
                <option <?if ($group_name == $gr){echo("selected");}?> value="<?echo(urldecode($group_name));?>"><b><?echo($group_name);?></b></option>
                <?}?>
                </select>
            </div>
            <div id="bottom_gr">
                <input type="submit" value="" id="group_submit">
            </div>
        </div>
    </form>

    <?if($_GET['view'] !== 'table'){?>
    <a href="?view=table&group=<?echo($gr);?>#today">
        <div class="bottom">
            <svg fill="white" height="40" viewBox="0 0 24 24" width="40" class="svg" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 0h24v24H0z" fill="none"></path>
                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"></path>
            </svg>
        </div>
    </a>
    <?}else{?>
    <a href="index.php?group=<?echo($gr);?>">
        <div class="bottom">
            <svg fill="white" height="40" viewBox="0 0 24 24" width="40" class="svg" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 14h4v-4H4v4zm0 5h4v-4H4v4zM4 9h4V5H4v4zm5 5h12v-4H9v4zm0 5h12v-4H9v4zM9 5v4h12V5H9z"></path>
                <path d="M0 0h24v24H0z" fill="none"></path>
        </svg>
        </div>
    </a>
    <?}?>
    </div>
</footer>

    <script>
        $(document).ready(function() {
            $("#select").select2({
                'width': '185px',
                'language': 'ru'
            });
        });
    </script>

</body>
</html>