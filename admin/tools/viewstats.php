<?php

define("INC_PATH_SEP",(eregi("windows",getenv("OS"))?";":":"));
define("PATH_DELIM",(eregi("windows",getenv("OS"))?"\\":"/"));
define( 'ROOT_FS' , realpath( '../' ) );
define( 'ROOT_WS' , 'http://'.$_SERVER['SERVER_NAME'] );
define( 'LIB_FS' , ROOT_FS . PATH_DELIM . 'lib');
define( 'LIB_WS' , ROOT_WS . '/admin/lib' );
define( 'PDF_FS' , ROOT_FS . PATH_DELIM . 'admin' .PATH_DELIM . 'pdf');
define( 'PDF_WS' , ROOT_WS . '/admin/pdf' );



//INCLUSION PATHS
ini_set( "include_path" , LIB_FS . INC_PATH_SEP . realpath(LIB_FS . "/Tools") . INC_PATH_SEP . realpath(LIB_FS . "/html2ps/public_html") . INC_PATH_SEP . realpath(dirname(__FILE__)) . INC_PATH_SEP . ini_get('include_path'));


//REQUIRES
require_once("pdfgen.php");

//$time=time();
//$basename=$time.".pdf";
$basename="realestate.pdf";
$pdfpath=PDF_FS.'/'.$basename;


$q="select * from items where num='".$_GET['itemId']."'";
$r=mysql_query($q) or die(mysql_error());
$row=mysql_fetch_array($r);

ob_start();
?>
<table cellpadding="0" cellspacing="0" style="width: 100%;">
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center" style="font-weight: bold;">Référence: <?=$row['reference'];?>&nbsp;&nbsp;<a href="<?=ROOT_WS.'/admin/pdf/'.$basename;?>" target="_blank"><img src="<?=ROOT_WS.'/medias/pdf.gif';?>" style="border: none;"></a></td>
  </tr>
  <tr>
    <td colspan="2" align="center" style="font-weight: bold;"><?=rawurldecode($_GET['rawstats']);?></td>
  </tr>
  <tr>
    <td>Visites</td>
    <td align="left"><img src="<?=ROOT_WS.'/admin';?>/tools/graph.php?itemId=<?= $_GET['itemId']; ?>&period=days" /></td>
  </tr>
  <tr>
    <td>Moyenne hebdomadaire<br />(par jour de semaine)</td>
    <td align="left"><img src="<?=ROOT_WS.'/admin';?>/tools/graph.php?itemId=<?= $_GET['itemId']; ?>&period=wdays" /></td>
  </tr>
   <tr>
    <td>Visites hebdomadaires</td>
    <td align="left"><img src="<?=ROOT_WS.'/admin';?>/tools/graph.php?itemId=<?= $_GET['itemId']; ?>&period=weeks" /></td>
  </tr>
  <tr>
    <td>Visites mensuelles</td>
    <td align="left"><img src="<?=ROOT_WS.'/admin';?>/tools/graph.php?itemId=<?= $_GET['itemId']; ?>&period=months" /></td>
  </tr>
</table>
<br /><br />
<table border=0 cellpadding=2 cellspacing=1 align=center>
 <tr><th width=300>Biens mis en favoris par</th><th  width=300>Le</th></tr> 
  <?php
$o=0;
$q="select * from users2items,users where itemId='".$_GET['itemId']."' and userId=users.id";
$r=mysql_query($q) or die(mysql_error());
while($row=mysql_fetch_array($r)) {
?>
  <tr class='rank<?= $o; ?>'>
   
    <td><a href='index.php?kind=users&userId=<?= $row['userId']; ?>'><?= $row['firstname']." ".strtoupper($row['lastname']); ?></a></td>
   <td><?= shakeDate($row['date']); ?></td>
   </tr>
  <?php
$o++;
	if($o>1) {$o=0;}
}
?>
</table>

<?php

$html=ob_get_contents();
ob_end_flush();

@unlink($pdfpath);
convert_to_pdf($html,$pdfpath);

/*
//HEADER PDF
if(is_file($pdfpath)){
	$len = filesize($pdfpath);
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: public"); 
	header("Content-Description: File Transfer");
	header("Content-Type: application/pdf");
	header("Content-Disposition: attachment; filename=".basename($pdfpath).";");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".$len);
	@readfile($pdfpath);
	exit;
}
*/

?>
