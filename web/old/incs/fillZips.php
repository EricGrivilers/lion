<?php
include('main.inc.php');
/*
$q="select * from items order by locfr";
$r=mysql_query($q) or die(mysql_error());

while($row=mysql_fetch_array($r)) {
$tq="insert into zip2item values('".substr($row['locfr'],0,4)."','".$row['num']."')";
$tr=mysql_query($tq) or die (mysql_error());

}
*/


/*
$q="select * from zip2item";
$r=mysql_query($q) or die(mysql_error());

while($row=mysql_fetch_array($r)) {
$tq="update items set zip='".$row['locationZip']."' where num='".$row['itemId']."'";
$tr=mysql_query($tq) or die (mysql_error());

}

*/
?>