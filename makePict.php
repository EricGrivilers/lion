<?php
include('incs/main.inc.php');

$q="select * from items";
$r=mysql_query($q) or die(mysql_error());
echo mysql_affected_rows($Connect);
while($row=mysql_fetch_array($r)) {
	$url='photos/'.$row['photo'].'s.jpg';
	echo $url;
	$pic = @imagecreatefromjpeg($url);
    if ($pic) {
		echo 'yes<br>';
		$width = imagesx($pic);
        $height = imagesy($pic);
		
		if($width>168 && $height>127 ) {
			$tq="update items set viewable='checked' where num='".$row['num']."'";
		
			echo " and yes";
		}
		else {
		$tq="update items set viewable='' where num='".$row['num']."'";
		}
		$tr=mysql_query($tq) or die(mysql_error());
    }
}


?>