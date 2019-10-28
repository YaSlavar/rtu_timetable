<?
global $item;
global $max_para_count;
?>

<table id="maket">
    <? for ($para = 1; $para < $max_para_count + 1; $para++) { ?>
        <tr>
            <td id="time">
                <?
                if ($item[$para]['time'] != "") {
                    echo $item[$para]['time'];
                } else {
                    echo get_para_time($para);
                }
                ?>
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
                        if ($item[$para]['name'] != '' and $item[$para]['room']) {
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
                <? if ($para < $max_para_count and $item['dist'] == "") { ?>
                    <div id="border"></div>
                <? } ?>
            </td>
        </tr>
    <? } ?>
</table>