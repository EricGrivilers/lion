<div id='item' align=center>
<table border="0" width=100% cellpadding=0 cellspacing=0>
   <td align=right><img src='medias/corner.gif' alt="immo le lion - Brussels" /></td>
    </tr></table>
  <?php
if($_SESSION['logged']!=true) {

switch($_GET['level']) {
default:

?>

  <b>Pour accéder à votre compte et consulter/modifier votre sélection, veuillez vous identifier.</b> <br />
  <form method='GET' action='index.php' name='theForm'>
    <input type='hidden' name='level' value='1' />
	<input type='hidden' name='chap' value='5' />
	 <input type='hidden' name='newItem' value='<?= $_GET['newItem']; ?>' />
    <table border=0 class=detail cellpadding=5 celspacing=0>
      <tr>
        <td><?= $_lang[5]['login']; ?></td>
        <td><input type='text' name='login'  /></td>
      </tr>
      <tr>
        <td><?= $_lang[5]['password']; ?></td>
        <td><input type='password' name='password'  /></td>
      </tr>
      <tr>
        <td colspan=2 class=detaila><input type='submit'  value='login'/></td>
      </tr>
    </table>
  </form>
  Pas encore inscrit ? <a href='javascript:document.theForm.level.value=2;document.theForm.submit()' class='yellow'>Cliquez ici</a>. <br />
  <br />
  <?php
break;
case 1:

$q="select * from users where login='".$_GET['login']."' and password='".$_GET['password']."'";
$r=mysql_query($q) or die(mysql_error());
if(mysql_affected_rows($Connect)==1) {

$_SESSION['logged']=true;
$_SESSION['firstname']=mysql_result($r,0,'firstname');
$_SESSION['phone']=mysql_result($r,0,'tel');
$_SESSION['lastname']=mysql_result($r,0,'lastname');
$_SESSION['email']=mysql_result($r,0,'email');
$_SESSION['userId']=mysql_result($r,0,'id');

$aq="selec * from users2search where userId='".mysql_result($r,0,'id')."'";
$ar=mysql_query($aq) or die(mysql_error());
$_SESSION['type']=mysql_result($ar,0,'type');
$_SESSION['prix']=mysql_result($ar,0,'prix');
$_SESSION['location']=mysql_result($ar,0,'location');
$_SESSION['searchfor']=mysql_result($ar,0,'searchfor');

$tq="update users set date='".date(Ymd)."' where id='".mysql_result($r,0,'id')."'";
$tr=mysql_query($tq) or die(mysql_error());
## main script
include('incs/profile.inc.php');
}
else {
?>
  <form method='GET' action='index.php' name='theForm'>
    <input type='hidden' name='level' value='4' />
	<input type='hidden' name='chap' value='5' />
	<input type='hidden' name='newItem' value='<?= $_GET['newItem']; ?>' />
    <table border=0 class=detail cellpadding=5 celspacing=0>
      <tr>
        <td>Nous n'avons pu retrouver vos informations.<br />
          <br />
          Veuillez vérifiez votre combinaison login/password.<br />
          <br />
          <a href='javascript:document.theForm.submit();' class=yellow>Cliquez ici</a> pour recevoir vos login/password à votre adresse email.</td>
      </tr>
    </table>
  </form>
  <?php
}
break;

case 2:
?>
  <form method='GET' action='index.php' name='theForm'>
    <input type='hidden' name='level' value='3' />
	<input type='hidden' name='chap' value='5' />
	<input type='hidden' name='newItem' value='<?= $_GET['newItem']; ?>' />
    <table border=0 class=detail cellpadding=5 celspacing=0>
      <tr>
        <td class='formlab'>login*</td>
        <td><input type='text' name='login'  /></td>
        <td class='formlab'>password*</td>
        <td><input type='password' name='password'  /></td>
      </tr>
      <tr>
        <td colspan=4>&nbsp;</td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang[5]['language']; ?></td>
        <td><input type='radio' name='language' checked value='fr'/>
          fr
          <input type='radio' name='language'  value='en'/>
          en </td>
        <td colspan=3></td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang[5]['lastname']; ?>*</td>
        <td><input type='text' name='lastname'  /></td>
        <td class='formlab'><?= $_lang[5]['firstname']; ?>*</td>
        <td><input type='text' name='firstname'  /></td>
      </tr>
      <tr>
        <td class='formlab'>E-mail*</td>
        <td><input type='text' name='email'  /></td>
        <td class='formlab'><?= $_lang[5]['phone']; ?>*</td>
        <td><input type='text' name='tel'  /></td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang[5]['street']; ?></td>
        <td><input type='text' name='street'  /></td>
        <td class='formlab'><?= $_lang[5]['number']; ?></td>
        <td><input type='text' name='number'  /></td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang[5]['zip']; ?> </td>
        <td><input type='text' name='zip'  /></td>
        <td class='formlab'><?= $_lang[5]['city']; ?></td>
        <td><input type='text' name='city'  /></td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang[5]['country']; ?></td>
        <td><input type='text' name='country'  /></td>
        <td class='formlab'>Fax</td>
        <td><input type='text' name='fax'  /></td>
      </tr>
      <tr>
        <td colspan=4>&nbsp;</td>
      </tr>
      <tr>
        <td colspan=4 class=detailb><input type='submit'  value='register'/></td>
      </tr>
    </table>
  </form>
  <?php
break;

case 3:
$q="select * from users where email='".$_GET['email']."'";
$r=mysql_query($q) or die(mysql_error());
if(mysql_affected_rows($Connect)<1) {
$q="insert into users (login,password,firstname,lastname,email,tel, fax, street,city,zip,country,language,date) values (\"".$_GET['login']."\",\"".$_GET['password']."\",\"".$_GET['firstname']."\",\"".$_GET['lastname']."\",\"".$_GET['email']."\",\"".$_GET['tel']."\", \"".$_GET['fax']."\", \"".$_GET['street']."\",\"".$_GET['city']."\",\"".$_GET['zip']."\",\"".$_GET['country']."\",\"".$_GET['language']."\",'".date('Ymd')."')";
$r=mysql_query($q) or die(mysql_error());
$tId=mysql_insert_id();
$_SESSION['logged']=true;
$_SESSION['firstname']=$_GET['firstname'];
$_SESSION['lastname']=$_GET['lastname'];
$_SESSION['userId']=$tId;
$_SESSION['email']=$_GET['email'];
$_SESSION['phone']=$_GET['tel'];

$q="insert into users2items (userId) values('".$tId."')";
$r=mysql_query($q) or die(mysql_error());

$q="insert into users2search (userId,type) values('".$tId."','4')";
$r=mysql_query($q) or die(mysql_error());

$message="login:".$_GET['login']."\n\rpassword:".$_GET['password']."\n\rfirstname:".$_GET['firstname']."\n\rlastname:".$_GET['lastname']."\n\remail:".$_GET['email']."\n\rtel:".$_GET['tel']."\n\rfax:".$_GET['fax']."\n\rstreet:".$_GET['street']."\n\rcity:".$_GET['city']."\n\rzip:".$_GET['zip']."\n\rcountry:".$_GET['country']."\n\rlangue:".$_GET['language']."\n\r";

$message2="Vous avez bien été enregistré. Nous mettons tout en oeuvre pour trouver au plus vite un bien qui réponde à vos souhaits.\n\rVotre login:".$_GET['login']."\n\rVotre mot de passe:".$_GET['password']."\n\r\n\rhttp://www.immo-lelion.be\n\rTel: +32 2 672 71 11\n\r\n\rImmobilière LE LION s.a.  -  Avenue Delleur 8  -  B-1170  Bruxelles";
mail('info@immo-lelion.be','nouvel utilisateur',$message,'from:'.$_GET['email']);
mail($_GET['email'],'Immobilière Le Lion',$message2,'from:info@immo-lelion.be');
?>
  <table border=0 class=detail cellpadding=5 celspacing=0>
    <tr>
      <td><?= $_lang[5]['registered']; ?></td>
    </tr>
  </table>
  <br />
  <?php
  }
  else {
  ?>
  <br />
  <table border=0 class=detail cellpadding=5 celspacing=0>
    <tr>
      <td>Un compte avec cette adresse email a déja été créé.<br />
        <br />
        <a href='' class=yellow>Cliquez ici</a> pour recevoir vos login/password à cette adresse.</td>
    </tr>
  </table>
  <br />
  <?php
  }
break;

case 4:
?>
  <form method='GET' action='index.php' name='theForm'>
    <input type='hidden' name='level' value='5' />
	<input type='hidden' name='chap' value='5' />
	<input type='hidden' name='newItem' value='<?= $_GET['newItem']; ?>' />
    <table border=0 class=detail cellpadding=5 celspacing=0>
      <tr>
        <td class='formlab'>E-mail*</td>
        <td><input type='text' name='email'  /></td>
      </tr>
     
      <tr>
        <td colspan=4>&nbsp;</td>
      </tr>
      <tr>
        <td colspan=4 class=detailb><input type='submit'  value='envoyer'/></td>
      </tr>
    </table>
  </form>
  <?php
break;

}
}
else {
include('incs/profile.inc.php');
}

?>
</div>
