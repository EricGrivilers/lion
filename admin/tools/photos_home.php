<?php


if(!empty($aVars['delid'])){
	$r=$DBManager->TblBanners->Find('','id='.intval($aVars['delid']));
	$aPhoto=$r->fetchRow();
	//print_r($aPhoto);
	@unlink(MEDIAS_FS.PATH_DELIM.'banners'.PATH_DELIM.$aPhoto['photo']);
	$DBManager->TblBanners->DeleteItem('id='.intval($aVars['delid']));
	unset($aPhoto);
}













$errorMsg["invalidfields"]=0;
unset($aInvalidFields);

//photo SAVE
if(!empty($aVars['photo_save']) && $aVars['photo_save']==1){

	$aExtraCond = array();

	if(!empty($aVars['delimg'])){
		$rs=$DBManager->TblBanners->Find("","id=".$aVars['photo_modif']);
		if($rowItem=$rs->FetchRow()){
			@unlink(MEDIAS_FS.PATH_DELIM.'banners'.PATH_DELIM.$rowItem['photo']);
		}
		$params['photo']='';
	}else{
		if(isset($aVars['photo'])){
			$params['photo']=$aVars['photo'];
		}
	}
	//IMAGE
	if(isset($_FILES["photo"])){
		
		if($_FILES["photo"]['error']=='0'){
			
			$rep=MEDIAS_FS.PATH_DELIM.'banners'.PATH_DELIM;
			$tp=str_replace('%20',' ',$_FILES['photo']['name']);
			$newname=trimAll($tp);
			if(is_file($rep.$newname)){
				@unlink($rep.$newname);
			}
			move_uploaded_file($_FILES["photo"]["tmp_name"], $rep.$newname) or die("impossible de deplacer l'image temporaire vers ".$rep.$newname);
			unset($_FILES["photo"]);
			$params['photo']=$newname;
		}
	}

	

	if(isset($aVars['ranking'])){
		$params['ranking']=$aVars['ranking'];
	}else{
		$params['ranking']=1;
	}

	

	if($errorMsg['invalidfields']==0){
		if(!empty($aVars['photo_modif'])){
			$params['id']=$aVars['photo_modif'];
		}
		//print_r($params);
		$newId=$DBManager->TblBanners->Save($params);
		
		!empty($newId)?$theId=$newId:$theId=$aVars['photo_modif'];
		//print_r($params);
		//header("Location:".ROOT_WS."admin/index.php?kind=images&todo=home&id=".$theId);
		//header("Location:".ADMIN_PROTOCOL."://www.draym.net/admin/site/portfolio_edit.php?portfolioid=$portfolioid");
	}else{
		print_r($aInvalidFields);
	}
}




















if(!empty($aVars['editid'])){
	$r=$DBManager->TblBanners->Find('','id='.intval($aVars['editid']));
	$aPhoto=$r->fetchRow();
}

$oModel = $DBManager->TblBanners;
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
					<td style="width: 50%">
						<table width="100%" border="0">
							<tr>
								<td>Numéro d'ordre</td>
								<td colspan="2">
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
					<td style="width: 50%">
						<table width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td>media:&nbsp;</td>
								<td>
								<?php
									if(!empty($aPhoto['photo'])){
										if(is_file(MEDIAS_FS.PATH_DELIM.'banners'.PATH_DELIM.$aPhoto['photo'])){
											$nameonly=substr($aPhoto['photo'],0,-4);
											$pathinfo=pathinfo($aPhoto['photo']);
											list($width, $height, $type, $attr) = getimagesize(MEDIAS_FS.PATH_DELIM.'banners'.PATH_DELIM.$aPhoto['photo']);
											if($width>195){
												$height=round((($height/$width)*195),0);
												$width=195;
											}
											if($pathinfo['extension']=='swf'){
												?>
												<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/photo/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="<?=$width;?>" height="<?=$height;?>" id="<?=$nameonly;?>" align="middle">
												<param name="allowScriptAccess" value="sameDomain" />
												<param name="movie" value="<?=UPLOADS_WS.$aPhoto['photo'];?>" /><param name="quality" value="high" />
												<param name="bgcolor" value="#009966" />
												<embed src="<?=UPLOADS_WS.$aPhoto['photo'];?>" quality="high" bgcolor="#009966" width="<?=$width;?>" height="<?=$height;?>" name="<?=$nameonly;?>" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
												</object>

												<?
											}else{
											?>
											<img src="<?=MEDIAS_WS;?>/banners/<?=$aPhoto['photo'];?>" style="border: solid 2px #204291; width: <?=$width;?>px; height: <?=$height;?>px;">
											<?}?>
											<br><br>
											<input type="hidden" name="delimg" id="delimg" value="" />
											<input type="hidden" name="photo" id="photo" value="<?=$aPhoto['photo'];?>" />
											<input type="button" value="Effacer le media" onClick="document.getElementById('delimg').value=1; document.getElementById('photo_save').value=1; this.form.submit();" />
											<?php
										}else{
											echo('media renseigné dans la base de données mais non trouvé sur le serveur');
										}
									}else{
										echo("Aucun media");
									}
								?><br /><br />
								</td>
							</tr>
							<tr>
								<td colspan="2">Remplacer le media par:&nbsp;<input type="file" name="photo" id="photo" /></td>
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
					<td onClick="if(document.getElementById('sortdir').value=='DESC'){document.getElementById('sortdir').value='ASC';}else{document.getElementById('sortdir').value='DESC';}document.getElementById('oby').value='photo';document.getElementById('search_formz').submit();" style="cursor: pointer; background-color: #DFC599; text-align: center;">Nom photo<?php if($oby=="photo"){echo("&nbsp;<img src='".MEDIAS_WS."/".strtolower($sortdir).".gif' border='0' />");}?></td>
					<td style="text-align: center; background-color: #DFC599; text-align: center;">Photo</td>
				  </tr>
				  <? foreach($aRows as $row) { ?>
				  <tr>
					<td style="background-color: #EFE4D1; text-align: center;"><A href="?kind=images&todo=home&delid=<?=$row["id"];?>" OnClick="return confirm('Supprimer la photo : <?=$row["photo"]?> ?');"><img src="<?=MEDIAS_WS;?>/b_drop.png" border="0" title="Supprimer"></A>&nbsp;<A href="?kind=images&todo=home&editid=<?=$row["id"];?>" ><img src="<?=MEDIAS_WS;?>/b_edit.png" border="0" title="<?=$lang_Conf["editgroup"];?>"></a>
					</td>
					<td style="background-color: #EFE4D1; text-align: center;"><?=!empty($row["ranking"])?$row["ranking"]:'&nbsp;';?></td>
					<td style="background-color: #EFE4D1; text-align: center;"><?=!empty($row["photo"])?$row["photo"]:'&nbsp;';?></td>
					<td style="background-color: #EFE4D1; text-align: center;"><? if(is_file(MEDIAS_FS.PATH_DELIM.'banners'.PATH_DELIM.$row['photo'])){;?><img src="<?=MEDIAS_WS . '/banners/' . $row['photo'];?>" style="width: 150px; border: none;"><? } ?></td>
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
