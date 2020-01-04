<?php
global $semestr_week_count;
global $group;
?>

<div class="cards_table container-fluid">
    <div class="work_zone">
        <? $today = date("d.m.Y"); ?>

        <? for ($days = 1; $days < 8; $days++) { ?>
            <div class="one_day">
                <?
                $temp_date = $start_day;
                $today_week = 1;

                foreach ($semestr_week_count as $timetable_type => $count_week) {

                    for ($week = 1; $week < $count_week + 1; $week++) {

                        if ($today_week % 2 == 0) {
                            $n_week = 2;
                        } else {
                            $n_week = 1;
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

                        $item = get_day_info($db, $group, $days, $n_week, $today_week, $temp_date, $timetable_type);

                        ?>

                        <div id="card">
                            <div id="date">
                                <? if ($days == 1) { ?>
                                    <div class="num_week">
                                        <? echo($today_week . " неделя"); ?>
                                    </div>
                                <? }
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
                                    echo('<div id="sun">Нет занятий</div>');
                                } else {
                                    include('views/components/day_table.php');
                                } ?>
                            </div>
                        </div>

                        <?
                        $temp_date = date("d.m.Y", strtotime($temp_date) + 604800);
                        $today_week += 1;
                    }
                }
                ?>
            </div>
            <?
            $start_day = date("d.m.Y", strtotime($start_day) + 86400);
        }
        ?>
    </div>
</div>