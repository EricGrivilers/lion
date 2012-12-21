<?php

/*
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
$basename="userdetails.pdf";
$pdfpath=PDF_FS.'/'.$basename;



*/

if(isset($_GET['userId'])) {
	ob_start();
$q="select * from users where id='".$_GET['userId']."'";

$r=mysql_query($q) or die(mysql_error());
$row=mysql_fetch_array($r);
//include('../incs/fr.inc.php');
?>
<div id='item'>

<table class='edit' cellpadding=5 cellspacing=0 >
      <tr><th><?= $row['id']; ?></th><th colspan=3 style='background:#666666;color:#ffffff'><?=strtoupper($row['lastname']);?> <?=ucfirst($row['firstname']);?><br><?=$row['tel'];?>&nbsp;&nbsp;<a href='<?=ROOT_WS.'/admin/pdf/'.$basename;?>' target='_blank'><img src='<?=ROOT_WS.'/medias/pdf.gif';?>' style='border: none;'></a></th>
      </tr>
      <tr>
        <td class='formlab' width=100>Langue</td>
        <td><input type='radio' name='language' <?=($_SESSION['language']=='fr' ? 'checked' : '');?> value='fr'/>
          fr
          <input type='radio' name='language'  <?=($_SESSION['language']=='en' ? 'checked' : '');?> value='en'/>
          en </td>
        <td width=100>Salutaion</td><td><select name='salutation'><option value='Mme' <?=($row['salutation']=='Mme' ? 'selected' : '');?>><?=$lang['mrs'];?></option><option value='M' <?=($row['salutation']=='M' ? 'selected' : '');?>><?=$lang['mr'];?></option></select></td>
      </tr>
      <tr>
        <td class='formlab'>Nom</td>
        <td><?=$row['lastname'];?></td>
        <td class='formlab'>Prénom</td>
        <td><?=$row['firstname'];?></td>
      </tr>
      <tr>
        <td class='formlab'>E-mail</td>
        <td><a href='mailto:<?=$row['email'];?>'><?=$row['email'];?></a></td>
        <td class='formlab'>Tél.</td>
        <td><?=$row['tel'];?></td>
      </tr>
      <tr>
        <td class='formlab'>Rue</td>
        <td><?=$row['street'];?></td>
        <td class='formlab'>Num</td>
        <td><?=$row['number'];?></td>
      </tr>
      <tr>
        <td class='formlab'>ZIP</td>
        <td><?=$row['zip'];?></td>
        <td class='formlab'>Ville</td>
        <td><?=$row['city'];?></td>
      </tr>
      <tr>
        <td class='formlab'>Pays</td>
        <td><?=$row['country'];?></td>
        <td class='formlab'>Fax</td>
        <td><?=$row['fax'];?></td>
      </tr>
     
     
    </table>
</div>
<div class='item'>
<?php
$out="<h1>Biens sauvegardés</h1>";
$out.="<table class='edit' cellpadding=5 cellspacing=0 align=center >";
$out.="<tr><th></th><th>description</th><th>Ref.</th><th>dernière fois vu</th><th>Compteur</th></tr>";
 $q="select * from users2items LEFT JOIN items ON items.num=users2items.itemId  where users2items.userId='".$_GET['userId']."' and saved=1 and reference!=''";

$r=mysql_query($q) or die(mysql_error());
while($row=mysql_fetch_array($r)) {
	
$out.="<tR><td width=100><img src='/photos/thumbs/".$row['photo'].".jpg' width=100></td><td>".$row['descrfr']."</td><td width=50><a href='index.php?kind=item&action=edit&level=2&itemId=".$row['itemId']."'>".$row['reference']."</a></td><td width=50>".$row['date']."</td><td width=50>".$row['counter']."</td></tr>";
}
$out.="</table>";
echo $out;


$out="<h1>Biens vus</h1>";
$out.="<table class='edit' cellpadding=5 cellspacing=0 align=center >";
$out.="<tr><th></th><th>description</th><th>Ref.</th><th>dernière fois vu</th><th>Compteur</th></tr>";
 
 
 
 
 
 //$q="select * from users2items,items where users2items.userId='".$_GET['userId']."' and items.num=itemId and view=1 and saved!=1";
  $q="select * from users2items LEFT JOIN items ON items.num=users2items.itemId  where users2items.userId='".$_GET['userId']."' and saved!=1 and reference!=''";
$r=mysql_query($q) or die(mysql_error());
while($row=mysql_fetch_array($r)) {
$out.="<tR><td width=100><img src='/photos/thumbs/".$row['photo'].".jpg' width=100></td><td>".$row['descrfr']."</td><td width=50><a href='index.php?kind=item&action=edit&level=2&itemId=".$row['itemId']."'>".$row['reference']."</a></td><td width=50>".$row['date']."</td><td width=50>".$row['counter']."</td></tr>";
}
$out.="</table>";
echo $out;
?>
</div>
<?php

$html=ob_get_contents();
ob_end_flush();

@unlink($pdfpath);
convert_to_pdf($html,$pdfpath);

}else{
	
	
	if($_GET['deleteId']>0 && $_SESSION['isadmin']==1) {
		$q="DELETE FROM users WHERE id='".$_GET['deleteId']."'";
		$r=mysql_query($q);
		
		$q="DELETE FROM users2items WHERE userId='".$_GET['deleteId']."'";
		$r=mysql_query($q);
		echo "<script>document.location='index.php?kind=users';</script>";
	}
?>
<input type='button' value='exporter' onclick="document.location='tools/export.php?exp=users'" /><table  cellpadding=2 cellspacing=1>
<tr class='rank<?= $i; ?>'>
<th></th>  
<th><a href="index.php?kind=users&orderby=date" class=yellow>Date</a></th>  
<th><a href="index.php?kind=users&orderby=lastVisit " class=yellow>Dernière<br/>visite</a></th>  
 <th>Email</th>               
   <!--<th>langue</th>           
   <th>salutation</th>-->
 <th>firstname</th>               
   <th><a href="index.php?kind=users&orderby=lastname" class=yellow>lastname</a></th>                 
   <th>tel</th>               
  <!-- <th>zip</th>             
   <th>fax</th>                
   <th>street</th>               
   <th>number</th>                
   <th>city</th>                
   <th>country</th>            
   <th>company</th>
   <th><a href="index.php?kind=users&orderby=searchfor" class=yellow>recherche</a></th>
   <th>type</th>
   <th>commune</th>
   <th><a href="index.php?kind=users&orderby=price" class=yellow>price</a></th>-->
   <th>refs</th>
   <th></th>
   </tr>  
<?php
$i=0;
$q="select *,type.type_fr as type2, users.id as id from users, users2search, type where  users.id=users2search.userId and users2search.type=type.id ";
$q="select *,users.id as id from users  ";
if(isset($_GET['orderby'])) {
	$q.=" order by ".$_GET['orderby'];
}
else {
	$q.=" order by `lastVisit` DESC, `date` DESC ";
}


//echo $q;
$r=mysql_query($q) or die(mysql_error());
$oe=array('odd','even');
$o=0;
while($row=mysql_fetch_array($r)) {
if($row['location']=='innercity') { $loc='Bruxelles'; }
if($row['location']=='outercity') { $loc='Brabant'; }
if($i>1) {$i=0;}


?><tr class='rank<?= $i; ?> <?= $oe[$o];?>' >
 <td><a href='index.php?kind=users&userId=<?= $row['id']; ?>'><img src='../medias/b_edit.png' border=0/></a></td>
 <td><?= shakeDate($row['date']); ?></td> 
 <td> <?php if($row['lastVisit']!='0000-00-00 00:00:00') { echo $row['lastVisit']; } ?> </td>
 <td><a href="mailto:<?= $row['email']; ?>"><?= $row['email']; ?></a></td>               
   <!--<td> <?= $row['language'] ; ?>  </td>           
   <td>  <?= $row['salutation']; ?></td>-->
 <td><?= $row['firstname'] ; ?> </td>               
   <td> <?= $row['lastname']; ?></td>                 
   <td> <?= $row['tel'] ; ?></td>               
  <!-- <td> <?= $row['zip'] ; ?></td>             
   <td> <?= $row['fax'] ; ?></td>                
   <td> <?= $row['street'] ; ?></td>               
   <td> <?= $row['number']; ?></td>                
   <td> <?= $row['city'] ; ?></td>                
   <td> <?= $row['country'] ; ?> </td>            
   <td> <?= $row['company']; ?> </td>
   <td> <?= $row['type2']; ?> </td>
   <td> <?= $row['searchfor']; ?> </td>
   <td> <?= $loc; ?> </td>
   <td> <?= $row['price']; ?> </td>-->
  <td>  <?php
  $refs=array();
  $a=1;
   $tq="select * from users2items LEFT JOIN items ON items.num=users2items.itemId where users2items.userId='".$row['id']."' ";
   //echo $tq;
   $tr=mysql_query($tq) or die(mysql_error());
   while($trow=mysql_fetch_array($tr)) {
   		if($trow['itemId']>0 && $trow['reference']!='') {
	   $ttype=$row['type'];
	   if($ttype>3) {$ttype=1;}
   		$refs[]="<a href='index.php?kind=item&action=edit&level=2&itemId=".$trow['itemId']."' target='detail'>".$trow['reference']."</a>";
		}
		$a++;
   }
   echo implode('<br>',$refs);
   ?></td>
   <td><a href='#' onclick="deleteUser('<?= $row['id']; ?>')">delete</a></td>
   </tr>             
 <?php
 $o=!$o;
$i++;
}

?>
</table>
<?php
}
?>