<?php
/**
 * Created by PhpStorm.
 * User: yasla
 * Date: 01.09.2019
 * Time: 21:39
 */

$db = new SQLite3("table.db");
# день должен быть понедельником
$start_day = "02.09.2019";
$delta = 35;
$etalon_group = "БНБО-02-16";