<?php

include_once __root__.'/lib/GoogleMap/GoogleMap.php';
include_once __root__.'/lib/GoogleMap/JSMin.php';
switch($_GET['level']) {

	default:
	$url="index.php?kind=item&action=list&level=0&actifonly=".$_GET['actifonly'];
	$o=0;
	?>

<input type=checkbox name='actifonly' onclick='document.location="<?php echo  $url; ?>&actifonly=true"'>
biens actifs uniquement | <a href="javascript:window.print()" class=yellow>imprimer</a> <br />
<input type='button' value='exporter' onclick="document.location='tools/export.php?exp=items&kind=item&action=list&level=0&actifonly=<?php echo  $_GET['actifonly']; ?>'" />
<table border=0 cellpadding=2 cellspacing=1 align=center>
  <tr>
    <th></th>
    <th>id</th>
    <th><a href='<?php echo  $url; ?>&orderby=reference' class='yellow'>ref.</a></th>
    <th><a href='<?php echo  $url; ?>&orderby=name' class='yellow'>nom</a></th>
    <th><a href='<?php echo  $url; ?>&orderby=prix' class='yellow'>prix</a></th>
    <th><a href='<?php echo  $url; ?>&orderby=location' class='yellow'>type</a></th>
    <th>état</th>
    <th><a href='<?php echo  $url; ?>&orderby=locfr' class='yellow'>localité</a></th>
    <th><a href='<?php echo  $url; ?>' class='yellow'>description</a></th>
    <th><a href='<?php echo  $url; ?>&orderby=actif' class='yellow'>actif</a></th>
    <th><a href='<?php echo  $url; ?>&orderby=datein' class='yellow'>date</a></th>
	<th><a href='<?php echo  $url; ?>&orderby=dayview' class='yellow'>visite quot.</a></th>
	<th><a href='<?php echo  $url; ?>&orderby=weekview' class='yellow'>visite hebdo</a></th>
	<th><a href='<?php echo  $url; ?>&orderby=monthview' class='yellow'>visite mens.</a></th>
	<th><a href='<?php echo  $url; ?>&orderby=monthview' class='yellow'>visite total</a></th>
    <th colspan=3>&nbsp;</th>
  </tr>
  <?php
  
  
  //stats v2
  
  
 $currentMonth=date('n');
	$currentWeek=date('W');
	$currentDay=date('z');
	$currentWDay=date('N');
	$currentYear=date('Y');
	/*$sq="select * from item_statsv2 where itemId='".$_GET['itemId']."'";
	$sr=mysql_query($sq) or die(mysql_error());
	//$srow=mysql_fetch_array($sr);
	*/
	
	$fields=array('days'=>366,'weeks'=>52,'months'=>12,'years'=>22,'wdays'=>7);
	function statsv2($row,$field,$current) {
	//foreach($fields as $keys => $values) {
		//$t=$fields[$i];
		//if($row[$keys]!='') {
			//$tArray=explode(",",$row[$keys]);
			$tArray=explode(",",$row[$field]);
		//}
		/*else {
			$tArray=array();
			for($i=0;$i<=$values;$i++) {
				$tArray[$i]=0;
			}
		}*/
		
		return $tArray[$current];
	
	}
	
	function statsv2total($row) {
		$tArray=explode(",",$row['months']);
		for($i=0;$i<count($tArray);$i++) {
			$t+=$tArray[$i];
		}
		
		
		return $t;
	
	}
	
	
	$q="select items.*,item_statsv2.days,item_statsv2.months,item_statsv2.weeks  from items LEFT JOIN item_statsv2 on item_statsv2.itemId=items.num where items.num>0 AND Lat=''  LIMIT 0,50";
	$r=mysql_query($q) or die(mysql_error());
	while($row=mysql_fetch_array($r)) {

		$MAP_OBJECT = new GoogleMapAPI(); $MAP_OBJECT->_minify_js = isset($_REQUEST["min"])?FALSE:TRUE;
		$lat='';$lng='';
		
		if($geocodes = $MAP_OBJECT->getGeoCode(utf8_encode($row['name']." ".$row['locfr']))) {
			$lng=$geocodes['lon'];
			$lat=$geocodes['lat'];
			
		}
		$tq="update items SET Lng='".$lng."',lat='".$lat."',processed='1' WHERE num='".$row['num']."'";
		
		$tr=mysql_query($tq);
	}

	
	//end stats
	
	$filter='';
	$orderby='prix';
	
	if(isset($_GET['orderby'])) {
	$orderby=$_GET['orderby'];
	}
	if($_GET['actifonly']==true) {
	$filter.=" and actif='Y' ";
	}
	$oddeven=array('odd','even');
	$q="select items.*,item_statsv2.days,item_statsv2.months,item_statsv2.weeks  from items LEFT JOIN item_statsv2 on item_statsv2.itemId=items.num where items.num>0 ".$filter." order by ".$orderby." ";
	//$q="select items.*  from items  where items.num>0 ".$filter."order by ".$orderby." ";
	
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
	?>
  <tr class="<?php echo  $oddeven[$o]; ?>">
    <td><?php
	if(file_exists('../photos/thumbs/'.$row['photo'].'.jpg')) { ?>
      <a href='index.php?kind=item&action=edit&level=2&itemId=<?php echo  $row['num']; ?>'><img src='../photos/thumbs/<?php echo  $row['photo']; ?>.jpg' width='80'/></a>
      <?php } ?></td>
    <td><?php echo  $row['num']; ?></td>
    <td><?php echo  $row['reference']; ?></td>
    <td><?php echo  $row['name']; ?></td>
    <td><?php echo  $row['prix']; ?></td>
    <td><?php echo  $type; ?></td>
    <td><?php echo  $etat; ?></td>
    <td><?php echo  $row['locfr']; ?></td>
    <td><?php echo  substr($row['descrfr'],0,255); ?></td>
    <td><?php echo  $row['actif']; ?></td>
    <td><?php echo  $row['datein']; ?></td>
    <td><?php echo  statsv2($row,'days',$currentDay)."<br>(".statsv2($row,'days',$currentDay-1).")"; ?></td>
   <!-- <td><?php echo  $row['dayview']."<br>(".$row['lastdayview'].")"; statsv2($row,'days',$currentDay) ?></td>-->
	<td><?php echo  statsv2($row,'weeks',$currentWeek)."<br>(".statsv2($row,'weeks',$currentWeek-1).")"; ?></td>
    <!--<td><?php echo  $row['weekview']."<br>(".$row['lastweekview'].")"; ?></td>-->
    <td><?php echo  statsv2($row,'months',$currentMonth)."<br>(".statsv2($row,'months',$currentMonth-1).")"; ?></td>
    <!--<td><?php echo  $row['monthview']."<br>(". $row['lastmonthview'].")"; ?></td>-->
	<!--<td><?php echo  $row['totalview']; ?></td>-->
    <td><?php echo  statsv2total($row); ?></td>
	  <td><a href='index.php?kind=stats&action=viewt&&itemId=<?php echo  $row['num']; ?>'><img src='../medias/b_view.png' border=0/></a></td>
    <td><a href='index.php?kind=item&action=edit&level=2&itemId=<?php echo  $row['num']; ?>'><img src='../medias/b_edit.png' border=0/></a></td>
    <td><a href='index.php?kind=item&action=delete&level=0&itemId=<?php echo  $row['num']; ?>'><img src='../medias/b_drop.png' border=0/></a></td>
  </tr>
  <?php
	$o=!$o;
	}
	?>
</table>
<?php
	break;
}
?>
