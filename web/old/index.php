<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

include('config.inc.php');
include(__lib__."init.inc.php");

/*
echo "<pre>";
print_r($_GET);
echo "<pre>";
*/
//echo $_GET['q'];
//die();


//print_r($_GET);
if($_GET['q']>0) {
			$db=new DB;
			$db->query="SELECT location FROM items WHERE reference='030/".$_GET['q']."' ";
			$db->setQuery();
			if($r=$db->output[0]) {
				if($r['location']=='Y') {
					$_GET['q']="location/".$_GET['q'];
					$_GET['searchType']='rent';
				}
				else {
					$_GET['q']="vente/".$_GET['q'];
					$_GET['searchType']='sale';
				}
			}

			header('location:/'.$_GET['q']);
}




if($_GET['language']!='') {
$_SESSION['language']=$_GET['language'];
unset($_SESSION['lang']);
}




if($_SESSION['user']['admin']==1) {
	$_SESSION['admin']=true;
}

require_once __root__.'/lib/Twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(__root__.'lib/templates');
$twig = new Twig_Environment($loader, array('debug' => true,'autoescape'=>false));
/*
$twig = new Twig_Environment($loader, array(
    'cache' => __root__.'cache/twig/cache',
));
*/


$template=new template('.');


$page=new page;
$page->init();


$contents=array();
$areas=$page->areas;

$headers=new headers;
$headers->pageId=$_GET['pageId'];
$headers->init();
$headers->templateFolder=$page->templateFolder;


$styles="<link rel=\"shortcut icon\" href=\"favicon.ico\" type=\"image/x-icon\" />";

$styles.=$headers->getStyles();
$headerScripts=$headers->getScripts();
//$headerScripts.=$headers->addJs("http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit");

$headerScripts.=$headers->addJs("http://www.google.com/jsapi");
$headerScripts.=$headers->addJs('https://maps.googleapis.com/maps/api/js?sensor=false');

if($_GET['ref']) {
	/*$MAP_OBJECT = new GoogleMapAPI(); $MAP_OBJECT->_minify_js = isset($_REQUEST["min"])?FALSE:TRUE;
	$MAP_OBJECT->getHeaderJS();
	$MAP_OBJECT->getMapJS();
	*/
}
$headerScripts.="<script type='text/javascript'>google.load('language', '1');</script>";

/*
if($_SESSION['user']['admin']==1) {
		$styles.=$headers->addCss("/_admin_/admin.css");
		$headerScripts.=$headers->addJs("/_admin_/js/admin.js");
		$headerScripts.=$headers->addJs("/lib/elements/datas/js/datas.js");

}
*/

$addOns[]="<input type='hidden' id='pageId' value='".$_GET['pageId']."' />";
$addOns[]="<div id='googt'></div>";

$template->set_file("template",__lib__."templates/".$page->templateFolder."/template.tpl");

foreach($areas as $area) {
	$template->set_var($area, $page->getElements($area));
}


$template->set_var('webRoot',__web__);
$template->set_var('styles', $styles);
$template->set_var('q', $_GET['q']);
$template->set_var('headerScripts', $headerScripts);
$template->set_var('addOns', implode("",$addOns));
$template->set_var('title', $page->title);
$template->set_var("keywords",$page->keywords);
$template->set_var("description",$page->description);
$template->set_var("language",$_SESSION['language']);
$template->set_var("olanguage",$olanguage);
switch($_SESSION['language']) {
	default:
		$baseline='Une agence locale de qualité intégrée dans un réseau international de prestige';
	break;

	case 'en':
		$baseline='A local quality real estate agency integrated in a prestigious global network';
	break;
}

$template->set_var("currentUrl",$_SERVER["REQUEST_URI"]);

$template->set_var("baseline",$baseline);
/*$template->parse("parse", "template");
$template->p("parse");
*/

echo $twig->render($page->templateFolder."/template.tpl",$template->get_vars());

?>
