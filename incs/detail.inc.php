<?php
$q="select * from items where num='".$_GET['detailId']."'";

$r=mysql_query($q) or die(mysql_error());
$row=mysql_fetch_array($r);

$tPrix= $row['prix'];
if($tPrix==99999999 || $tPrix<100) {
$tPrix=$_lang[1]['ondemand'];
}
else {
	$tPrix=makePrice($tPrix)." &euro;";
}
if($row['surdemande']=='Y') {
	$tPrix=$_lang[1]['ondemand'];
}
if($row['vendu']=='Y') {
	if($row['location']=='Y') {
		$tPrix='<b>'.$_lang[1]['loue'].'</b>';
	}
	else {
		$tPrix='<b>'.$_lang[1]['vendu'].'</b>';
	}
}
if($row['enoption']=='Y') {
	$tPrix='<b>'.$_lang[1]['option'].'</b>';
}
?>

<div id='item' align=right>
  <table border="0" width=100% cellpadding=0 cellspacing=0>
    <tr>
      <td width=20 bgcolor="#020258"></td>
      <td width="120" bgcolor="#020258"></td>
      <td  bgcolor="#020258"></td>
      <td bgcolor="#020258"></td>
      <td width=10 bgcolor="#020258"></td>
    </tr>
    <tr>
      <td rowspan=6 width=20></td>
      <td colspan=3 align=left class='loc'></td>
      <td ><img src='medias/corner.gif' alt="immo le lion - Brussels" /></td>
    </tr>
    <tr>
      <td colspan=3 align=left class='loc'>
	  <table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td class=price2 width=30%><?= $row['locfr'];?></td><td class=price2  width=30% align=center><?= $tPrix;?></td><td class=price align=right ><a href='javascript:showMap(<?= $row['zip']; ?>)' class='price2'><img src="medias/map.gif" alt="map" width="45" height="38" border=0 align="absmiddle"/> situer sur la carte</a></td>
	  </tr><tr><td colspan=3>&nbsp;</td></tr></table></td>
      <td >&nbsp;</td>
    </tr>
    <tr>
      <td align=left><table border=0 cellpadding=0 cellspacing=0><tr><td><img src='medias/spirit.gif' width=400 height=1 /></td></tr><tr><td align=center><?php
	  if(file_exists('photos/'.$row['photo'].'.jpg')) { echo  displayMain($row['photo']);} ?></td></tr></table></td>
      <td colspan=2 class='descro' align=center><!-- thumbs -->
        <table border=0 cellpadding=5 cellspacing=0 bgcolor="#020935" width=320>
		<tr><td colspan=2><img src='medias/spirit.gif' width=280 height=1 /></td></tr>
          <tr>
            <td width=400><?php if(file_exists('photos/'.$row['photo'].'.jpg')) {
			echo displayThumb($row['photo'],'');
			}
			?></td>
            <td><?php if(file_exists('photos/'.$row['photo'].'a.jpg')) {
			echo displayThumb($row['photo'],'a');
			}
			?></td>
          </tr>
          <tr>
            <td><?php if(file_exists('photos/'.$row['photo'].'b.jpg')) {
			echo displayThumb($row['photo'],'b');
			}
			?></td>
            <td><?php if(file_exists('photos/'.$row['photo'].'c.jpg')) {
			echo displayThumb($row['photo'],'c');
			}
			?></td>
          </tr>
		 <!-- <tr>
            <td class=price><?php if($row['reference']!='') {?>
        (ref.:
        <?= $row['reference'];?>
        )
        <?php }?></td>
            <td class=price> <?= $tPrix;?></td>
          </tr>-->
        </table></td>
    </tr>
	<tr>
      <td colspan=4 align=left>&nbsp;</td>
    </tr>
    <tr>
      <td align=left><!--<span class="loc">
        <?= $row['locfr'];?>
        </span>--></td>
      <td colspan=2 align=right class='price'><?php if($row['reference']!='') {?>
        (ref.:
        <?= $row['reference'];?>
        )
        <?php }?></td>
      <td align=left>&nbsp;</td>
    </tr>
	<?php
	
	if($_SESSION['language']=='fr') {
	?><tr>
      <td colspan=4 align=left class="descro">
        <?= $row['descrfr'];?>      </td>
    </tr>
	<tr>
      <td colspan=4 align=left class="descro">&nbsp;
       </td>
    </tr>
	<tr>
      <td colspan=4 align=left class="descro">
        <?= $row['moredescrfr'];?>      </td>
    </tr>
	<?php
	}
	else if(strlen($row['descruk'])>1) {
	?>
	<tr>
      <td colspan=4 align=left class="descro">
        <?= $row['descruk'];?>      </td>
    </tr>
	<?php
	}
	
	?>
	<tr>
      <td colspan=4 align=left>&nbsp;</td>
    </tr>
	<tr>
      <td colspan=4 align=right>
        
		  <table border=0 class="detail" cellspacing=0 cellpadding=5 width=720><tr>
		 <td class=detaila align=right>surface:</td>
          <td class=detailb align=left><?php if($row['area']>0) {echo $row['area']."&nbsp;m²";} else { echo "N/A";} ?></td>
		  <td class=detaila  align=right>chambre(s:</td>
          <td class=detailb align=left><?= $row['rooms']; ?></td>
		  <td class=detaila  align=right>salle(s) d'eau:</td>
		  <td class=detailb align=left><?= $row['bathrooms']; ?></td>
		  <td class=detaila  align=right>garage(s):</td>
		  <td class=detailb align=left><?= $row['garages']; ?></td>
		  <td class=detaila><?= $row['garden']; ?></td>
		  </tr></table>      </td>
    </tr>
	<tr>
      <td colspan=4 align=left>&nbsp;</td>
    </tr>
    <tr>
      <td colspan=5 class=price align='center'><a href='javascript:history.back()' class=yellow><< retour à la liste</a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;<a href='javascript:window.print()' class=yellow>imprimer</a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;<a href="index.php?chap=5&newItem=<?= $row['num']; ?>" class=yellow>ajouter à ma sélection</a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;<a href='index.php?chap=4&ref=<?= $row['reference']; ?>' class=yellow>me contacter au sujet de ce bien</a></td>
    </tr>
  </table>
  </br><br />
</div>
