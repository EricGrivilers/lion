<?php


$pagesnames=array('home','ventes','locations','presentation','contact','profil','nouveautes');

session_start();
if(!isset($_SESSION['language'])) {
$_SESSION['language']='fr';
$language='fr';
}
if(isset($_GET['tl'])) {
$_SESSION['language']=$_GET['tl'];

}


if(isset($_GET['logoff'])) {
$_SESSION['logged']=false;
}
if(isset($_SESSION['firstname'])) {
$loggedas=$_SESSION['firstname']." ".$_SESSION['lastname']." | <a href='index.php?chap=0&logoff=true' class=yellow>log off</a>";
}


/*
echo $_SESSION['language'];
if(!isset($_SESSION['language'])) {
$_SESSION['language']='fr';
$language='fr';
}

if(isset($_GET['tl'])) {
$_SESSION['language']=$_GET['tl'];
}
*/

if(!isset($_GET['chap'])) { $_GET['chap']=0;}

function datePopup($lap,$start,$end,$selected) {
	for($i=$start;$i<=$end;$i++) {
		$j=$i;
		if($j<10) {$j='0'.$i;}
		$code.="<option value='".$j."' ";
		if($j==$selected) {
			$code.=" selected";
		}
		$code.=">".$j."</option>";
	}
	return "<select name='".$lap."'>".$code."</select>";
}


function createPict($url,$thumburl,$twidth) {
				$pic = @imagecreatefromjpeg($url) or die ("Image not found!");
    			if ($pic) {
					$width = imagesx($pic);
        			$height = imagesy($pic);
					//$twidth = 320;

					$theight = $twidth * $height / $width; # calculate height
					$thumb = @imagecreatetruecolor ($twidth, $theight) or die ("Can't create Image!");
					imagecopyresampled($thumb, $pic, 0, 0, 0, 0,$twidth, $theight, $width, $height); # resize image into thumb
					ImageJPEG($thumb,$thumburl,100); # Thumbnail as JPEG
    			}
}

function makeThumb($url,$thumburl) {
	$pic = imagecreatefromjpeg($url) or die ("Image not found!");
    			if ($pic) {
				//echo 'yes';
					$width = imagesx($pic);
        			$height = imagesy($pic);
					//$twidth = 320;
					if($width>$height) {
						$twidth=185;
						$theight = $twidth * $height / $width; # calculate height
					}
					else {
						$theight=120;
						$twidth = $theight * $width/$height ; # calculate height
					}
					$thumb = imagecreatetruecolor ($twidth, $theight) or die ("Can't create Image!");
					imagecopyresampled($thumb, $pic, 0, 0, 0, 0,$twidth, $theight, $width, $height); # resize image into thumb
					ImageJPEG($thumb,$thumburl,100); # Thumbnail as JPEG
    			}


}

function makeBig($url,$thumburl) {
	$pic = imagecreatefromjpeg($url) or die ("Image not found!");
    			if ($pic) {
				//echo 'yes';
					$width = imagesx($pic);
        			$height = imagesy($pic);
					//$twidth = 320;
					if($width>$height) {
						$twidth=590;
						$theight = $twidth * $height / $width; # calculate height
					}
					else {
						$theight=400;
						$twidth = $theight * $width/$height ; # calculate height
					}
					$thumb = @imagecreatetruecolor ($twidth, $theight) or die ("Can't create Image!");
					imagecopyresampled($thumb, $pic, 0, 0, 0, 0,$twidth, $theight, $width, $height); # resize image into thumb
					ImageJPEG($thumb,$thumburl,100); # Thumbnail as JPEG
    			}


}

function displayThumb($photo,$ad) {
$url="photos/".$photo.$ad.".jpg";
	$pic = imagecreatefromjpeg($url) or die ("Image not found!");
    if ($pic) {
		$sens='';
		$width = imagesx($pic);
       $height = imagesy($pic);
		if($width>$height) {
			$w=130;
			$h = 98;
		}
		else {
			$w=73;
			$h = 98;
		}
		$size=$width."x".$height;
	}
	return ("<a href=# onmouseover=\"switchPict('".$photo."','".$ad."','".$size."')\" ><img src='photos/".$photo.$ad.".jpg' class='thumbnail' width=".$w." height=".$h." border=0/></a>");
}


function displayMain($photo) {
$url="photos/".$photo.".jpg";
	$pic = imagecreatefromjpeg($url) or die ("Image not found!");
    if ($pic) {
		$width = imagesx($pic);
       $height = imagesy($pic);
		if($width>$height || $width>=400) {
			$p=" width=590 ";
		}
		else if($height>$width || $height>=300){
			$q=" height=400 ";
		}

	}
	return ("<img src='photos/".$photo.$ad.".jpg' class='thumbnail' ".$p." ".$q." border=0/ name='big'>");
	//return ("<img src='photos/".$photo.$ad.".jpg' class='thumbnail' height=300 border=0/ name='big'>");
}



function makePrice($price) {
$l=strlen($price);
$a=array();
for($i=0;$i<$l;$i++) {
	$a[$i]=substr($price,$i,1);
}
$t=0;
$price='';
for($i=$l;$i>=0;$i--) {
	$price=$a[$i].$price;
	$t++;
	if($t==4) {

	$price=".".$price;
	}
	if($t==7 && $l>6) {

	$price=".".$price;
	}
}
return $price;
}


function shakeDate($date) {

	return substr($date,6,2)."/".substr($date,4,2)."/".substr($date,0,4);
}

/*



	$q="select * from items_stats where type='day' and date='".date('Ymd')."'";
	$r=mysql_query($q) or die(mysql_error());
	$max=mysql_affected_rows($Connect);
	if($max==0) {
		$q="update items set lastdayview=dayview, dayview=0";
		$r=mysql_query($q) or die(mysql_error());
		if(date('D')=='Mon') {
			$q="update items set lastweekview=weekview, weekview=0";
			$r=mysql_query($q) or die(mysql_error());
		}
		$q="insert into items_stats (type,date) values ('day','".date('Ymd')."')";
		$r=mysql_query($q);
	}

	$q="select * from items_stats where type='month' and date='".date('m')."'";
	//echo $q;
	$r=mysql_query($q) or die(mysql_error());
	$max=mysql_affected_rows($Connect);
	if($max==0) {
		$q="update items set lastmonthview=monthview, monthview=0";
		$r=mysql_query($q) or die(mysql_error());
		$q="insert into items_stats (type,date) values ('month','".date('m')."')";
		$r=mysql_query($q);
	}



*/

?>
