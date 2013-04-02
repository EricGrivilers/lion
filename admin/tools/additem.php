<?php
if(!empty($_POST['level'])){
	$level=$_POST['level'];
}elseif(!empty($_GET['level'])){
	$level=$_GET['level'];
}

switch($level) {

	default:
	
	$q="select * from items where num='".$_POST['detailId']."'";

$r=mysql_query($q) or die(mysql_error());
$row=mysql_fetch_array($r);

?>
<input type='hidden' name='level' value='1' />
<div id='item' align=right>
  <table width="100%" cellpadding='5' class='edit'>
    
    <tr>
      
      <td align=left class='loc'>
        Option 
        <input name='enoption' type='checkbox' <?php if($row['enoption']=='Y') { echo ' checked'; } ?>/></td>
      <td align=left class='loc'>Actif <input type='checkbox' name='actif' <?php if($row['actif']=='Y') { echo ' checked'; } ?>/></td>
     
    </tr>
    
	
    <tr>
      <td align=left class='loc'>Prix:
        <input name='prix' type='text' id="prix"  />
        &euro;&nbsp;&nbsp;<input name='surdemande' type=checkbox id="surdemande" <?php if($row['surdemande']=='Y') { echo ' checked'; } ?>/> sur demande</td>
      <td align=left class='loc'>Localité:
        <select name='locfr'><option value=''></option>
          <?php
	  $tq="select * from locations order by zip";
	  $tr=mysql_query($tq) or die(mysql_error());
	  while($trow=mysql_fetch_array($tr)) {
	  ?>
          <option value="<?php echo  $trow['zip']." ".$trow['fr']; ?>" <?php if($row['locfr']==$trow['zip']." ".$trow['fr']) { echo 'selected'; } ?>>
            <?php echo  $trow['zip']." - ".$trow['fr']; ?>
          </option>
          <?php
	  }
	  ?>
        </select></td>
      
    </tr>
    <tr>
      <td align=left class='loc'>Reference:
      <input name='reference' type='text' id="reference" value="030/" /></td>
      <td align=left class=loc></td>
    </tr>
	<tr>
      
      <td align='left' class='loc'>Type: <select name='type'><option value='1'>Maison</option><option value='2'>Appartement</option><option value='3'>Terrain</option></select></td>
      <td align=left>Vente <input type=radio name=searchfor value='sale' <?php if($row['location']!='Y') { echo 'checked'; } ?>/> Location <input type=radio name=searchfor value='rent' <?php if($row['location']=='Y') { echo 'checked'; } ?>/></td>
	</tr>
	<tr>
		<td>Rue et numéro&nbsp;<input type="text" name="name" id="name" value="<?php echo  $row['name'];?>" style="width: 200px;" />
		</td>
		<td>
			<?php
			require_once('functions.inc.php');
			$sq="SELECT id,nom_quartier FROM quartiers ORDER BY nom_quartier ASC";
			$rq=mysql_query($sq) or die(mysql_error());
			while($rowq=mysql_fetch_array($rq)){
				$aQuartiers[$rowq['id']]=$rowq['nom_quartier'];
			}
			$quartier_id=(!empty($_POST['quartier_id'])?$_POST['quartier_id']:$row['quartier_id']);
			echo('Quartier:&nbsp;'.makeselect('kv','quartier_id',$aQuartiers,$quartier_id,'selectbox',''));

			/*if(!empty($quartier_id)){
				$sq="SELECT googlecode FROM quartiers WHERE id=".intval($quartier_id);
				$rq=mysql_query($sq);
				$row=mysql_fetch_array($rq);
				$googlecode=$row['googlecode'];
				echo('<br />'.$googlecode);
			}*/
			?>	
		</td>
	</tr>
    <tr><td>
        Zone de recherche
        <select name='zone'>
          <option></option>
          <?php 
		function options($def) {
	$zones=array(1=>"BRUXELLES SUD ET CENTRE",2=>"BRUXELLES EST",3=>"PERIPHERIE BRUXELLOISE",4=>"PROVINCE");
	foreach($zones as $k=>$v) {
		$out.="<option value='".$k."' ";
		if($def==$k) {
			$out.=" selected ";	
		}
		$out.=">".$v."</option>";	
	}
	return $out;
}
echo options($row['zone']);?>
        </select>
        </td><td></td>
     
    </tr>
	
    <tr>
      <td align=left class='loc'>Description:<br /><textarea id="descrfr" name="descrfr" rows="10" cols="60">
	<?php echo html_entity_decode($row['descrfr']); ?>
</textarea>      </td>
      <td align=left class='loc'></td>
    </tr>
	
    <tr>
      <td colspan="2" align=left class="loc">Surface <input type='text' size='4' name='area' /> m² | Chambre(s) <select name='rooms'><?php
	for($i=1;$i<15;$i++) {?>
	<option value='<?php echo  $i; ?>'><?php echo  $i; ?></option>
	<?php
	}  
	  ?>
	  </select> | salle(s) d'eau <select name='bathrooms'><?php
	for($i=1;$i<15;$i++) {?>
	<option value='<?php echo  $i; ?>'><?php echo  $i; ?></option>
	<?php
	}  
	  ?>
	  </select> | garage(s) <select name='garages'><?php
	for($i=0;$i<15;$i++) {?>
	<option value='<?php echo  $i; ?>'><?php echo  $i; ?></option>
	<?php
	}  
	  ?>
	  </select> | Jardin <select name='garden'><option value=''><option value='Jardin'>Jardin</option><option value='Cour'>Cour</option><option value='Terrasse'>Terrasse</option></select></td>
    </tr>
	 
	 
    <tr>
      <td colspan="2" align="left" valign="bottom"><!--Lien Whyse: <input type='text' name='lienwhyse' value="<?php echo  $row['lienwhyse']; ?>"  size='40'/>--></td>
    </tr>
    

    
  
    <tr>
      <td colspan=4 class=price align='center'><input type='submit' value='AJOUTER' />&nbsp;(les photos peuvent être insérées à l'écran suivant)</td>
    </tr>
  </table>
  </br>
  <br />
</div>
<?php
	break;
	
	case 1:
	/*
		if(isset($_POST['zip']) && isset($_POST['city'])) {
		echo 'yesssssssssssssss';
		$tq="select * from locations where zip='".$_POST['zip']."'";
		$tr=mysql_query($tq) or die(mysql_error());
		if(mysql_affected_rows($Connect)==0) {
			$tq="insert into locations (zip,fr) values ('".$_POST['zip']."','".$_POST['city']."')";
			$tr=mysql_query($tq) or die(mysql_error());
			$_POST['locfr']=$_POST['zip']." ".$_POST['city'];
			}
		}
		*/
		$zip=substr($_POST['locfr'],0,4);
		$tprix=$_POST['prix'];
		if(isset($_POST['actif'])) {$actif='Y';}
		if(isset($_POST['surdemande'])) {$surdemande='Y';}
		if(isset($_POST['enoption'])) {$isenoption='Y';}
		if(isset($_POST['viewable'])) {$viewable='checked';}
		if($_POST['searchfor']=='rent') {$location='Y';}
		else {$location='';}
		$q="INSERT INTO `items` ( 
		`name`, 
		`datein` , 
		`update`, 
		`prix` , 
		`locfr` , 
		`quartier_id`, 
		`zone`, 
		`shortdescrfr` , 
		`shortdescren`, 
		`descrfr` , 
		`descren` , 
		`surdemande`  , 
		`actif` , 
		`location` , 
		`reference` , 
		`enoption` , 
		`type` , 
		`zip` , 
		`moredescrfr` , 
		`public`, 
		`area`, 
		`rooms`, 
		`bathrooms`, 
		`garages`, 
		`garden`, 
		`viewable` ) 
VALUES ( 
\"".$_POST['name']."\", 
'".date('Y-m-d')."' , 
'".date('Y-m-d')."' ,
'".$tprix."' , 
'".$_POST['locfr']."' , 
'".$_POST['quartier_id']."', 
\"".$_POST['zone']."\",
 \"".$_POST['shortdescrfr']."\" , 
 \"".$_POST['shortdescren']."\", 
 \"".mysql_real_escape_string($_POST['descrfr'])."\" , 
 \"".$_POST['descren']."\" ,
 '".$surdemande."'  , 
 '".$actif."' , 
 '".$location."' , 
 '".$_POST['reference']."' , 
 '".$isenoption."' , 
 '".$_POST['type']."', 
 '".$zip."', 
 \"".$_POST['moredescrfr']."\", 
 'checked'
, '".$_POST['area']."', 
'".$_POST['rooms']."', 
'".$_POST['bathrooms']."', 
'".$_POST['garages']."', 
'".$_POST['garden']."',
'".$viewable."')";

		$r=mysql_query($q) or die(mysql_error());
		
		$t=array('','','a','b','c');
			for($i=1;$i<=4;$i++) {
				if(file_exists($_FILES['pict'.$i]['tmp_name'])) {
					$url=$_FILES['pict'.$i]['tmp_name'];
					$thumburl='../photos/npict'.$_POST['num'].$t[$i].".jpg";
					makeBig($url,$thumburl);
					if($i==1) {
						$tq="update items set photo='npict".$_POST['num'].$t[$i-1]."' where num='".$_POST['num']."'";
						$tr=mysql_query($tq) or die(mysql_error());
						$thumburl='../photos/npict'.$_POST['num'].$t[$i]."s.jpg";
						makeThumb($url,$thumburl);
					}		
				}
			}

			$itemId=mysql_insert_id();
			
			echo("<script>window.location='index.php?kind=item&action=edit&level=2&itemId=".$itemId."&detailId=".$itemId."';</script>");
	break;
}
?>
