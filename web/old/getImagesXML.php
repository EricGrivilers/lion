<?php

require_once('config.inc.php');
require_once(__lib__.'init.inc.php');

if(!empty($_GET['itemId']) && ctype_digit($_GET['itemId'])){

	$itemId=intval($_GET['itemId']);




	echo('<?xml version="1.0" encoding="ISO-8859-1" ?>'."\r\n");

	//NEWS
	//$r = $searchMgr->_DBManager->TblPhoto2item->Find("","item_id=".$itemId,"ranking ASC");
	
	$db=new DB;
	$db->query="SELECT * FROM photo2item WHERE item_id='".$itemId."' ORDER BY ranking ASC";
	$db->setQuery();
	
	$items=$db->output;

	echo('<gallery>'."\r\n");
	echo('<album lgPath="http://www.immo-lelion.be/photos/big/" tnPath="http://www.immo-lelion.be/photos/thumbs/" title="" description="" tn="">'."\r\n");

	//while($rowItem=$r->FetchRow()){
	foreach($items as $rowItem){
		if(!eregi("^(npict)[0-9]+(s\.jpg)$",$rowItem['photo'])){
			echo(' <img src="'.$rowItem['photo'].'" title="'.$rowItem['photo'].'" caption="'.$rowItem['photo'].'" link="" target="_blank" pause="" />'."\r\n");
		}
	}

	echo('</album>'."\r\n");
	echo('</gallery>'."\r\n");

}//END IF ITEMID

?>