<?php


if(!empty($aVars['delid'])){
	$DBManager->TblSlideshow->DeleteItem('id='.intval($aVars['delid']));
}



$errorMsg["invalidfields"]=0;
unset($aInvalidFields);

//photo SAVE
if(!empty($aVars['photo_save']) && $aVars['photo_save']==1){

	$aExtraCond = array();

	if(!empty($aVars['ventes']) && ctype_digit($aVars['ventes'])){
		$params['item_id']=intval($aVars['ventes']);
	}elseif(!empty($aVars['locs']) && ctype_digit($aVars['locs'])){
		$params['item_id']=intval($aVars['locs']);
	}elseif(!empty($aVars['newz']) && ctype_digit($aVars['newz'])){
		$params['item_id']=intval($aVars['newz']);
	}else{
		$errorMsg['invalidfields']=1;
		$aInvalidFields['select']='erreur';
	}

	

	if(!empty($aVars['ranking'])){
		$params['ranking']=$aVars['ranking'];
	}else{
		$params['ranking']=1;
	}

	

	if($errorMsg['invalidfields']==0){
		if(!empty($aVars['photo_modif'])){
			$params['id']=$aVars['photo_modif'];
		}
		//print_r($params);
		$newId=$DBManager->TblSlideshow->Save($params);
		
		!empty($newId)?$theId=$newId:$theId=$aVars['photo_modif'];
		//print_r($params);
		//header("Location:".ROOT_WS."admin/index.php?kind=images&todo=slide");
		echo("<script>window.location='".ROOT_WS."/admin/index.php?kind=images&todo=slide';</script>");
		//header("Location:".ADMIN_PROTOCOL."://www.draym.net/admin/site/portfolio_edit.php?portfolioid=$portfolioid");
	}else{
		print_r($aInvalidFields);
	}
}




















if(!empty($aVars['editid'])){
	$r=$DBManager->slideshow->Find('','slideshow.id='.intval($aVars['editid']));
	$aPhoto=$r->fetchRow();
	if( strtotime($aPhoto['datein']) >= mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")) ){
		$itemtype='newz';
	}elseif($aPhoto['location']=='Y'){
		$itemtype='locs';
	}else{
		$itemtype='ventes';
	}
}

$oModel = $DBManager->slideshow;
$dtl = new DataList($oModel,array());

if(isset($aVars["oby"])){$dtl->SortField=$oby=$aVars["oby"];}else{$dtl->SortField=$oby="ranking";}
if(isset($aVars["sortdir"])){$dtl->SortDir=$sortdir=$aVars["sortdir"];}else{$dtl->sortDir=$sortdir="ASC";}


if(!empty($aVars['viewby'])){
	if($aVars['viewby']=='all'){
		$dtl->NoLimit = true;
	}else{
		$dtl->PageSize=$aVars['viewby'];
	}
}else{
	$dtl->PageSize=20;
}

//$oby=$dtl->SortField;
//$sortdir=$dtl->sortDir;
$viewby=$dtl->PageSize;

$groupBy="id";

//getRows( $aListenFields=array() , $aSelectFields='' , $aExtraCond='' , $aHaving='' , $groupBy='' , $countGroupBy="", $aExcludeKeys="" , $aExcludeValues="" ) 
$aRows = $dtl->getRows('','',$aExtraCond,'',$groupBy);

$NavBarParams=$dtl->NavBarParams;
//print_r($NavBarParams);
//print_r($aRows);


