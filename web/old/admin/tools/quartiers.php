<?php





if($_GET['delid']>0) {
	$q="DELETE FROM quartiers WHERE id='".$_GET['delid']."'";
	$r=mysql_query($q) or die(mysql_error());
}





if($_POST['quartier_save']=='1' && $_POST['quartier_modif']<=0) {
	$q="INSERT INTO quartiers (nom_quartier,googlecode,LatLng,zoom) VALUES (\"".$_POST['nom_quartier']."\",\"".addslashes($_POST['googlecode'])."\",'".$_POST['LatLng']."','".$_POST['zoom']."')";
	
	$r=mysql_query($q) or die(mysql_error());
}

if($_POST['quartier_modif']>0) {
	$q="UPDATE quartiers SET nom_quartier=\"".$_POST['nom_quartier']."\", googlecode=\"".addslashes($_POST['googlecode'])."\",LatLng='".$_POST['LatLng']."',zoom='".$_POST['zoom']."' WHERE id='".$_POST['quartier_modif']."'";
	$r=mysql_query($q) or die(mysql_error());
}
if($_GET['editid']>0 ) {
	$q="select * FROM quartiers WHERE id='".$_GET['editid']."' ";
	$r=mysql_query($q) or die(mysql_error());
	//$qu=mysql_fetch_array($r);
	$aQuartier=mysql_fetch_array($r);
	//print_r($aQuartier);
}

$q="select * FROM quartiers ORDER BY nom_quartier ";
$quartiers=mysql_query($q) or die(mysql_error());
?>




</form>
<br /><br />
<table cellpadding="2" cellspacing="0" style="width: 60%; margin-left: auto; margin-right: auto;">
	<tr>
		<td style="background-color: #DFC599;">
<b><?php echo !empty($aQuartier)?'Modifier le quartier':'Ajouter un quartier';?></b>
			<form name="f_quartier" id="f_quartier" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="quartier_save" id="quartier_save" value="">
			<input type="hidden" name="quartier_modif" id="quartier_modif" value="<?php echo $aQuartier['id'];?>">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width: 20%">
						<table width="100%" border="0">
							<tr>
								<td>Nom</td>
								<td colspan="2">
									<input type="text" name="nom_quartier" id="nom_quartier" value="<?php echo $aQuartier['nom_quartier'];?>" />&nbsp;<?php echo $aInvalidFields['nom_quartier'];?></td>
							</tr>
							<tr>
								<td>Lat Lng</td>
								<td colspan="2">
									<input type="text" name="LatLng" id="LatLng" value="<?php echo $aQuartier['LatLng'];?>" />&nbsp;<?php echo $aInvalidFields['LatLng'];?></td>
							</tr>
							<tr>
								<td>Zoom</td>
								<td colspan="2">
									<input type="text" name="zoom" id="zoom" value="<?php echo $aQuartier['zoom'];?>" />&nbsp;<?php echo $aInvalidFields['zoom'];?></td>
							</tr>
						</table>
					</td>
					<td style="width: 50%">
						<table width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td>Code Google:&nbsp;</td>
								<td>
									<textarea class="mceNoEditor" name="googlecode" id="googlecode" style="width: 300px; height: 150px;"><?php echo stripslashes($aQuartier['googlecode']);?></textarea>&nbsp;<?php echo $aInvalidFields['googlecode'];?>
								</td>
							</tr>
						</table>
					</td>
					<td style="width: 30%">
							<?php echo $aQuartier['googlecode'];?>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						&nbsp;<br>&nbsp;<br>
					</td>
				</tr>
				<tr>
					<td colspan="3" style="text-align: center;">
						<input type="button" class="boutonb" value="ENREGISTRER" onClick="document.getElementById('quartier_save').value=1; getLatLng(); this.form.submit();">
					</td>
				</tr>
			</table>
			</form>













		</td>
	</tr>
	
	<tr >
		<td style="text-align: center;">
			<table cellpadding="2" cellspacing="2" style="width: 100%; text-align: left; margin-left: auto; margin-right: auto;">
				<tr class="colHeader">
					<td style="text-decoration: none; background-color: #DFC599;">&nbsp;</td>
					<td onClick="if(document.getElementById('sortdir').value=='DESC'){document.getElementById('sortdir').value='ASC';}else{document.getElementById('sortdir').value='DESC';}document.getElementById('oby').value='nom_quartier';document.getElementById('search_formz').submit();" style="cursor: pointer; background-color: #DFC599; text-align: center;">Nom<?php if($oby=="nom_quartier"){echo("&nbsp;<img src='".MEDIAS_WS."/".strtolower($sortdir).".gif' border='0' />");}?></td>
					<td style="text-decoration: none; background-color: #DFC599;">Lat lang</td>
					<td style="text-decoration: none; background-color: #DFC599;">Zoom</td>
					<!--<td onClick="if(document.getElementById('sortdir').value=='DESC'){document.getElementById('sortdir').value='ASC';}else{document.getElementById('sortdir').value='DESC';}document.getElementById('oby').value='googlecode';document.getElementById('search_formz').submit();" style="cursor: pointer; background-color: #DFC599; text-align: center;">Code Google<?php if($oby=="googlecode"){echo("&nbsp;<img src='".MEDIAS_WS."/".strtolower($sortdir).".gif' border='0' />");}?></td>-->
				  </tr>
				  <?php 

	while($row=mysql_fetch_array($quartiers)) {
				  	
				  	?>
				  <tr class="quartier">
					<td style="background-color: #EFE4D1; text-align: center;">
						<A href="?kind=quartiers&delid=<?php echo $row["id"];?>" OnClick="return confirm('Supprimer le quartier : <?php echo $row["nom_quartier"]?> ?');"><img src="../medias/b_drop.png" border="0" title="Supprimer"></A>&nbsp;<A href="?kind=quartiers&editid=<?php echo $row["id"];?>" ><img src="../medias/b_edit.png" border="0" title="Editer"></a>
					</td>
					<td style="background-color: #EFE4D1; text-align: center;"><?php echo !empty($row["nom_quartier"])?$row["nom_quartier"]:'&nbsp;';?></td>
					<td style="background-color: #EFE4D1; text-align: center;"  ><?php echo $row["LatLng"];?></td>
					
					<td style="background-color: #EFE4D1; text-align: center;"  ><?php echo $row["zoom"];?></td>
					<!--<td style="background-color: #EFE4D1; text-align: center;"><textarea class="mceNoEditor"><?php echo !empty($row["googlecode"])?$row["googlecode"]:'';?></textarea></td>-->
				  </tr>
				  <?php 
				  ; } ?>
			</table>
		</td>
	</tr>
	
</table>








