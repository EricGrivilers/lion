<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);
/*http://cpanel.optinet-isp.net*/

define(__DBhost__,"localhost");
define(__DBuser__,"immolelionbe");
define(__DBpass__,"gdfd54yuy");
define(__DBName__,"immolelionbe");

define(__DBprefix__,"lion");

define(__postmaster__,"info@immo-lelion.be");
define(__cc__,"eric@caravanemedia.com");
///web2/web1003-domains/immo-lelion.be/www/
//define(__root__,"/web2/web1003-domains/immo-lelion.be/www/");
define(__root__,$_SERVER['DOCUMENT_ROOT']."/");

//define(__web__,"/v4/");
define(__web__,"http://".$_SERVER['HTTP_HOST']."/");
define(__lib__,__root__."lib/");
define(__core__,__lib__."core/");
define(__elem__,__lib__."elements/");

/*

define(__DBhost__,"web1003.optinet-isp.net");
define(__DBuser__,"immolelionbe");
define(__DBpass__,"gdfd54yuy");
define(__DBName__,"immolelionbe");

define(__DBprefix__,"lion");

define(__postmaster__,"info@immo-lelion.be");
define(__cc__,"eric@caravanemedia.com");
///web2/web1003-domains/immo-lelion.be/www/
define(__root__,"/web2/web1003-domains/immo-lelion.be/www/");
//define(__root__,__FILE__);
//define(__web__,"/v4/");
define(__web__,"http://www.immo-lelion.be/");
define(__lib__,__root__."lib/");
define(__core__,__lib__."core/");
define(__elem__,__lib__."elements/");
*/
?>
