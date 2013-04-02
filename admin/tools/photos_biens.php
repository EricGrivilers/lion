<?php
session_start();

define("INC_PATH_SEP",(eregi("windows",getenv("OS"))?";":":"));
define("PATH_DELIM",(eregi("windows",getenv("OS"))?"\\":"/"));
define( 'ROOT_FS' , realpath(dirname(__FILE__).'/../../') );
define( 'ROOT_WS' , 'http://' . $_SERVER['SERVER_NAME'] );
define( 'MEDIAS_FS' , ROOT_FS . PATH_DELIM . 'medias' );
define( 'MEDIAS_WS' , ROOT_WS . '/medias' );
define( 'PICTURES_FS' , ROOT_FS . PATH_DELIM . 'photos' );
define( 'PICTURES_WS' , ROOT_WS . '/photos' );
define( 'LIB_FS' , ROOT_FS . PATH_DELIM . 'lib' );
define( 'LIB_WS' , ROOT_WS . '/lib' );
define('MAILFROM' , 'cedric@caravanemedia.com');



//INCLUSION PATHS
ini_set( "include_path" , LIB_FS . INC_PATH_SEP . realpath(LIB_FS . "/DBManager") . INC_PATH_SEP . realpath(ROOT_FS . "/incs") . INC_PATH_SEP . realpath(dirname(__FILE__)) . INC_PATH_SEP . ini_get('include_path'));



//REQUIRES
require_once('config.inc.php');
require_once('clsTemplate.inc.php');
require_once('menu.inc.php');

require_once('DBManager2.php');
require_once('DataList2.php');
require_once('functions.inc.php');

$dsn = array('phptype'  => 'mysql',	'username' => $DBuser,	'password' => $DBpass, 'hostspec' => $DBhost, 'database' => $DBName);
$DBManager= new DBManager($dsn);


//_GET & _POST secure recup
$aVars = getVars(array_merge($_GET,$_POST),true);



$itemId=intval($aVars['detailId']);


