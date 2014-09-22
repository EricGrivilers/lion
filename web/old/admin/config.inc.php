<?php


//db

$DBhost = "web1003.optinet-isp.net";
$DBuser = "immolelionbe";
$DBpass = "gdfd54yuy";
$DBName = "immolelionbe";


$Connect = mysql_connect($DBhost, $DBuser, $DBpass) or die("Can not connect");
@mysql_select_db("$DBName") or die ("Can not access the database");


?>
