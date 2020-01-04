<?php
global $email_to_feedback;
global $db;
?>

<div class="container">
    <div class="row start">

        <section class="col">
            <div class="none-group">
                Добро пожаловать на сайт!
                <br>
                Пожалуйста, выберите Ваш курс и учебную группу.
            </div>

            <div class="accordion" id="set_group">
                <div class="card">
                    <?
                    $group_list = get_group_list($db);

                    for ($course = 1; $course < 5 + 1; $course++) {
                        ?>
                        <div class="course card-header" id="heading_<?
                        echo($course); ?>">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse"
                                        data-target="#course_<?
                                        echo($course); ?>"
                                        aria-expanded="true" aria-controls="collapseOne">
                                    <? echo($course); ?> курс
                                </button>
                            </h5>
                        </div>
                        <div id="course_<? echo($course); ?>" class="collapse"
                             aria-labelledby="heading_<? echo($course); ?>" data-parent="#set_group">
                            <div class="card-body">
                                <?
                                foreach ($group_postfix as $type => $postfix) {

                                    if ($course > 2 && $type == "mag") {
                                        continue;
                                    } else {
                                        ?>
                                        <div class="card">
                                            <div class="card-header"><? echo($group_description[$type]); ?></div>
                                            <div class="card-body d-flex flex-wrap"><?

                                                while ($row = $group_list->fetchArray()) {
                                                    $group_name = str_replace("_", "-", $row[0]);
                                                    $postfix = get_group_postfix($group_name);
                                                    $group_type = get_group_type($postfix);
                                                    $course_num = get_course_num($group_name);

                                                    if ($course_num == $course && $group_type == $type) {
                                                        ?>
                                                        <div class="group_name"><a href="?group=<?echo($group_name);?>"><? echo($group_name); ?></a></div>
                                                        <?
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <?
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <?
                    }
                    ?>
                </div>
            </div>
            <div class="feedback d-flex justify-content-center">
                Для обратной связи: <a href="<? echo($email_to_feedback); ?>"><? echo($email_to_feedback); ?></a>
            </div>
        </section>
    </div>
</div>
