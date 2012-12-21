<?php
switch($_GET['level']) {

	default:
	?>
	<table border=0 class=thumb align=center bgcolor="#FF6600"><tr><td class=title>Etes-vous sûr de vouloir supprimer ce bien (cette action est irrévocable)<br /><input type=button value='oui' onclick="document.location='index.php?kind=item&action=delete&level=1&itemId=<?= $_GET['itemId']; ?>';"/><input type=button value='non' onclick="history.back()"/></td></tr></table>
	
	<?php
	$q="select * from items where num='".$_GET['itemId']."'";
	$r=mysql_query($q) or die(mysql_error());
$row=mysql_fetch_array($r);

$tPrix= $row['prix'];
if($tPrix==99999999 || $tPrix<100) {
$tPrix='prix sur demande';
}
else {
	$tPrix=makePrice($tPrix)." &euro;";
}
if($row['surdemande']=='Y') {
	$tPrix='prix sur demande';
}
if($row['vendu']=='Y') {
	if($row['location']=='Y') {
		$tPrix='<b>L O U É</b>';
	}
	else {
		$tPrix='<b>V E N D U</b>';
	}
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
	  <table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td class=price2 width=30%><?= $row['locfr'];?></td><td class=price2  width=30% align=center><?= $tPrix;?></td><td class=price align=right ></td>
	  </tr><tr><td colspan=3>&nbsp;</td></tr></table></td>
      <td >&nbsp;</td>
    </tr>
    <tr>
      <td align=left><table border=0 cellpadding=0 cellspacing=0><tr><td><img src='medias/spirit.gif' width=400 height=1 /></td></tr><tr><td align=center>
	  <img src='../photos/big/<?= $row['photo'];?>.jpg'></td></tr></table></td>
      <td colspan=2 class='descro' align=center><!-- thumbs -->
        <table border=0 cellpadding=5 cellspacing=0 bgcolor="#020935" width=320>
		<tr><td colspan=2><img src='medias/spirit.gif' width=280 height=1 /></td></tr>
        
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
	<tr>
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
	<tr>
      <td colspan=4 align=left>&nbsp;</td>
    </tr>
	<tr>
      <td colspan=4 align=right>
        <!--<table border=0 class="detail" cellspacing=0 cellpadding=5 width=720><tr>
		 <td class=detaila width='100'>surf. habitable </td>
          <td class=detailb>aa</td>
          <td class=detaila width='100'>chambre(s) </td>
          <td class=detailb>aa</td>
          <td class=detaila width='100'>garage(s)</td>
          <td class=detailb>aa</td>
          <td class=detaila width='100'>jardin</td>
          <td class=detailb>aa</td></tr>
		<tr>
		 <td class=detaila>charges </td>
          <td class=detailb>aa</td>
		  <td class=detaila>bureau(x)</td>
		  <td class=detailb>aa</td>
		  <td class=detaila>grenier(s)</td>
		  <td class=detailb>aa</td>
		  <td class=detaila>terrasse</td>
		  <td class=detailb>aa</td></tr>
		<tr>
		 <td class=detaila>ascensseur </td>
          <td class=detailb>aa</td>
		  <td class=detaila>salle(s) d'eau</td>
		  <td class=detailb>aa</td>
		  <td class=detaila>cave(s)</td>
		  <td class=detailb>aa</td>
		  <td class=detaila>cour</td>
		  <td class=detailb>aa</td></tr></table>-->
		  <table border=0 class="detail" cellspacing=0 cellpadding=5 width=720><tr>
		 <td class=detaila>surface</td>
          <td class=detailb><?= $row['area']; ?>&nbsp;m²</td>
		  <td class=detaila>chambre(s)</td>
          <td class=detailb><?= $row['rooms']; ?></td>
		  <td class=detaila>salle(s) d'eau</td>
		  <td class=detailb><?= $row['bathrooms']; ?></td>
		  <td class=detaila>garage(s)</td>
		  <td class=detailb><?= $row['garages']; ?></td>
		  <td class=detaila><?= $row['garden']; ?></td>
		  </tr></table>      </td>
    </tr>
	<tr>
      <td colspan=4 align=left>&nbsp;</td>
    </tr>
    
  </table>
</div>
<?php
	break;
	
	case 1:
		$q="delete from items where num='".$_GET['itemId']."'";
		$r=mysql_query($q) or die(mysql_error());
		$q="select * from photo2item  where item_id='".$_GET['itemId']."'";
		$r=mysql_query($q) or die(mysql_error());
		while($row=mysql_fetch_array($r)) {
			if(file_exists('../photos/big/'.$row['photo'])) {
				unlink('../photos/big/'.$row['photo']);
			}
			if(file_exists('../photos/thumbs/'.$row['photo'])) {
				unlink('../photos/thumbs/'.$row['photo']);
			}
		}
		?>
		Bien supprimé avec succés.
		<?php
	break;
}
?>
