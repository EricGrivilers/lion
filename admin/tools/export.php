<?php
//header("Content-type: application/vnd.ms-excel");


function formatText($text) {
	
		$text=preg_replace("/\\r/"," ",$text);
		$text=preg_replace("/\\n/"," ",$text);
		return $text;
	}
switch($_GET['exp']) {
default:


//header("Content-disposition:  attachment; filename=biens_" .date("Y-m-d").".csv");
include('../config.inc.php');


$code="id\treference\tname\tprix\ttype\tetat\tlocalité\tactif\tdate\tvisite quot\tvisite hebdo\tvisite mens\tvisite total\tdescription\n";
	

	//$url="index.php?kind=item&action=list&level=0&actifonly=".$_GET['actifonly'];
	$o=0;
	
	$filter='';
	$orderby='prix';
	
	if(isset($_GET['orderby'])) {
	$orderby=$_GET['orderby'];
	}
	if($_GET['actifonly']==true) {
	$filter.=" and actif='Y' ";
	}
	
	$q="select * from items where num>0 ".$filter." order by ".$orderby;
	$r=mysql_query($q) or die(mysql_error());
	
	
	
	
	while($row=mysql_fetch_array($r)) {
		$etat='';
		if($row['location']=='Y') {
			$type='location';
		}
		else {
			$type='vente';
		}
		if($row['vendu']=='Y') {
			if($row['location']=='Y') {
				$etat ='loué';
			}
			else {
				$etat ='vendu';
			}
		}
		else if($row['enoption']=='Y') {
			$etat='option';
		}
	
	/*
	if(file_exists('../../photos/'.$row['photo'].'s.jpg')) { 
	$code.="<img src='../../photos/".$row['photo']."s.jpg'/>";
	}
	*/
	
	
	$code.= $row['num']."\t";
	$code.= $row['reference']."\t";
	$code.= $row['name']."\t";
	$code.= $row['prix']."\t";
	$code.= $type."\t";
	$code.= $etat."\t";
	$code.= $row['locfr']."\t";
	$code.= $row['actif']."\t";
	$code.= $row['datein']."\t";
	
	$code.= $row['lastdayview']."\t";
	$code.= $row['lastweekview']."\t";
    $code.= $row['lastmonthview']."\t";
	$code.= $row['totalview']."\t";
	$code.= formatText($row['descrfr'])."\n";
	}
	


break;
case 'users':

//header("Content-disposition:  attachment; filename=users_" .date("Y-m-d").".csv");
include('../../incs/config.inc.php');


$code="Email\tlangue\tsalutation\tfirstname\tlastname\ttel\tzip\tfax\tstreet\tnumber\tcity\tcountry\tcompany\trecherche\ttype\tcommune\tprice\trefs\n";
$q="select *,type.type_fr as type2, users.id as id from users, users2search, type where  users.id=users2search.userId and users2search.type=type.id ";
	$r=mysql_query($q) or die(mysql_error());
	while($row=mysql_fetch_array($r)) {
		if($row['location']=='innercity') { $loc='Bruxelles'; }
		if($row['location']=='outercity') { $loc='Brabant'; }
		if($i>1) {$i=0;}
		$code.=$row['email']."\t";               
   $code.=$row['language']."\t";           
   $code.=$row['salutation']."\t";
 $code.=$row['firstname']."\t";              
   $code.=$row['lastname']."\t";                
   $code.=$row['tel']."\t";             
   $code.=$row['zip']."\t";           
  $code.=$row['fax']."\t";                
   $code.=$row['street']."\t";               
  $code.=$row['number']."\t";                
  $code.=$row['city']."\t";               
  $code.=$row['country']."\t";          
  $code.=$row['company']."\t";
   $code.=$row['type2']."\t";
   $code.=$row['searchfor']."\t";
   $code.=$loc."\t";
   $code.=$row['price']."\t";

  $refs=array();
  $a=1;
   $tq="select * from users2items where users2items.userId='".$row['id']."'";
   //echo $tq;
   $tr=mysql_query($tq) or die(mysql_error());
   while($trow=mysql_fetch_array($tr)) {
   		$refs[]="http://www.immo-lelion.be/index.php?chap=".$row['type']."&detailId=".$trow['itemId'];
		$a++;
   }
   $code.=implode('|',$refs)."\n";
	}
break;
}









$mode="xls";
$type="excel";
		 
header("Content-Type: application/vnd.ms-$type; charset=UTF-8'");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-disposition: attachment; filename=". date("d-m-Y")."-Export-".$_GET['exp'].".xls");

print $code;
exit;
?>
