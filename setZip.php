<?php
include('incs/main.inc.php');

$q="select * from items order by num";
$r=mysql_query($q) or die(mysql_error());
while($row=mysql_fetch_array($r)) {
$tq="update items set zip='".substr($row['locfr'],0,4)."' where num='".$row['num']."'";
echo $tq;
$tr=mysql_query($tq) or die(mysql_error());

}
?>