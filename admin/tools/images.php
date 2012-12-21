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




echo('<a href="?kind=images&todo=home">Photos Homepage</a> | <a href="?kind=images&todo=slide">Photos Slideshow</a>');



switch($aVars['todo']){
case 'home':
	require('photos_home.php');
	break;
case 'slide':
	require('slideshow.php');
	break;
}
?>