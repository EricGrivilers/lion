<?php

if(!isset($_GET['detailId'])) {
include('incs/list.inc.php');
}
else {
include('incs/detail.inc.php');
}
?>
