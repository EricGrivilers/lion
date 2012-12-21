<?php
ini_set('display_errors',1);






if($_GET['delid']>0) {
	$q="DELETE FROM quartiers WHERE id='".$_GET['delid']."'";
	$r=mysql_query($q) or die(mysql_error());
}




if($_GET['editid']>0 ) {
	$q="select * FROM quartiers WHERE id='".$_GET['editid']."' ";
	$r=mysql_query($q) or die(mysql_error());
	//$qu=mysql_fetch_array($r);
	$aQuartier=mysql_fetch_array($r);
	//print_r($aQuartier);
}
if($_POST['quartier_save']=='1' && $_POST['quartier_modif']<=0) {
	$q="INSERT INTO quartiers (nom_quartier,googlecode) VALUES (\"".$_POST['nom_quartier']."\",\"".addslashes($_POST['googlecode'])."\")";
	
	$r=mysql_query($q) or die(mysql_error());
}

if($_POST['quartier_modif']>0) {
	$q="UPDATE quartiers SET nom_quartier=\"".$_POST['nom_quartier']."\", googlecode=\"".addslashes($_POST['googlecode'])."\" WHERE id='".$_POST['quartier_modif']."'";
	$r=mysql_query($q) or die(mysql_error());
}


$q="select * FROM quartiers ORDER BY nom_quartier ";
$quartiers=mysql_query($q) or die(mysql_error());
?>




</form>
<br /><br />
<table cellpadding="2" cellspacing="0" style="width: 60%; margin-left: auto; margin-right: auto;">
	<tr>
		<td style="background-color: #DFC599;">
<b><?=!empty($aQuartier)?'Modifier le quartier':'Ajouter un quartier';?></b>
			<form name="f_quartier" id="f_quartier" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="quartier_save" id="quartier_save" value="">
			<input type="hidden" name="quartier_modif" id="quartier_modif" value="<?=$aQuartier['id'];?>">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width: 50%">
						<table width="100%" border="0">
							<tr>
								<td>Nom</td>
								<td colspan="2">
									<input type="text" name="nom_quartier" id="nom_quartier" value="<?=$aQuartier['nom_quartier'];?>" />&nbsp;<?=$aInvalidFields['nom_quartier'];?></td>
							</tr>
						</table>
					</td>
					<td style="width: 50%">
						<table width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td>Code Google:&nbsp;</td>
								<td>
									<textarea class="mceNoEditor" name="googlecode" id="googlecode" style="width: 300px; height: 150px;"><?=stripslashes($aQuartier['googlecode']);?></textarea>&nbsp;<?=$aInvalidFields['googlecode'];?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						&nbsp;<br>&nbsp;<br>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: center;">
						<input type="button" class="boutonb" value="ENREGISTRER" onClick="document.getElementById('quartier_save').value=1; this.form.submit();">
					</td>
				</tr>
			</table>
			</form>













		</td>
	</tr>
	
	<tr>
		<td style="text-align: center;">
			<table cellpadding="2" cellspacing="2" style="width: 100%; text-align: left; margin-left: auto; margin-right: auto;">
				<tr class="colHeader">
					<td style="text-decoration: none; background-color: #DFC599;">&nbsp;</td>
					<td onClick="if(document.getElementById('sortdir').value=='DESC'){document.getElementById('sortdir').value='ASC';}else{document.getElementById('sortdir').value='DESC';}document.getElementById('oby').value='nom_quartier';document.getElementById('search_formz').submit();" style="cursor: pointer; background-color: #DFC599; text-align: center;">Nom<?php if($oby=="nom_quartier"){echo("&nbsp;<img src='".MEDIAS_WS."/".strtolower($sortdir).".gif' border='0' />");}?></td>
					<td onClick="if(document.getElementById('sortdir').value=='DESC'){document.getElementById('sortdir').value='ASC';}else{document.getElementById('sortdir').value='DESC';}document.getElementById('oby').value='googlecode';document.getElementById('search_formz').submit();" style="cursor: pointer; background-color: #DFC599; text-align: center;">Code Google<?php if($oby=="googlecode"){echo("&nbsp;<img src='".MEDIAS_WS."/".strtolower($sortdir).".gif' border='0' />");}?></td>
				  </tr>
				  <?php 

	while($row=mysql_fetch_array($quartiers)) {
				  	
				  	?>
				  <tr>
					<td style="background-color: #EFE4D1; text-align: center;">
						<A href="?kind=quartiers&delid=<?=$row["id"];?>" OnClick="return confirm('Supprimer le quartier : <?=$row["nom_quartier"]?> ?');"><img src="../medias/b_drop.png" border="0" title="Supprimer"></A>&nbsp;<A href="?kind=quartiers&editid=<?=$row["id"];?>" ><img src="../medias/b_edit.png" border="0" title="Editer"></a>
					</td>
					<td style="background-color: #EFE4D1; text-align: center;"><?=!empty($row["nom_quartier"])?$row["nom_quartier"]:'&nbsp;';?></td>
					<td style="background-color: #EFE4D1; text-align: center;"><?=!empty($row["googlecode"])?$row["googlecode"]:'&nbsp;';?></td>
				  </tr>
				  <? } ?>
			</table>
		</td>
	</tr>
	
</table>