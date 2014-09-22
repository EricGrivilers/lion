<?php
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
    <td></td>
    <td>id</td>
    <td><a href='<?php echo  $url; ?>&orderby=reference' class='yellow'>ref.</a></td>
    <td><a href='<?php echo  $url; ?>&orderby=prix' class='yellow'>prix</a></td>
    <td><a href='<?php echo  $url; ?>&orderby=location' class='yellow'>type</a></td>
    <td>état</td>
    <td><a href='<?php echo  $url; ?>&orderby=locfr' class='yellow'>localité</a></td>
    <td><a href='<?php echo  $url; ?>' class='yellow'>description</a></td>
    <td><a href='<?php echo  $url; ?>&orderby=actif' class='yellow'>actif</a></td>
    <td><a href='<?php echo  $url; ?>&orderby=datein' class='yellow'>date</a></td>
	<td><a href='<?php echo  $url; ?>&orderby=dayview' class='yellow'>visite quot.</a></td>
	<td><a href='<?php echo  $url; ?>&orderby=weekview' class='yellow'>visite hebdo</a></td>
	<td><a href='<?php echo  $url; ?>&orderby=monthview' class='yellow'>visite mens.</a></td>
	<td><a href='<?php echo  $url; ?>&orderby=monthview' class='yellow'>visite total</a></td>
    <td colspan=3>
  </tr>
  <?php
	$filter='';
	$orderby='prix';
	
	if(isset($_GET['orderby'])) {
	$orderby=$_GET['orderby'];
	}
	if($_GET['actifonly']==true) {
	$filter.=" and actif='Y' ";
	}
	
	$q="select * from items where num>0 ".$filter." order by ".$orderby;
	echo $q;
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
  <tr class='rank<?php echo  $o; ?>'>
    <td><?php
	if(file_exists('../photos/thumbs/'.$row['photo'].'.jpg')) { ?>
      <img src='../photos/thumbs/<?php echo  $row['photo']; ?>.jpg'/>
      <?php } ?></td>
    <td><?php echo  $row['num']; ?></td>
    <td><?php echo  $row['reference']; ?></td>
    <td><?php echo  $row['prix']; ?></td>
    <td><?php echo  $type; ?></td>
    <td><?php echo  $etat; ?></td>
    <td><?php echo  $row['locfr']; ?></td>
    <td><?php echo  substr($row['descrfr'],0,255); ?></td>
    <td><?php echo  $row['actif']; ?></td>
    <td><?php echo  $row['datein']; ?></td>
    <td><?php echo  $row['dayview']."<br>(".$row['lastdayview'].")"; ?></td>
	<td><?php echo  $row['weekview']."<br>(".$row['lastweekview'].")"; ?></td>
    <td><?php echo  $row['monthview']."<br>(". $row['lastmonthview'].")"; ?></td>
	<td><?php echo  $row['totalview']; ?></td>
	  <td><a href='index.php?kind=stats&action=viewt&&itemId=<?php echo  $row['num']; ?>'><img src='../medias/b_view.png' border=0/></a></td>
    <td><a href='index.php?kind=item&action=edit&level=2&itemId=<?php echo  $row['num']; ?>'><img src='../medias/b_edit.png' border=0/></a></td>
    <td><a href='index.php?kind=item&action=delete&level=0&itemId=<?php echo  $row['num']; ?>'><img src='../medias/b_drop.png' border=0/></a></td>
  </tr>
  <?php
	$o++;
	if($o>1) {$o=0;}
	}
	?>
</table>
<?php
	break;
}
?>