if(!empty($itemId) && is_int($itemId)){








	if(!empty($aVars['delid'])){
		$r=$DBManager->TblPhoto2item->Find('','item_id='.$itemId.' AND id='.intval($aVars['delid']));
		$aPhoto=$r->fetchRow();
		//print_r($aPhoto);
		@unlink(PICTURES_FS.PATH_DELIM.'big'.PATH_DELIM.$aPhoto['photo']);
		@unlink(PICTURES_FS.PATH_DELIM.'thumb'.PATH_DELIM.$aPhoto['photo']);
		$DBManager->TblPhoto2item->DeleteItem('id='.intval($aVars['delid']));
		unset($aPhoto);
	}








	$errorMsg["invalidfields"]=0;
	unset($aInvalidFields);

	//photo SAVE
	if(!empty($aVars['photo_save']) && $aVars['photo_save']==1){

		$aExtraCond = array();

		if(!empty($aVars['delimg'])){
			$rs=$DBManager->TblPhoto2item->Find("","item_id=".$itemId." AND id=".$aVars['photo_modif']);
			if($rowItem=$rs->FetchRow()){
				@unlink(PICTURES_FS.PATH_DELIM.'big'.PATH_DELIM.$rowItem['photo']);
				@unlink(PICTURES_FS.PATH_DELIM.'thumb'.PATH_DELIM.$rowItem['photo']);
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
				
				$bigRep=PICTURES_FS.PATH_DELIM.'big'.PATH_DELIM;
				$thumbRep=PICTURES_FS.PATH_DELIM.'thumbs'.PATH_DELIM;
				$tp=str_replace('%20',' ',$_FILES['photo']['name']);
				$newname=strtolower(trimAll($tp));
				if(is_file($bigRep.$newname)){
					@unlink($bigRep.$newname);
				}
				if(is_file($thumbRep.$newname)){
					@unlink($thumbRep.$newname);
				}
				//echo($rep);
				makeThumb($_FILES['photo']['tmp_name'],$thumbRep.$newname);
				makeBig($_FILES['photo']['tmp_name'],$bigRep.$newname);

				//move_uploaded_file($_FILES["photo"]["tmp_name"], $rep.$newname) or die("impossible de deplacer l'image temporaire vers ".$rep.$newname);
				unset($_FILES["photo"]);
				$params['photo']=$newname;
			}
		}

		

		if(isset($aVars['ranking'])){
			$params['ranking']=$aVars['ranking'];
		}else{
			$params['ranking']=1;
		}

		$params['item_id']=$itemId;

		

		if($errorMsg['invalidfields']==0){
			if(!empty($aVars['photo_modif'])){
				$params['id']=$aVars['photo_modif'];
			}
			//print_r($params);
			$newId=$DBManager->TblPhoto2item->Save($params);

			$rs=$DBManager->TblPhoto2item->Find("","item_id=".$itemId,"ranking");
			$a=$rs->FetchRow();
			$thePhoto=explode('.',$a['photo']);
			$thePhoto=$thePhoto[0];
			$r=$DBManager->TblItems->UpdateItem(array('photo'=>$thePhoto),"num=".$itemId);
			
			!empty($newId)?$theId=$newId:$theId=$aVars['photo_modif'];
			//print_r($params);
			//header("Location:".ROOT_WS."admin/index.php?kind=images&todo=home&id=".$theId);
			//header("Location:".ADMIN_PROTOCOL."://www.draym.net/admin/site/portfolio_edit.php?portfolioid=$portfolioid");
		}else{
			print_r($aInvalidFields);
		}
	}




















	if(!empty($aVars['editid'])){
		$r=$DBManager->TblPhoto2item->Find('','item_id='.$itemId.' AND id='.intval($aVars['editid']));
		$aPhoto=$r->fetchRow();
	}

	$oModel = $DBManager->TblPhoto2item;
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

	$aExtraCond[]="item_id=".$itemId;

	//getRows( $aListenFields=array() , $aSelectFields='' , $aExtraCond='' , $aHaving='' , $groupBy='' , $countGroupBy="", $aExcludeKeys="" , $aExcludeValues="" ) 
	$aRows = $dtl->getRows('','',$aExtraCond,'',$groupBy);

	$NavBarParams=$dtl->NavBarParams;
	//print_r($NavBarParams);
	//print_r($aRows);

	?>
	</form>
	<a name="photos">
	<br /><br />
	
	<table cellpadding="2" cellspacing="0" style="width: 60%; margin-left: auto; margin-right: auto;">
		<tr>
			<td style="font-weight: bold; text-align: center; color: red;">
				ATTENTION:<br />
				1. ENREGISTREZ D'ABORD VOS MODIFICATIONS CI-DESSUS AVANT DE MODIFIER LES IMAGES !<br />
				2. La miniature de la première image sera celle prise pour le petit slideshow et pour l'image de présentation dans les résultats.
			</td>
			</tr>
		<tr>
			<td style="background-color: #DFC599;">
	<b><?php echo !empty($aPhoto)?'Modifier le media':'Ajouter un media';?></b>
				<form name="f_photo" id="f_photo" method="POST" enctype="multipart/form-data" action="<?php echo ROOT_WS;?>/admin/index.php?&#35;photos">
				<input type="hidden" name="kind" id="kind" value="item">
				<input type="hidden" name="action" id="action" value="edit">
				<input type="hidden" name="level" id="level" value="<?php echo $aVars['level'];?>">
				<input type="hidden" name="detailId" id="detailId" value="<?php echo $aVars['detailId'];?>">
				<input type="hidden" name="detailId" id="itemId" value="<?php echo $itemId;?>">
				<input type="hidden" name="photo_save" id="photo_save" value="">
				<input type="hidden" name="photo_modif" id="photo_modif" value="<?php echo $aPhoto['id'];?>">
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
										&nbsp;<?php echo $aInvalidFields['ranking'];?></td>
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
											if(is_file(PICTURES_FS.PATH_DELIM.'thumbs'.PATH_DELIM.$aPhoto['photo'])){
												$nameonly=substr($aPhoto['photo'],0,-4);
												$pathinfo=pathinfo($aPhoto['photo']);
												list($width, $height, $type, $attr) = getimagesize(PICTURES_FS.PATH_DELIM.'thumbs'.PATH_DELIM.$aPhoto['photo']);
												if($width>170){
													$height=round((($height/$width)*170),0);
													$width=170;
												}
												if($pathinfo['extension']=='swf'){
													?>
													<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/photo/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="<?php echo $width;?>" height="<?php echo $height;?>" id="<?php echo $nameonly;?>" align="middle">
													<param name="allowScriptAccess" value="sameDomain" />
													<param name="movie" value="<?php echo UPLOADS_WS.$aPhoto['photo'];?>" /><param name="quality" value="high" />
													<param name="bgcolor" value="#009966" />
													<embed src="<?php echo UPLOADS_WS.$aPhoto['photo'];?>" quality="high" bgcolor="#009966" width="<?php echo $width;?>" height="<?php echo $height;?>" name="<?php echo $nameonly;?>" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
													</object>

													<?
												}else{
												?>
												<img src="<?php echo PICTURES_WS;?>/big/<?php echo $aPhoto['photo'];?>" style="border: solid 2px #204291; width: <?php echo $width;?>px; height: <?php echo $height;?>px;">
												<?}?>
												<br><br>
												<input type="hidden" name="delimg" id="delimg" value="" />
												<input type="hidden" name="photo" id="photo" value="<?php echo $aPhoto['photo'];?>" />
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
				
				<div style="background-color: #AF9B78; padding: 3px; text-align: center;">
					<? if ($NavBarParams["pagenum"]> 0) { ?>
					<a href="?<?php echo $NavBarParams["first_link"]?>&#35;photos" ><<</a>&nbsp;
					<? } ?>

					<? if ($NavBarParams["pagenum"] > 0) { ?>
					<a href="?<?php echo $NavBarParams["previous_link"]?>&#35;photos"><</a>&nbsp;
					<? } ?>

					Page&nbsp;<?php echo $NavBarParams["pagenum"]+1?>/<?php echo $NavBarParams["totalpages"]?>&nbsp;(<?php echo $NavBarParams["totalrows"]?>&nbsp;Résultats&nbsp;trouvés)&nbsp;

					<? if ($NavBarParams["pagenum"] < $NavBarParams["totalpages"] -1 ) { ?>            
					<a href="?<?php echo $NavBarParams["next_link"]?>&#35;photos">></a>&nbsp;
					<? } ?>

					<? if ($NavBarParams["pagenum"] < $NavBarParams["totalpages"]-1) { ?>            
					<a href="?<?php echo $NavBarParams["last_link"]?>&#35;photos">>></a>
					<? } ?>
					&nbsp;&nbsp;&nbsp;Visualiser&nbsp;par:&nbsp;
					<?php
					if (isset($aRows)) {
						$arr=array('1'=>'1','5'=>'5','10'=>'10','20'=>'20','30'=>'30','40'=>'40','50'=>'50','100'=>'100','150'=>'150','200'=>'200','500'=>'500','all'=>$lang_Conf['all']);
						echo(makeselect('kv','viewby',$arr,$aVars['viewby'],'selectbox','onChange="javascript: window.location=\''.$_SERVER['PHP_SELF'].'?'.$NavBarParams["viewby_link"].'&viewby=\' + this.value + \'&#35;photos\';"'));
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
						<td onClick="if(document.getElementById('sortdir').value=='DESC'){document.getElementById('sortdir').value='ASC';}else{document.getElementById('sortdir').value='DESC';}document.getElementById('oby').value='ranking';document.getElementById('search_formz').submit();" style="cursor: pointer; background-color: #DFC599; text-align: center;">Ordre<?php if($oby=="ranking"){echo("&nbsp;<img src='".PICTURES_WS."/".strtolower($sortdir).".gif' border='0' />");}?></td>
						<td onClick="if(document.getElementById('sortdir').value=='DESC'){document.getElementById('sortdir').value='ASC';}else{document.getElementById('sortdir').value='DESC';}document.getElementById('oby').value='photo';document.getElementById('search_formz').submit();" style="cursor: pointer; background-color: #DFC599; text-align: center;">Nom photo<?php if($oby=="photo"){echo("&nbsp;<img src='".PICTURES_WS."/".strtolower($sortdir).".gif' border='0' />");}?></td>
						<td style="text-align: center; background-color: #DFC599; text-align: center;">Photo</td>
					  </tr>
					  <? foreach($aRows as $row) { ?>
					  <tr>
						<td style="background-color: #EFE4D1; text-align: center;"><A href="?kind=item&action=edit&level=2&itemId=<?php echo $itemId;?>&delid=<?php echo $row["id"];?>&#35;photos" OnClick="return confirm('Supprimer la photo : <?php echo $row["photo"]?> ?');"><img src="<?php echo MEDIAS_WS;?>/b_drop.png" border="0" title="Supprimer"></A>&nbsp;<A href="?kind=item&action=edit&level=2&itemId=<?php echo $itemId;?>&editid=<?php echo $row["id"];?>&#35;photos" ><img src="<?php echo MEDIAS_WS;?>/b_edit.png" border="0" title="<?php echo $lang_Conf["editgroup"];?>"></a>
						</td>
						<td style="background-color: #EFE4D1; text-align: center;"><?php echo !empty($row["ranking"])?$row["ranking"]:'&nbsp;';?></td>
						<td style="background-color: #EFE4D1; text-align: center;"><?php echo !empty($row["photo"])?$row["photo"]:'&nbsp;';?></td>
						<td style="background-color: #EFE4D1; text-align: center;"><? if(is_file(PICTURES_FS.PATH_DELIM.'thumbs'.PATH_DELIM.$row['photo'])){;?><img src="<?php echo PICTURES_WS . '/thumbs/' . $row['photo'];?>" style="width: 150px; border: none;"><? } ?></td>
					  </tr>
					  <? } ?>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<div style="background-color: #AF9B78; padding: 3px; text-align: center;">
					<? if ($NavBarParams["pagenum"]> 0) { ?>
					<a href="?<?php echo $NavBarParams["first_link"]?>&#35;photos" ><<</a>&nbsp;
					<? } ?>

					<? if ($NavBarParams["pagenum"] > 0) { ?>
					<a href="?<?php echo $NavBarParams["previous_link"]?>&#35;photos"><</a>&nbsp;
					<? } ?>

					Page&nbsp;<?php echo $NavBarParams["pagenum"]+1?>/<?php echo $NavBarParams["totalpages"]?>&nbsp;(<?php echo $NavBarParams["totalrows"]?>&nbsp;Résultats&nbsp;trouvés)&nbsp;

					<? if ($NavBarParams["pagenum"] < $NavBarParams["totalpages"] -1 ) { ?>            
					<a href="?<?php echo $NavBarParams["next_link"]?>&#35;photos">></a>&nbsp;
					<? } ?>

					<? if ($NavBarParams["pagenum"] < $NavBarParams["totalpages"]-1) { ?>            
					<a href="?<?php echo $NavBarParams["last_link"]?>&#35;photos">>></a>
					<? } ?>
					&nbsp;&nbsp;&nbsp;Visualiser&nbsp;par:&nbsp;
					<?php
					if (isset($aRows)) {
						$arr=array('1'=>'1','5'=>'5','10'=>'10','20'=>'20','30'=>'30','40'=>'40','50'=>'50','100'=>'100','150'=>'150','200'=>'200','500'=>'500','all'=>$lang_Conf['all']);
						echo(makeselect('kv','viewby',$arr,$aVars['viewby'],'selectbox','onChange="javascript: window.location=\''.$_SERVER['PHP_SELF'].'?'.$NavBarParams["viewby_link"].'&viewby=\' + this.value + \'&#35;photos\';"'));
					}
					?>
				</div>
				</form>
			</td>
		</tr>
	</table>


<?php
	
}//END IF itemId

?>