$rsItemsLoc=$DBManager->TblItems->Find('',"location='Y' AND datein<'".(date('Y-m-d',mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"))))."'",'locfr');
$aItemsLoc=$rsItemsLoc->fetchAll();

$rsItemsVente=$DBManager->TblItems->Find('',"location!='Y' AND datein<'".(date('Y-m-d',mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"))))."'",'locfr');
$aItemsVente=$rsItemsVente->fetchAll();

$rsItemsNew=$DBManager->TblItems->Find('',"datein>='".(date('Y-m-d',mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"))))."'",'locfr');
$aItemsNew=$rsItemsNew->fetchAll();


foreach($aItemsNew as $k=>$v){
	if($itemtype=='newz' && $aPhoto['num']==$v['num']){$sel=' selected';}else{$sel='';}
	$seloptsNew.='<option '.$sel.' value="'.$v['num'].'" onMouseOver=\'document.getElementById("photodiv").innerHTML="<img src='.PICTURES_WS.'/thumbs/'.$v['photo'].'.jpg />";\'>'.$v['locfr'].' - '.$v['datein'].' - '.substr(trim($v['descrfr'],"\t"),0,90).' ...</span></option>';
}

foreach($aItemsVente as $k=>$v){
	if($itemtype=='ventes' && $aPhoto['num']==$v['num']){$sel=' selected';}else{$sel='';}
	$seloptsVente.='<option '.$sel.' value="'.$v['num'].'" onMouseOver=\'document.getElementById("photodiv").innerHTML="<img src='.PICTURES_WS.'/thumbs/'.$v['photo'].'.jpg />";\'>'.$v['locfr'].' - '.$v['datein'].' - '.substr(trim($v['descrfr'],"\t"),0,90).' ...</span></option>';
}

foreach($aItemsLoc as $k=>$v){
	if($itemtype=='locs' && $aPhoto['num']==$v['num']){$sel=' selected';}else{$sel='';}
	$seloptsLoc.='<option '.$sel.' value="'.$v['num'].'" onMouseOver=\'document.getElementById("photodiv").innerHTML="<img src='.PICTURES_WS.'/thumbs/'.$v['photo'].'.jpg />";\'>'.$v['locfr'].' - '.$v['datein'].' - '.substr(trim($v['descrfr'],"\t"),0,90).' ...</span></option>';
}



?>
</form>
<br /><br />
<table cellpadding="2" cellspacing="0" style="width: 60%; margin-left: auto; margin-right: auto;">
	<tr>
		<td style="background-color: #DFC599;">
<b><?=!empty($aPhoto)?'Modifier le media':'Ajouter un media';?></b>
			<form name="f_photo" id="f_photo" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="photo_save" id="photo_save" value="">
			<input type="hidden" name="photo_modif" id="photo_modif" value="<?=$aPhoto['id'];?>">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width: 70%">
						<table width="100%" border="0">
							<tr>
								<td>Numéro d'ordre</td>
								<td colspan="2" style="text-align: left;">
									<?php
									$arr=array('0','1','2','3','4','5','6','7','8','9');
									for($i=10; $i<1001; $i++ ){
										$arr[]=$i;
									}
									echo(makeselect('vv','ranking',$arr,(!empty($aVars['ranking'])?$aVars['ranking']:$aPhoto['ranking']),'selectbox',''));
									?>
									&nbsp;<?=$aInvalidFields['ranking'];?></td>
							</tr>
						</table>
					</td>
					<td style="width: 30%">
						<div name="photodiv" id="photodiv" style="height: 150px;">
							<? if(!empty($aPhoto) && is_array($aPhoto)){ ?>
							<img src="<?=PICTURES_WS.'/thumbs/'.$aPhoto['photo'].'.jpg';?>">
							<? } ?>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<b>CHOISIR PARMIS:</b>&nbsp;<?=$aInvalidFields['select'];?><br />
						Ventes:<br />
						<select name="ventes" id="ventes" style="width: 550px;" onClick="if(this.value!=''){document.getElementById('locs').selectedIndex=0;document.getElementById('newz').selectedIndex=0;}" onBlur="if(this.value==''){document.getElementById('photodiv').innerHTML='';}">
							<option onMouseOver="document.getElementById('photodiv').innerHTML='';" ></option>
							<?=$seloptsVente;?>
						</select><br />
						ou Locations:<br />
						<select name="locs" id="locs" style="width: 550px;" onClick="if(this.value!=''){document.getElementById('ventes').selectedIndex=0;document.getElementById('newz').selectedIndex=0;}" onBlur="if(this.value==''){document.getElementById('photodiv').innerHTML='';}">
							<option onMouseOver="document.getElementById('photodiv').innerHTML='';" ></option>
							<?=$seloptsLoc;?>
						</select><br />
						ou Nouveautés:<br />
						<select name="newz" id="newz" style="width: 550px;" onClick="if(this.value!=''){document.getElementById('locs').selectedIndex=0;document.getElementById('ventes').selectedIndex=0;}" onBlur="if(this.value==''){document.getElementById('photodiv').innerHTML='';}">
							<option onMouseOver="document.getElementById('photodiv').innerHTML='';" ></option>
							<?=$seloptsNew;?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						&nbsp;<br>&nbsp;<br>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: center;">
						<input type="button" class="boutonb" value="ENREGISTRER" onClick="document.getElementById('photo_save').value=1; this.form.submit();">
					</td>
				</tr>
			</table>
			</form>













		</td>
	</tr>
	<tr>
		<td>
			<form name="search_formz" id="search_formz" method="post">
			<input type="hidden" name="oby" id="oby" value="<?php if(isset($oby)){echo $oby;}else{$oby="ranking";echo "ranking";}?>">
			<input type="hidden" name="sortdir" id="sortdir" value="<?php if(isset($sortdir)){echo($sortdir);}else{$sortdir="ASC";echo("ASC");} ?>">
			
			<div style="background-color: #AF9B78; padding: 3px;">
				<? if ($NavBarParams["pagenum"]> 0) { ?>
				<a href="?<?=$NavBarParams["first_link"]?>" ><<</a>&nbsp;
				<? } ?>

				<? if ($NavBarParams["pagenum"] > 0) { ?>
				<a href="?<?=$NavBarParams["previous_link"]?>"><</a>&nbsp;
				<? } ?>

				Page&nbsp;<?=$NavBarParams["pagenum"]+1?>/<?=$NavBarParams["totalpages"]?>&nbsp;(<?=$NavBarParams["totalrows"]?>&nbsp;Résultats&nbsp;trouvés)&nbsp;

				<? if ($NavBarParams["pagenum"] < $NavBarParams["totalpages"] -1 ) { ?>            
				<a href="?<?=$NavBarParams["next_link"]?>">></a>&nbsp;
				<? } ?>

				<? if ($NavBarParams["pagenum"] < $NavBarParams["totalpages"]-1) { ?>            
				<a href="?<?=$NavBarParams["last_link"]?>">>></a>
				<? } ?>
				&nbsp;&nbsp;&nbsp;Visualiser&nbsp;par:&nbsp;
				<?php
				if (isset($aRows)) {
					$arr=array('1'=>'1','5'=>'5','10'=>'10','20'=>'20','30'=>'30','40'=>'40','50'=>'50','100'=>'100','150'=>'150','200'=>'200','500'=>'500','all'=>$lang_Conf['all']);
					echo(makeselect('kv','viewby',$arr,$aVars['viewby'],'selectbox','onChange="javascript: window.location=\''.$_SERVER['PHP_SELF'].'?'.$NavBarParams["viewby_link"].'&viewby=\' + this.value;"'));
				}
				?>
			</div>
		</td>
	</tr>
	<tr>
		<td style="text-align: center;">
			<table cellpadding="2" cellspacing="2" style="width: 100%; text-align: left; margin-left: auto; margin-right: auto;">
				<tr class="colHeader">
					<td style="text-decoration: none; background-color: #DFC599;">&nbsp;</td>
					<td onClick="if(document.getElementById('sortdir').value=='DESC'){document.getElementById('sortdir').value='ASC';}else{document.getElementById('sortdir').value='DESC';}document.getElementById('oby').value='ranking';document.getElementById('search_formz').submit();" style="cursor: pointer; background-color: #DFC599; text-align: center;">Ordre<?php if($oby=="ranking"){echo("&nbsp;<img src='".MEDIAS_WS."/".strtolower($sortdir).".gif' border='0' />");}?></td>
					<td onClick="if(document.getElementById('sortdir').value=='DESC'){document.getElementById('sortdir').value='ASC';}else{document.getElementById('sortdir').value='DESC';}document.getElementById('oby').value='photo';document.getElementById('search_formz').submit();" style="cursor: pointer; background-color: #DFC599; text-align: center;">Type de bien<?php if($oby=="photo"){echo("&nbsp;<img src='".MEDIAS_WS."/".strtolower($sortdir).".gif' border='0' />");}?></td>
					<td onClick="if(document.getElementById('sortdir').value=='DESC'){document.getElementById('sortdir').value='ASC';}else{document.getElementById('sortdir').value='DESC';}document.getElementById('oby').value='locfr';document.getElementById('search_formz').submit();" style="cursor: pointer; background-color: #DFC599; text-align: center;">Bien<?php if($oby=="locfr"){echo("&nbsp;<img src='".MEDIAS_WS."/".strtolower($sortdir).".gif' border='0' />");}?></td>
					<td style="text-align: center; background-color: #DFC599; text-align: center;">Photo</td>
				  </tr>
				  <? foreach($aRows as $row) { ?>
				  <tr>
					<td style="background-color: #EFE4D1; text-align: center;"><A href="?kind=images&todo=slide&delid=<?=$row["id"];?>" OnClick="return confirm('Supprimer la photo : <?=$row["photo"]?> ?');"><img src="<?=MEDIAS_WS;?>/b_drop.png" border="0" title="Supprimer"></A>&nbsp;<A href="?kind=images&todo=slide&editid=<?=$row["id"];?>" ><img src="<?=MEDIAS_WS;?>/b_edit.png" border="0" title="<?=$lang_Conf["editgroup"];?>"></a>
					</td>
					<td style="background-color: #EFE4D1; text-align: center;"><?=!empty($row["ranking"])?$row["ranking"]:'&nbsp;';?></td>
					<td style="background-color: #EFE4D1; text-align: center;">
						<?php
						if( strtotime($row['datein']) >= mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")) ){
							echo('Nouveauté');
						}elseif($row['location']=='Y'){
							echo('Location');
						}else{
							echo('Vente');
						}
						
						?>
					</td>
					<td style="background-color: #EFE4D1; text-align: center;"><?=$row['locfr'].'<br />'.$row['descrfr'];?></td>
					<td style="background-color: #EFE4D1; text-align: center;">

						<?php
							if(is_file(PICTURES_FS.PATH_DELIM.'thumbs'.PATH_DELIM.$row['photo'].'.jpg')){

							list($width, $height, $type, $attr) = getimagesize(PICTURES_FS.PATH_DELIM.'thumbs'.PATH_DELIM.$row['photo'].'.jpg');
								if($width>170){
									$height=round((($height/$width)*170),0);
									$width=170;
								}
								echo('<img src="'.PICTURES_WS . '/thumbs/' . $row['photo'].'.jpg'.'" style="width: '.$width.'px; height: '.$height.'px; border: none;">');
							}
						?>
					</td>
				  </tr>
				  <? } ?>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<div style="background-color: #AF9B78; padding: 3px;">
				<? if ($NavBarParams["pagenum"]> 0) { ?>
				<a href="?<?=$NavBarParams["first_link"]?>" ><<</a>&nbsp;
				<? } ?>

				<? if ($NavBarParams["pagenum"] > 0) { ?>
				<a href="?<?=$NavBarParams["previous_link"]?>"><</a>&nbsp;
				<? } ?>

				Page&nbsp;<?=$NavBarParams["pagenum"]+1?>/<?=$NavBarParams["totalpages"]?>&nbsp;(<?=$NavBarParams["totalrows"]?>&nbsp;Résultats&nbsp;trouvés)&nbsp;

				<? if ($NavBarParams["pagenum"] < $NavBarParams["totalpages"] -1 ) { ?>            
				<a href="?<?=$NavBarParams["next_link"]?>">></a>&nbsp;
				<? } ?>

				<? if ($NavBarParams["pagenum"] < $NavBarParams["totalpages"]-1) { ?>            
				<a href="?<?=$NavBarParams["last_link"]?>">>></a>
				<? } ?>
				&nbsp;&nbsp;&nbsp;Visualiser&nbsp;par:&nbsp;
				<?php
				if (isset($aRows)) {
					$arr=array('1'=>'1','5'=>'5','10'=>'10','20'=>'20','30'=>'30','40'=>'40','50'=>'50','100'=>'100','150'=>'150','200'=>'200','500'=>'500','all'=>$lang_Conf['all']);
					echo(makeselect('kv','viewby',$arr,$aVars['viewby'],'selectbox','onChange="javascript: window.location=\''.$_SERVER['PHP_SELF'].'?'.$NavBarParams["viewby_link"].'&viewby=\' + this.value;"'));
				}
				?>
			</div>
			</form>
		</td>
	</tr>
</table>