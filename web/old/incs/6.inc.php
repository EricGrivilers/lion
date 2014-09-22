<?php
if($_GET['searchfor']=='rent') {
$saleonoff="";
$rentonoff="on";
}
else {
$saleonoff="on";
$rentonoff="";
}
?><div align=right style="background-color:#020935"><a href="index.php?chap=6&searchfor=sale" class="subtitle<?= $saleonoff; ?>"><?= ucfirst($lang[0]['sale']); ?></a> | <a href="index.php?chap=6&searchfor=rent" class="subtitle<?= $rentonoff; ?>"><?= ucfirst($lang[0]['rent']); ?></a></div>
<?php

if(!isset($_GET['detailId'])) {
$_GET['type']=2;
$_GET['type']='no';
include('incs/list.inc.php');
}
else {
include('incs/detail.inc.php');
}
?>
