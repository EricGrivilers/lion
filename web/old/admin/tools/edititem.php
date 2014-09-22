<?php

//stats v2


 $currentMonth=date('n');
	$currentWeek=date('W');
	$currentDay=date('z');
	$currentWDay=date('N');
	$currentYear=date('Y');

	//$fields=array('days'=>366,'weeks'=>52,'months'=>12,'years'=>22,'wdays'=>7);
	function statsv2($row,$field,$current) {
        //print_r($row);
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
		//print_r($tArray);
		return $tArray[$current];

	}

	function statsv2total($row) {
		$tArray=explode(",",$row['months']);
		for($i=0;$i<count($tArray);$i++) {
			$t+=$tArray[$i];
		}


		return $t;

	}




	//end stats

if(!empty($_POST['level'])){
	$level=$_POST['level'];
}elseif(!empty($_GET['level'])){
	$level=$_GET['level'];
}
if(isset($_POST['deleteMe']) && $_POST['deleteMe']=='on') {
	$q="delete  from items where num='".$_POST['num']."'";
	$r=mysql_query($q) or die(mysql_error());
	$q="select * from photo2item  where item_id='".$_POST['num']."'";
		$r=mysql_query($q) or die(mysql_error());
		while($row=mysql_fetch_array($r)) {
			if(file_exists('../photos/big/'.$row['photo'])) {
				unlink('../photos/big/'.$row['photo']);
			}
			if(file_exists('../photos/thumbs/'.$row['photo'])) {
				unlink('../photos/thumbs/'.$row['photo']);
			}
		}
	$level=0;
}
switch($level) {

	default:
	if(!isset($_POST['searchfor'])) {
	$_POST['searchfor']='sale';
	}
	include('tools/search.php');
	break;

	case 1:
		$limit="";
		$anyMoreWhere='';
		$params='';
		//echo ($_POST['searchfor']);
		if($_POST['price']) {

			$priceArray=explode('|',$_POST['price']);
			$min=$priceArray[0];
			$max=$priceArray[1];
			$anyMoreWhere=" and prix>".$min." and prix<=".$max." ";
			$params.="&price=".$_POST['price'];
		}
		if($_POST['type']) {
			$anyMoreWhere.=" and items.type='".$_POST['type']."' ";
			$params.="&type=".$_POST['type'];
		}
		if($_POST['location']) {
			if($_POST['location']=='innercity') {
				$anyMoreWhere.=" and locations.innercity='true' ";
			}
			else if ($_POST['location']=='outercity') {
				$anyMoreWhere.=" and locations.innercity='false' ";
			}
			$params.="&location=".$_POST['location'];
		}
		if(isset($_POST['actif'])) {
		$isactif=" and actif='Y' ";
		}
		if($_POST['searchfor']=='sale') {
		//echo'yes';
			$q="select * from items,locations where items.zip=locations.zip ".$isactif." and location!='Y' ".$anyMoreWhere."  group by items.num order by num asc ".$limit;
		}
		else {
		//echo 'no';
			$q="select * from items,locations where items.zip=locations.zip ".$isactif."and location='Y' ".$anyMoreWhere."  group by items.num order by num asc ".$limit;
		}

if(isset($_POST['reference']) && $_POST['reference']!='030/') {
$q="select * from items where items.reference='".$_POST['reference']."'";
}
//echo $q;
$r=mysql_query($q) or die(mysql_error());
while($row=mysql_fetch_array($r)) {
$tPrix= $row['prix'];
if($tPrix==99999999 || $tPrix<100) {
$tPrix='prix sur demande';
}
else {
$tPrix=makePrice($tPrix)." &euro;";
}
if($row['vendu']=='Y') {
$tPrix='<b>V E N D U / L O U É</b>';
}
?>

<div id='item'>
  <table>
    <tr>
      <td width="200px"></td>
      <td></td>
      <td width="100px"></td>
    </tr>
    <tr>
      <td rowspan=3><a href='javascript:document.theForm.detailId.value=<?php echo  $row['num']; ?>; document.theForm.submit()' class=price><img src='../photos/thumbs/<?php echo  $row['photo'];?>.jpg' class='thumbnail' border='0'/></a></td>
      <td ><h2>
          <?php echo  $row['locfr'];?>
        </h2></td>
      <td><button onclick='document.theForm.detailId.value=<?php echo  $row['num']; ?>; document.theForm.submit()' class=price><u>editer&nbsp;>></button></td>
    </tr>
    <tr>
      <td><?php echo  $tPrix;?></td>
      <td></td>
    </tr>
    <tr>
      <td><?php echo  $row['descrfr'];?></td>
      <td></td>
    </tr>
  </table>
</div>
<?php
}
?>
<input type='hidden' name='level' value='2' />
<input type='hidden' name='detailId' value='' />
<?php
	break;

	case 2:
	if(isset($_GET['itemId'])) {$_POST['detailId']=$_GET['itemId']; }
	$q="select *, (SELECT COUNT(*) FROM photo2item WHERE photo <> '' AND item_id = '" . $_POST['detailId'] . "') AS photos_number from items LEFT JOIN item_statsv2 on item_statsv2.itemId=items.num where num='".$_POST['detailId']."'";

$r=mysql_query($q) or die(mysql_error());
$row=mysql_fetch_array($r);

?>
<input type='hidden' name='level' value='3' />
<input type='hidden' name='num' id='num' value='<?php echo  $_POST['detailId']; ?>' />
<?php //print_r($row); ?>
<div id="stats"> <a href="index.php?kind=stats&action=view&itemId=<?php echo  $_POST['detailId']; ?>&rawstats=<?php echo rawurlencode('Visites hebdomadaires: '.$row['weekview'].' | Visites mensuelles '.$row['totalview'].' | Total visites depuis le 18/12/06: '.$row['totalview']);?>" class="yellow">Statistiques</a><br>
  Visites hebdomadaires: <b>
  <?php echo  statsv2($row,'weeks',$currentWeek); ?>
</b>  | Visites mensuelles
 <b> <?php echo  statsv2($row,'months',$currentMonth); ?>
 </b> | Total visites depuis le 01/04/08:
 <b> <?php echo  statsv2total($row); ?>
 </b>
</div>
<div id='item' >
  <table>
  </table>
  <table width="100%" cellpadding='5'>
    <tr>
      <td class='loc'   style='text-align:right;color:#cccccc;background:#666666'> Suprimer
        <input type='checkbox' name='deleteMe' />
        <input type='button' value='supprimer' onclick="document.theForm.submit();"/></td>
    </tr>
  </table>



  <table width="100%" cellpadding='5' class='edit'>
    <tr>
      <td align=left class='loc'>Vendu / Lou&eacute;
        <input type='checkbox' name='vendu' <?php if($row['vendu']=='Y') { echo ' checked'; } ?>/>
        Option
        <input name='enoption' type='checkbox' <?php if($row['enoption']=='Y') { echo ' checked'; } ?>/></td>
      <td align=left class='loc' >Actif
        <input type='checkbox' name='actif' <?php if($row['actif']=='Y') { echo ' checked'; } ?> onchange="if (this.checked && <?php echo $row['photos_number']; ?> > 0) { document.theForm.mobile_push.disabled = false; document.getElementById('mobile_push_message').style.display = 'none'; } else { document.theForm.mobile_push.disabled = true; document.theForm.mobile_push.checked = false; document.getElementById('mobile_push_message').style.display = ''; }" /></td>

    </tr>
    <tr>
      <td align=left class='loc'>Prix:
        <input name='prix' type='text' id="prix" value='<?php echo  $row['prix'];?>' />
        &euro;&nbsp;&nbsp;
        <input name='surdemande' type=checkbox id="surdemande" <?php if($row['surdemande']=='Y') { echo ' checked'; } ?>/>
        sur demande</td>
      <td align=left class='loc'>Localité:
        <select name='locfr' onchange="document.theForm.zip.value='';document.theForm.city.value='';">
          <option value=''></option>
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
      <td align=left class='loc'>Ancien prix:
        <input name='oldPrix' type='text' id="oldPrix" value='<?php echo  $row['oldPrix'];?>' />
        &euro;</td>
      <td align=left class='loc'></td>

    </tr>
    <tr>
      <td align=left class='loc'>Reference:
        <input name='reference' type='text' id="reference" value="<?php echo  $row['reference']; ?>" /></td>
      <td align=left class=loc>zip
        <!--<input type='text' name='zip' size="4" value='<?php echo  substr($row['locfr'],0,4); ?> '/>
nom
<input type='text' name='city' value='<?php echo  substr($row['locfr'],5,strlen($row['locfr'])); ?> '/> --></td>
    </tr>
    <tr>
      <td align='left' class='loc'>Type:
        <select name='type'>
          <?php
	  	$tq="select * from type order by id";
		$tr=mysql_query($tq) or die(mysql_error());
		while($trow=mysql_fetch_array($tr)) {
		?>
          <option value="<?php echo  $trow['id']; ?>" <?php if($trow['id']==$row['type']) { echo ' selected';}; ?>>
          <?php echo  $trow['type_fr']; ?>
          </option>
          <?php
		}
	  ?>
        </select></td>
      <td align=left>Vente
        <input type=radio name='searchfor' value='sale' <?php if($row['location']!='Y') { echo 'checked'; } ?> />
        Location
        <input type=radio name='searchfor' value='rent' <?php if($row['location']=='Y') { echo 'checked'; } ?>/></td>
    </tr>
    <tr>
      <td>Rue et numéro&nbsp;
        <input type="text" name="name" id="name" value="<?php echo  $row['name'];?>" style="width: 200px;" />
       </td>
        <td rowspan='3'><?php

			require_once('functions.inc.php');
			$sq="SELECT id,nom_quartier FROM quartiers ORDER BY nom_quartier ASC";

			$rq=mysql_query($sq) or die(mysql_error());

			while($rowq=mysql_fetch_array($rq)){
				$aQuartiers[$rowq['id']]=$rowq['nom_quartier'];
			}

			$quartier_id=(!empty($_POST['quartier_id'])?$_POST['quartier_id']:$row['quartier_id']);
			echo('Quartier:&nbsp;'.makeselect('kv','quartier_id',$aQuartiers,$quartier_id,'selectbox',''));

			/*
			if(!empty($quartier_id)){
				$sq="SELECT googlecode FROM quartiers WHERE id=".intval($quartier_id);
				$rq=mysql_query($sq);
				$rowq=mysql_fetch_array($rq);
				$googlecode=$rowq['googlecode'];
				echo('<br />'.$googlecode);
			}*/
			?></td>
      </tr><tr><td>
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
        </td>

    </tr>
    <tr><td>Description<br />
        <textarea id="descrfr" name="descrfr" rows="10" cols="60">
	<?php echo html_entity_decode($row['descrfr']); ?>
</textarea></td></tr>
    <!--<tr>
      <td align=left class='loc'>Description courte:<br />
        <textarea id="descrfr" name="descrfr" rows="10" cols="40">
	<?php echo html_entity_decode($row['descrfr']); ?>
</textarea></td>
      <td align=left class='loc'></td>
        </td>
    </tr>-->
    <tr>
      <td colspan="2" align=left class="loc">Surface
        <input type='text' size='4' name='area' value='<?php echo  $row['area']; ?>'/>
        m² | Chambre(s)
        <select name='rooms'>
          <?php
	for($i=0;$i<30;$i++) {?>
          <option value='<?php echo  $i; ?>' <?php if($row['rooms']==$i) { echo ' selected';} ?>>
          <?php echo  $i; ?>
          </option>
          <?php
	}
	  ?>
        </select>
        | salle(s) d'eau
        <select name='bathrooms'>
          <?php
	for($i=0;$i<30;$i++) {?>
          <option value='<?php echo  $i; ?>' <?php if($row['bathrooms']==$i) { echo ' selected';} ?>>
          <?php echo  $i; ?>
          </option>
          <?php
	}
	  ?>
        </select>
        | garage(s)
        <select name='garages'>
          <?php
	for($i=0;$i<30;$i++) {?>
          <option value='<?php echo  $i; ?>' <?php if($row['garages']==$i) { echo ' selected';} ?>>
          <?php echo  $i; ?>
          </option>
          <?php
	}
	  ?>
        </select>
        | Jardin
        <select name='garden'>
          <option value=''>
          <option value='Jardin' <?php if($row['garden']=='Jardin') {echo ' selected';} ?>>Jardin</option>
          <option value='Cour' <?php if($row['garden']=='Cour') {echo ' selected';} ?>>Cour</option>
          <option value='Terrasse' <?php if($row['garden']=='Terrasse') {echo ' selected';} ?>>Terrasse</option>
        </select></td>
    </tr>
    <tr>
      <td colspan="2" align="left" valign="bottom"><!--Lien Whyse: <input type='text' name='lienwhyse' value="<?php echo  $row['lienwhyse']; ?>"  size='40'/>--></td>
    </tr>
    <tr>
      <td>
        <input<?php if ($row['actif'] != 'Y' || $row['photos_number'] == 0) { echo ' disabled="disabled"'; } ?> id="mobile_push" name="mobile_push" type="checkbox" /><label for="mobile_push" style="margin-left: 3px;">Pusher sur iPhone et iPad</label>
        <div id="mobile_push_message"<?php if ($row['actif'] == 'Y' && $row['photos_number'] > 0) { echo ' style="display: none;"'; } ?>>L'annonce doit être active et comporter au moins une photo pour utiliser cette fonctionnalité.</div>
    </td>
    </tr>
    <tr>
      <td colspan="4" align="left" valign="bottom"><?php echo  $row['datein']."  --  ".$row['update']; ?></td>
    </tr>
    <tr>
      <td colspan=4 class=price align='center'><input type='submit' value='METTRE À JOUR' /></td>
    </tr>
    <tr>
      <td colspan="5" style='background:#666666'><ul id='thumbs'>
          <?php
				$ranks=array();
				$q="SELECT photo2item.*,items.photo as defPict FROM items LEFT JOIN photo2item ON items.num=photo2item.item_id WHERE num='".$_POST['detailId']."' ORDER BY ranking ASC";

				$r=mysql_query($q) or die(mysql_error());
				$i=1;
				$picts=array();
				require_once(__lib__."core/clsImageManip.inc.php");
				while($p=mysql_fetch_array($r)) {
			//	echo $p['defPict'].".jpg";
					if(file_exists(__root__."photos/big/".$p['defPict'].".jpg")) {
						$picts[$p['defPict'].".jpg"]=$p['defPict'].".jpg";
					}
					if($p['photo']!='' && file_exists(__root__."photos/big/".$p['photo'])) {
					$picts[$p['photo']]=$p['photo'];
					}

				}

				//print_r($picts);

				foreach($picts as $p) {
				$tp=strtolower($p);
					if(!file_exists(__root__."photos/thumbs/".$tp)) {
						$im=new imageManip;
						$im->load(__root__."photos/big/".$p);
						$im->resizeToFit(185,138);
						$im->save(__root__."photos/thumbs/".$tp);
					}
					echo "<li><img src='/photos/thumbs/".$tp."' /><br/>";
					//echo $p."<br/>";
					if($i>1) {
						echo "<input type='checkbox' name=\"del['".$i."']\" value='".$tp."' />delete ".$tp;
					}
					else {
						echo "".$tp;
					}
					echo "</li>";
					$ranks[]=$tp;
					$i++;
				}

			?>
        </ul>
        <input type='hidden' name='ranks' id='ranks' value='<?php echo implode(",",$ranks); ?>' />
        <br class='clearfloat' />
        <input id="fileInput" name="fileInput" type="file" /></td>
    </tr>
    <tr>
      <td colspan=4 class=price align='center'><input type='submit' value='METTRE À JOUR' /></td>
    </tr>
  </table>
  </br>
  <br />
</div>
<?php
	break;

	case 3:


		/*
		if(isset($_POST['zip']) && isset($_POST['city'])) {

			$tq="select * from locations where zip='".$_POST['zip']."'";
			$tr=mysql_query($tq) or die(mysql_error());
			if(mysql_affected_rows($Connect)==0) {
				$tq="insert into locations (zip,fr) values ('".$_POST['zip']."','".$_POST['city']."')";
				$tr=mysql_query($tq) or die(mysql_error());
				$_POST['locfr']=$_POST['zip']." ".$_POST['city'];
			}
		}*/
		$zip=substr($_POST['locfr'],0,4);
		if($_POST['searchfor']=='rent') {$searchfor='Y'; }
		else {$searchfor='';}
		if(isset($_POST['surdemande'])) {$surdemande='Y';}
		if(isset($_POST['vendu'])) {$vendu='Y';}
		if(isset($_POST['enoption'])) {$isenoption='Y';}
		if(isset($_POST['actif'])) {$actif='Y';}
		if(isset($_POST['viewable'])) {$viewable='checked';}
		if(isset($_POST['oldPrix'])) {
			$delta=abs($_POST['oldPrix']-$_POST['prix']);
			if($delta>=$_POST['prix']/20) {
				$update="`update`='".date('Ymd')."',";
			}
		}
		$q="UPDATE `items` SET
			name=\"".$_POST['name']."\",
			prix='".$_POST['prix']."',
			oldPrix='".$_POST['oldPrix']."',
			".$update."
			locfr='".$_POST['locfr']."',
			quartier_id='".$_POST['quartier_id']."',
			type='".$_POST['type']."',
			location='".$searchfor."',
			actif='".$actif."',
			reference='".$_POST['reference']."',
			surdemande='".$surdemande."',
			vendu='".$vendu."',
			zip='".$zip."',
			enoption='".$isenoption."',
			area='".$_POST['area']."',
			zone='".$_POST['zone']."',
			rooms='".$_POST['rooms']."',
			bathrooms='".$_POST['bathrooms']."',
			garages='".$_POST['garages']."',
			viewable='".$viewable."',
			garden='".$_POST['garden']."',
			`descrfr` = \"".mysql_real_escape_string($_POST['descrfr'])."\",
			`descren` = \"".$_POST['descren']."\",
			`moredescrfr` = \"".$_POST['moredescrfr']."\"
			 WHERE `num` ='".$_POST['num']."'";
			$r=mysql_query($q) or die(mysql_error());
			//echo($q);

			foreach($_POST['del'] as $k=>$v) {
				$q="DELETE FROM photo2item WHERE item_id='".$_POST['num']."' and photo='".$v."' ";
				$r=mysql_query($q) or die(mysql_error());
				unlink(__root__."photos/big/".$v);
				unlink(__root__."photos/thumbs/".$v);

			}
			$rank=1;
			$tr=explode(",",$_POST['ranks']);

			foreach($tr as $r) {
				$q="INSERT INTO photo2item (item_id,ranking,photo) VALUES ('".$_POST['num']."','".$rank."','".$r."') ON DUPLICATE KEY UPDATE ranking='".$rank."' ";
				$r=mysql_query($q) or die(mysql_error());
				//echo $q."<br/>";
				$rank++;
			}

			$tf=__root__."photos/big/".$tr[0];
			$path_parts = pathinfo($tf);
			$q="UPDATE items SET photo='".$path_parts['filename']."' WHERE num='".$_POST['num']."' ";
			$q="UPDATE items SET photo='".preg_replace("/\.jpg/","",$tr[0])."' WHERE num='".$_POST['num']."' ";
		//	$tr[0]
			//echo $q;
			$r=mysql_query($q) or die(mysql_error());




			$q = 'SELECT COUNT(*) AS photos_number FROM photo2item WHERE photo <> \'\' AND item_id = \'' . $_POST['num'] . '\'';
			$r = mysql_query($q) or die(mysql_error());
			$row = mysql_fetch_array($r);

			if (isset($_POST['mobile_push']) && $actif == 'Y' && $row['photos_number'] > 0)
			{
				include('../mobile/mobile_push.class.php');
				try
				{
					$mobile_push = new MobilePush();
					$mobile_push->sendNotifications($_POST['num']);
				}
				catch (Exception $e)
				{
					echo $e->getMessage();
				}
			}

			echo("<script>window.location='index.php?kind=item&action=edit&level=2&itemId=".$_POST['num']."&detailId=".$_POST['num']."';</script>");


	break;
}
?>
