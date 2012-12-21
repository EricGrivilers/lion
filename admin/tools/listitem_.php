<?php
switch($_GET['level']) {

	default:
	$url="index.php?kind=item&action=list&level=0&actifonly=".$_GET['actifonly'];
	$o=0;
	?>

<input type=checkbox name='actifonly' onclick='document.location="<?= $url; ?>&actifonly=true"'>
biens actifs uniquement | <a href="javascript:window.print()" class=yellow>imprimer</a> <br />
<input type='button' value='exporter' onclick="document.location='tools/export.php?exp=items&kind=item&action=list&level=0&actifonly=<?= $_GET['actifonly']; ?>'" />
<table border=0 cellpadding=2 cellspacing=1 align=center>
  <tr>
    <td></td>
    <td>id</td>
    <td><a href='<?= $url; ?>&orderby=reference' class='yellow'>ref.</a></td>
    <td><a href='<?= $url; ?>&orderby=prix' class='yellow'>prix</a></td>
    <td><a href='<?= $url; ?>&orderby=location' class='yellow'>type</a></td>
    <td>état</td>
    <td><a href='<?= $url; ?>&orderby=locfr' class='yellow'>localité</a></td>
    <td><a href='<?= $url; ?>' class='yellow'>description</a></td>
    <td><a href='<?= $url; ?>&orderby=actif' class='yellow'>actif</a></td>
    <td><a href='<?= $url; ?>&orderby=datein' class='yellow'>date</a></td>
	<td><a href='<?= $url; ?>&orderby=dayview' class='yellow'>visite quot.</a></td>
	<td><a href='<?= $url; ?>&orderby=weekview' class='yellow'>visite hebdo</a></td>
	<td><a href='<?= $url; ?>&orderby=monthview' class='yellow'>visite mens.</a></td>
	<td><a href='<?= $url; ?>&orderby=monthview' class='yellow'>visite total</a></td>
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
  <tr class='rank<?= $o; ?>'>
    <td><?php
	if(file_exists('../photos/thumbs/'.$row['photo'].'.jpg')) { ?>
      <img src='../photos/thumbs/<?= $row['photo']; ?>.jpg'/>
      <?php } ?></td>
    <td><?= $row['num']; ?></td>
    <td><?= $row['reference']; ?></td>
    <td><?= $row['prix']; ?></td>
    <td><?= $type; ?></td>
    <td><?= $etat; ?></td>
    <td><?= $row['locfr']; ?></td>
    <td><?= substr($row['descrfr'],0,255); ?></td>
    <td><?= $row['actif']; ?></td>
    <td><?= $row['datein']; ?></td>
    <td><?= $row['dayview']."<br>(".$row['lastdayview'].")"; ?></td>
	<td><?= $row['weekview']."<br>(".$row['lastweekview'].")"; ?></td>
    <td><?= $row['monthview']."<br>(". $row['lastmonthview'].")"; ?></td>
	<td><?= $row['totalview']; ?></td>
	  <td><a href='index.php?kind=stats&action=viewt&&itemId=<?= $row['num']; ?>'><img src='../medias/b_view.png' border=0/></a></td>
    <td><a href='index.php?kind=item&action=edit&level=2&itemId=<?= $row['num']; ?>'><img src='../medias/b_edit.png' border=0/></a></td>
    <td><a href='index.php?kind=item&action=delete&level=0&itemId=<?= $row['num']; ?>'><img src='../medias/b_drop.png' border=0/></a></td>
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
