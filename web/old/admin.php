<?php
header('Content-type: text/html; charset=UTF-8'); 
include('config.inc.php');
include(__lib__."init.inc.php");
require_once(__root__."admin/clsAdmin.inc.php");

$admin=new admin;
switch($_GET['func']) {
	default:
		$out=$admin->init();
	break;
	
	case 'edit':
	print_r($_POST);
		$admin->elementName=$_POST['element'];
		$admin->dataId=$_POST['dataId'];
		$out=$admin->edit();
	break;
	
	case 'addData':
		$admin->elementName=$_POST['type'];
		$out=$admin->addData();
	break;
	
	case 'saveDatas':
		$admin->datas=$_POST['datas'];
		$admin->elementName=$_POST['element'];
		$admin->dataId=$_POST['dataId'];
		$out=$admin->saveDatas();
	break;
	
	case 'autofill':
		$admin->autofillA=array();
		$admin->autofillA['type']=$_POST['type'];
		$admin->autofillA['queryString']=$_POST['queryString'];
		$out=$admin->autofill();
	break;
	
	case 'list':
		$admin->listType['type']=$_POST['type'];
		$admin->orderLimit=$_POST['start'];
		$out=$admin->listAll();
	break;
	
	case 'removeTrack':
		$admin->removeTrack();
	break;
}
echo $out;
?>