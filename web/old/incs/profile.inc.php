<br />
<div><a class='price2' href='index.php?chap=5&level=1&tab=0'><?= $_lang[5]['select']; ?></a> | <a class='price2' href='index.php?chap=5&level=1&tab=1'><?= $_lang[5]['criteria']; ?></a> | <a class='price2' href='index.php?chap=5&level=1&tab=2'><?= $_lang[5]['details']; ?></a></div><br />
<?php


switch($_GET['tab']) {

default:
$q="select count(*) from items,users2items where items.num=users2items.itemId and users2items.userId='".$_SESSION['userId']."'";
$r=mysql_query($q) or die(mysql_error());
if(mysql_result($r, 0, 0)>0) { 
$rq="select * from items,users2items where items.num=users2items.itemId and users2items.userId='".$_SESSION['userId']."'";
	  $rr=mysql_query($rq) or die(mysql_error());
	  while($rrow=mysql_fetch_array($rr)) {
	  $refs.=$rrow['reference']."(".$rrow['num'].") | ";
	  }
?>
<div align=left><a href='index.php?chap=4&ref=<?= $refs; ?>' class=price><?= $_lang[5]['contactme']; ?></a></div>
<?php
}
else {
?>

<div align=left><a href='' class=price><?= $_lang[5]['noselection']; ?></a></div>
<?php
}
?><br />
<?php
	  if(isset($_GET['newItem'])) {
	  	$q="select * from users2items where userId='".$_SESSION['userId']."' and itemId='".$_GET['newItem']."'";
		$r=mysql_query($q) or die(mysql_error());
		if(mysql_affected_rows($Connect)<1) {
	 		$q="insert into users2items values('".$_SESSION['userId']."','".$_GET['newItem']."')";
	  		$r=mysql_query($q) or die(mysql_error());
			//rrrrrrrrrrrr
			include('incs/searchmail.inc.php');
			//rrrrrrrrrrrrrrr
		}
	  }
	  if(isset($_GET['removeItem'])) {
	  	$q="delete  from users2items where userId='".$_SESSION['userId']."' and itemId='".$_GET['removeItem']."'";
		$r=mysql_query($q) or die(mysql_error());
	  }
	  $q="select * from items,users2items where items.num=users2items.itemId and users2items.userId='".$_SESSION['userId']."'";
	  $r=mysql_query($q) or die(mysql_error());
	  while($row=mysql_fetch_array($r)) {
	  ?>
      <div id='item' align=right>
        <table border="0" width=100% cellpadding=0 cellspacing=0>
          <tr>
            <td width=20></td>
            <td width="120" align=left class='loc'></td>
            <td ></td>
            <td></td>
            <td width=10></td>
          </tr>
          <tr>
            <td rowspan=3 width=20></td>
            <td colspan=3 align=left class='loc'><?= $row['locfr'];?></td>
            <td ><img src='medias/corner.gif' alt="immo le lion - Brussels" /></td>
          </tr>
          <tr>
            <td rowspan=2 align=left><a href='index.php?chap=<?= $_GET['chap']; ?>&detailId=<?= $row['num']; ?>' class=price><img src='http://www.immo-lelion.be/photos/<?= $row['photo'];?>s.jpg' class='thumbnail' border='0'/></a></td>
            <td align='left' class='descro' colspan=2 ><?= $row['descrfr'];?></td>
            <td rowspan=2></td>
          </tr>
          <tr>
            <td class=price align=left><?= $tPrix;?>
              | <a href="index.php?chap=5&removeItem=<?= $row['num']; ?>" class=yellow>suprimer de ma sélection</a>
              <?php if($row['reference']!='') {?>
              (ref.:
              <?= $row['reference'];?>
              )
              <?php }?></td>
            <td align=right class='yellow'><a href='index.php?chap=1&detailId=<?= $row['num']; ?>' class=price><u>en savoir plus >></u></a><!--<br><input type=checkbox name=selelectedItems value="<?= $row['num'];?>" /> sélectionner ce bien--></td>
          </tr>
          <tr>
            <td colspan=5>&nbsp;</td>
          </tr>
        </table>
      </div>
      <?php
	  
	  
	  }
	  ?>
	  <div align='left'>
<a href='javascript:history.back()' class='price'>&lt;&lt; <?= $_lang[5]['back']; ?></a> | <a href='index.php?chap=4&ref=<?= $refs; ?>' class=price><?= $_lang[5]['contactme']; ?></a></div>

<?php
break;

case 1:
 $isprofile='true';
 
		
 $q="select * from users2search where userId='".$_SESSION['userId']."'";
$r=mysql_query($q) or die(mysql_error());
$_GET['type']=mysql_result($r,0,'type');
$_GET['location']=mysql_result($r,0,'location');
$_GET['prix']=mysql_result($r,0,'prix');
$_GET['searchfor']=mysql_result($r,0,'searchfor');
include('incs/search.php');
break;

case 2:
if($_SESSION['logged']==true) {
$q="select * from users where id='".$_SESSION['userId']."'";
$r=mysql_query($q) or die(mysql_error());
$row=mysql_fetch_array($r);
}
?>
<form method='GET' action='index.php' name='theForm'>
<input type='hidden' name='chap' value='5' />
<input type='hidden' name='level' value='1' />
<input type='hidden' name='tab' value='3' />
    <table border=0 class=detail cellpadding=5 celspacing=0>
      <tr>
        <td class='formlab'><?= $_lang[5]['language']; ?></td>
        <td><input type='radio' name='language' <?php if($_SESSION['language']=='fr') { echo 'checked' ;} ?> value='fr'/>
          fr
          <input type='radio' name='language'  <?php if($_SESSION['language']=='en') { echo 'checked' ;} ?> value='en'/>
          en </td>
        <td colspan=3><?= $_lang[5]['title']; ?> <select name='salutation'><option value='Mme' <?php if($row['salutation']=='Mme') { echo 'selected'; } ?>><?= $_lang[5]['mrs']; ?></option><option value='M' <?php if($row['salutation']=='M') { echo 'selected'; } ?>><?= $_lang[5]['mr']; ?></option></select></td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang[5]['lastname']; ?></td>
        <td><input type='text' name='lastname'  value="<?= $row['lastname']; ?>"/></td>
        <td class='formlab'><?= $_lang[5]['firstname']; ?></td>
        <td><input type='text' name='firstname'  value="<?= $row['firstname']; ?>" /></td>
      </tr>
      <tr>
        <td class='formlab'>E-mail</td>
        <td><input type='text' name='email'  value="<?= $row['email']; ?>" /></td>
        <td class='formlab'><?= $_lang[5]['phone']; ?></td>
        <td><input type='text' name='tel'   value="<?= $row['tel']; ?>"/></td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang[5]['street']; ?></td>
        <td><input type='text' name='street'  value="<?= $row['street']; ?>" /></td>
        <td class='formlab'><?= $_lang[5]['number']; ?></td>
        <td><input type='text' name='number'   value="<?= $row['number']; ?>"/></td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang[5]['zip']; ?></td>
        <td><input type='text' name='zip'  value="<?= $row['zip']; ?>" /></td>
        <td class='formlab'><?= $_lang[5]['city']; ?></td>
        <td><input type='text' name='city'  value="<?= $row['city']; ?>" /></td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang[5]['country']; ?></td>
        <td><input type='text' name='country'  value="<?= $row['country']; ?>" /></td>
        <td class='formlab'><?= $_lang[5]['fax']; ?></td>
        <td><input type='text' name='fax'  value="<?= $row['fax']; ?>" /></td>
      </tr>
      <tr>
        <td colspan=4>&nbsp;</td>
      </tr>
      <tr>
        <td colspan=4  align='center'><input type='submit'  value='<?= $_lang[5]['update']; ?>'/></td>
      </tr>
    </table>
  </form>
  <?php
break;

case 3:

$q="update users set firstname=\"".$_GET['firstname']."\",lastname=\"".$_GET['lastname']."\",email=\"".$_GET['email']."\",tel=\"".$_GET['tel']."\", fax=\"".$_GET['fax']."\", street=\"".$_GET['street']."\", number=\"".$_GET['number']."\",  city=\"".$_GET['city']."\",zip=\"".$_GET['zip']."\",country=\"".$_GET['country']."\",language=\"".$_GET['language']."\" ,salutation=\"".$_GET['salutation']."\" where id='".$_SESSION['userId']."'";
$r=mysql_query($q) or die(mysql_error());
?>
<div align=center class=login>Vos coordonnées ont été mises à jour.<br /><br /></div>
<?php
break;

}
?>