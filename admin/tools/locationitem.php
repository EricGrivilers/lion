<?php
if(isset($_POST['level'])) {$_GET['level']=$_POST['level'];}
switch($_GET['level']) {
default:
?>
<input type='hidden' name='level' value='' />
<table align=center>
  <tr>
    <td colspan=2 class=yellow>Ajouter une commune</td>
  </tr>
  <tr>
    <td>Zip</td>
    <td><input type=text name=zip></td>
  </tr>
  <tr>
    <td>Commune</td>
    <td><input type=text name=fr></td>
  </tr>
  <tr>
    <td>19 communes</td>
    <td><input type=checkbox name=innercity></td>
  </tr>
  <tr>
    <td></td>
    <td><input type=button value='ajouter' onclick="document.theForm.level.value=5; submit()"></td>
  </tr>
</table>
<br>
<br>
<table align=center>
  <tr>
    <td>zip</td>
    <td>commune</td>
    <td>appartient aux '19 communes'</td>
    <td colspan=2>
  </tr>
  <?php
$o=0;
	$q="select * from locations order by zip";
	$r=mysql_query($q) or die(mysql_error());
	while($row=mysql_fetch_array($r)) {
		?>
  <tr class='rank<?php echo  $o; ?>'>
    <td><?php echo  $row['zip']; ?></td>
    <td><?php echo  $row['fr']; ?></td>
    <td><?php echo  $row['innercity']; ?></td>
    <td><?php if($row['fixed']!='true') { ?><a href='index.php?kind=item&action=location&level=1&itemId=<?php echo  $row['id']; ?>'><img src='../medias/b_edit.png' border=0/></a><?php } ?></td>
    <td><?php if($row['fixed']!='true') { ?><a href='index.php?kind=item&action=location&level=4&itemId=<?php echo  $row['id']; ?>'><img src='../medias/b_drop.png' border=0/></a><?php } ?></td>
  </tr>
  <?php
		$o++;
		if($o>1) {$o=0; }
	}
	?>
</table>
<?php 
break;
case 1:
$q="select * from locations where id='".$_GET['itemId']."'";
$r=mysql_query($q) or die(mysql_error());

?>
<input type='hidden' name='level' value='' />
<input type='hidden' name='itemId' value='<?php echo  $_GET['itemId']; ?>' />
<table align=center>
  <tr>
    <td colspan=2 class=yellow>Modifier une commune</td>
  </tr>
  <tr>
    <td>Zip</td>
    <td><input type=text name=zip value="<?php echo  mysql_result($r,0,'zip'); ?>"></td>
  </tr>
  <tr>
    <td>Commune</td>
    <td><input type='text' name='fr' value="<?php echo  mysql_result($r,0,'fr'); ?>" /></td>
  </tr>
   <tr>
    <td>19 communes</td>
    <td><input type='checkbox' name='innercity' <?php if(mysql_result($r,0,'innercity')=='true') { echo ' checked'; } ?> value='true'></td>
  </tr>
  <tr>
    <td></td>
    <td><input type=button value='modifier' onclick="document.theForm.level.value=2;submit()"><!-- "document.location='index.php?zip='+document.theForm.zip.value+'&innercity='+document.theForm.innercity.value+'&fr='+document.theForm.fr.value+'&kind=item&action=location&level=2&itemId=<?php echo  $_GET['itemId']; ?>'" --></td>
  </tr>
</table>
<?php
break;

case 2:
if($_POST['innercity']=='true') { $inner='true';}
else { $inner='false';}
$q="update locations set zip='".$_POST['zip']."', fr=\"".$_POST['fr']."\", innercity='".$inner."' where id='".$_POST['itemId']."'";
$r=mysql_query($q) or die(mysql_error());
?>
<table align=center>
  <tr>
    <td>Localité modifiée.</td>
  </tr>
</table>
<?php 
break;

case 4:
$q="delete from locations where id='".$_GET['itemId']."'";
$r=mysql_query($q) or die(mysql_error());
?>
<table align=center>
  <tr>
    <td>Localité effacée.</td>
  </tr>
</table>
<?php
break;

case 5:
if($_POST['innercity']=='on') {$inner='true';}
else { $inner='false';}
	$q="insert into locations (zip,fr,innercity) values('".$_POST['zip']."',\"".$_POST['fr']."\",'".$inner."')";
	$r=mysql_query($q) or die(mysql_error());
	?>
	Commune ajoutée.
	<?php
break;
}
?>
