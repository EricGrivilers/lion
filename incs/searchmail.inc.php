<?php

$aq="select * from items,users,users2items  where users.id=users2items.userId and users.id='".$_SESSION['userId']."' and items.num=users2items.itemId group by items.num";
//echo $aq;
$ar=mysql_query($aq) or die(mysql_error());

$message.="Nom:".$_SESSION['lastname']."\r\n";
$message.="Prénom:".$_SESSION['firstname']."\r\n";
$message.="Email:".$_SESSION['email']."\r\n";
$message.="Tel:".$_SESSION['phone']."\r\n";
$message.="\r\n-------------------------\r\n";
$message.="Biens selectionnés:\r\n";
while($arow=mysql_fetch_array($ar)) {
if($arow['location']=='Y') {
$tchap=2;
}
else {
$tchap=1;
}
	$message.=$arow['reference']." (http://www.immo-lelion.be/index.php?chap=".$tchap."&detailId=".$arow['num'].")   \r\n";


}

$message.="\r\n-------------------------\r\n";
$message.="Critères de recherche:\r\n";
$tt=array('','maison','appartement','terrain','non précisé');
$message.="bien:".$tt[$_SESSION['type']]."\r\n";
$message.="prix:".$_SESSION['prix']."\r\n";
$message.="localité:".$_SESSION['location']."\r\n";
$message.="type:".$_SESSION['searchfor']."\r\n";
//$message="ref:".mysql_result($ar,0,'reference')." (http://www.immo-lelion.be/index.php?chap=1&detailId=".$_GET['newItem']."') | ".$_SESSION['firstname'].",".$_SESSION['lastname'].", tel:".$_SESSION['phone'];
			mail('info@immo-lelion.be','Nouvelle selection de '.$_SESSION['firstname'].", ".$_SESSION['lastname'],$message,'from:'.$_SESSION['email']);
?>