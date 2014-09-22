<?php
include('incs/main.inc.php');

$q="select * from items ";
$r=mysql_query($q) or die(mysql_error());
while($row=mysql_fetch_array($r)) {
$tq="update items set tphoto='npict".$row['num']."' where num='".$row['num']."'";
echo $tq;
$tr=mysql_query($tq) or die(mysql_error());
rename("photos/".$row['photo'].".jpg","photos/".$row['tphoto'].".jpg");
rename("photos/".$row['photo']."s.jpg","photos/".$row['tphoto']."s.jpg");
}
?>