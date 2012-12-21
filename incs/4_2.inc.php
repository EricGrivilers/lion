<div id='item' align=center>
<?php
switch($_GET['level']) {
default:
if($_SESSION['logged']==true) {
$q="select * from users where id='".$_SESSION['userId']."'";
$r=mysql_query($q) or die(mysql_error());
$row=mysql_fetch_array($r);
}
?>

<table border="0" width=100% cellpadding=0 cellspacing=0>
   <td align=right><img src='medias/corner.gif' alt="immo le lion - Brussels" /></td>
    </tr></table><div id='content' ><p align=center><b><?= $_lang[4]['intro']; ?></b></p></div>
<form method='GET' action='index.php' name='theForm'>
    <input type='hidden' name='level' value='1' />
	<input type='hidden' name='chap' value='4_2' />
	<input type='hidden' name='newItem' value='<?= $_GET['newItem']; ?>' />
    <table border=0 class=detail cellpadding=5 celspacing=0>
      
     
      <tr>
	  <td class='formlab'><?= $_lang[5]['title']; ?></td>
        <td align=left><select name='salutation' class='gender'><option value='Mme' <?php if($row['salutation']=='Mme') { echo 'selected'; } ?>><?= $_lang[5]['mrs']; ?></option><option value='M' <?php if($row['salutation']=='M') { echo 'selected'; } ?>><?= $_lang[5]['mr']; ?></option></select></td>
        <td class='formlab'><?= $_lang[5]['language']; ?></td>
        <td align=left><input type='radio' name='language' checked />
          fr
          <input type='radio' name='language'  />
          en </td>
       
      </tr>
      <tr>
        <td class='formlab'><?= $_lang[5]['lastname']; ?>*</td>
        <td><input type='text' name='lastname'  value="<?=$row['lastname']; ?>"/></td>
        <td class='formlab'><?= $_lang[5]['firstname']; ?>*</td>
        <td><input type='text' name='firstname'   value="<?=$row['firstname']; ?>"/></td>
      </tr>
      <tr>
        <td class='formlab'>E-mail*</td>
        <td><input type='text' name='email'   value="<?=$row['email']; ?>"/></td>
        <td class='formlab'><?= $_lang[5]['phone']; ?>*</td>
        <td><input type='text' name='tel'   value="<?=$row['tel']; ?>"/></td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang[5]['street']; ?></td>
        <td><input type='text' name='street'   value="<?=$row['street']; ?>"/></td>
        <td class='formlab'><?= $_lang[5]['number']; ?></td>
        <td><input type='text' name='number'   value="<?=$row['number']; ?>"/></td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang[5]['zip']; ?> </td>
        <td><input type='text' name='zip'   value="<?=$row['zip']; ?>"/></td>
        <td class='formlab'><?= $_lang[5]['city']; ?></td>
        <td><input type='text' name='city'   value="<?=$row['city']; ?>"/></td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang[5]['country']; ?></td>
        <td><input type='text' name='country'   value="<?=$row['country']; ?>"/></td>
        <td class='formlab'>Fax</td>
        <td><input type='text' name='fax'   value="<?=$row['fax']; ?>"/></td>
      </tr>
      <tr>
        <td colspan=4>&nbsp;</td>
      </tr>
	  <tr>
        <td class='formlab' colspan=2><?= $_lang[5]['comments']; ?></td>
        <td colspan=2></td>
      </tr>
	   <tr>
        <td class='formlab'></td>
        <td colspan=3 align=left><textarea cols=40 rows=6 name='comments'><?php
		if($_GET['type']) {
			?>type:<?= $_GET['type']; ?>;
<?php
		}
		
		if($_GET['prix']) {
			?>price range:<?= $_GET['prix']; ?>;
<?php
		}
		if($_GET['location']) {
			?>location:<?= $_GET['location']; ?>;
<?php
		}
		if($_GET['ref']) {
			?>ref:<?= $_GET['ref']; ?>;
<?php
		}
		?></textarea></td>
      </tr>
	 
      <tr>
        <td colspan=4 class=detailb><input type='submit'  value='<?= $_lang[5]['send']; ?>'/></td>
      </tr>
    </table>
  </form>
  
  <?php
  break;
  
  case 1:
  $message="email:".$_GET['email']."\r\nfirstname:".$_GET['firstname']."\r\nlastname:".$_GET['lastname']."\r\ntel:".$_GET['tel']."\r\n".$_GET['comments'];
  if(mail('info@immo-lelion.be',"demande d'informations",$message,'From:'.$_GET['email'], "-finfo@immo-lelion.be")) {
// if(mail('ericgrivilers@hotmail.com','test','test',"From: eric@135.be","-finfo@immo-lelion.be")) {
  mail('ericgrivilers@hotmail.com',"demande d'informations",$message,'From:'.$_GET['email'], "-finfo@immo-lelion.be");
 //mail('info@immo-lelion.be',"demande d'informations",$message,'From:'.$_GET['email'], "-finfo@immo-lelion.be");
  ?>
  <br />
  <br />
  Nous prendrons au plus vite contact avec vous.<br />
  <br />
  <?php
 }
  else {
   ?>
  <br />
  <br />
  Une erreur s'est produite lors de l'envoi du mail.<br/>Veuillez réessayer ou nous contacter par téléphone.<br />Merci.<br />
  <br />
  <?php
  }
  break;
  
  }
  
  ?>
</div>
