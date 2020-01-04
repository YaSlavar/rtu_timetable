<?php
global $semestr_week_count;
global $group;
global $email_to_feedback;
?>

<div class="container">
    <div class="row">
        <div class="col-xl-5 col-lg-5 col-md-6 col-sm-9 col-9 start_week">
            <?
            $date = date("d.m.Y", strtotime($start_day));
            $week = (date("W", strtotime($date))) - $delta;

            foreach ($semestr_week_count as $timetable_type => $count_week) {
                $day_count = $count_week * 7;

                for ($day_num = 1; $day_num < $day_count + 1; $day_num++) {

                    if ($week % 2 == 0) {
                        $n_week = 2;
                    } else {
                        $n_week = 1;
                    }
                    $day = strftime("%w", strtotime($date));

                    $today_date = date('%d.%m.%Y');
                    if ($day == 1 and $date != $today_date) { ?>
                        <div id="border_num_week">
                            <div id="num_week"><? echo($week . ' неделя'); ?></div>
                        </div>
                    <? } ?>
                    <div id="card">
                        <a name="<? echo(strftime("%d.%m.%Y", strtotime($date))); ?>"></a>
                        <div id="date">
                            <?
                            echo get_today_name($date);
                            echo get_day_name($day);
                            echo(" (" . strftime("%d.%m.%Y", strtotime($date)) . ")");
                            ?>
                        </div>
                        <?
                        $item = get_day_info($db, $group, $day, $n_week, $week, $date, $timetable_type);

                        ?>
                        <div id="day">
                            <? if ($item == array()) {
                                echo('<div id="sun">Нет занятий</div>');
                            } else {
                                include('views/components/day_table.php');
                            } ?>
                        </div>
                    </div>

                    <?
                    $date = date("d.m.Y", strtotime($date) + 86400);
                    if ($day_num % 7 == 0) {
                        $week++;
                    }
                }
            }
            ?>
        </div>
        <section class="col-xl-5 col-lg-5 col-md-5 col-sm-9 col-10">
            <div class="donat_container">
                <div class="calendar day justify-content-center"></div>
                <div class="feedback d-flex justify-content-center">
                    Для обратной связи: <a href="<? echo($email_to_feedback); ?>"><? echo($email_to_feedback); ?></a>
                </div>
            </div>
        </section>
    </div>
</div>
