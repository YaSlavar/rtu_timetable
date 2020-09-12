<?
global $item;
global $max_lesson_count;
global $timetable_type;
?>

<table id="maket">
    <? for ($lesson = 1; $lesson < $max_lesson_count + 1; $lesson++) { ?>
        <tr>
            <td id="time">
                <?
                if ($item[$lesson]['time'] != "") {
                    echo $item[$lesson]['time'];
                } else {
                    echo get_lesson_time($lesson);
                }
                ?>
            </td>
            <td id="lesson">
                <div id="dist">
                    <?
                    if ($item[$lesson]['except'] != 'False' and $item[$lesson]['include'] != 'False' or ($item[$lesson]['except'] == '' and $item[$lesson]['include'] == '')) {
                        echo($item[$lesson]['name']);
                    }
                    if ($item[$lesson]['name'] == '') {
                        echo "<div class='none'>- - - - - - - - - - - - - - - - - - - - - - -</div>";
                    }
                    ?>
                </div>
                <div id="aud">
                    <?
                    if ($item[$lesson]['except'] != 'False' and $item[$lesson]['include'] != 'False' or ($item[$lesson]['except'] == '' and $item[$lesson]['include'] == '')) {
                        if ($item[$lesson]['name'] != '' and $item[$lesson]['room']) {
                            echo('ауд.' . $item[$lesson]['room']);
                            if ($item[$lesson]['type'] != '') {
                                echo('  ( ' . $item[$lesson]['type'] . ' )');
                            }
                        }
                    }
                    ?>
                </div>
                <div id="teacher">
                    <?
                    if ($item[$lesson]['except'] != 'False' and $item[$lesson]['include'] != 'False' or ($item[$lesson]['except'] == '' and $item[$lesson]['include'] == '')) {
                        echo($item[$lesson]['teacher']);
                    }
                    ?>
                </div>
                <? if ($lesson < $max_lesson_count and $item['dist'] == "") { ?>
                    <div id="border"></div>
                <? } ?>
            </td>
        </tr>
    <? } ?>
</table>