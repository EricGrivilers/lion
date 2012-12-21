<?php

session_start();

require_once __core__."clsDb.inc.php";


require_once __core__."clsSite.inc.php";
require_once __core__."clsTemplate.inc.php";
require_once __core__."clsPage.inc.php";
require_once __core__."clsHeaders.inc.php";
require_once __core__."clsElement.inc.php";
require_once __core__."clsUtils.inc.php";
require_once __core__."clsLang.inc.php";
require_once __core__."clsImageManip.inc.php";



if ( !function_exists('json_decode') ){
    function json_decode($content, $assoc=false){
                require_once __lib__.'core/clsJson.inc.php';
                if ( $assoc ){
                    $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
        } else {
                    $json = new Services_JSON;
                }
        return $json->decode($content);
    }
}

if ( !function_exists('json_encode') ){
    function json_encode($content){
                require_once __lib__.'core/clsJson.inc.php';
                $json = new Services_JSON;
               
        return $json->encode($content);
    }
}


$addOn=array();


if(!empty($_GET['logout'])) {
	unset($_SESSION['user']);
	
}


if(!empty($_GET['language'])) {
	$_SESSION['language']=$_GET['language'];
	
}
if(empty($_SESSION['language'])) {
	$_SESSION['language']='fr';
}


$site=new site;
$site->setLanguage();



//ajax 
if(!empty($_POST['jfunc'])) {
	$c=explode(".",$_POST['jfunc']);
	if(file_exists(__elem__.$c[0]."/cls".ucfirst($c[0]).".inc.php")) {
		require_once(__elem__.$c[0]."/cls".ucfirst($c[0]).".inc.php");
		$el=new $c[0];
		foreach($_POST as $k=>$v) {
			$el->$k=$v;
		}
		echo call_user_func(array($el,$c[1]));
	}
	die();
}

?>