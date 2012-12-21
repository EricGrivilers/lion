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
<script>
function checkTheFields() {
	bloc_types_array=Array();
	for(i=0;i<document.theForm.bloc_types.length;i++) {
		if(document.theForm.bloc_types[i].selected) {
			bloc_types_array.push(document.theForm.bloc_types[i].value);
		}
	}
	
	flat_types_array=Array();
	for(i=0;i<document.theForm.flat_types.length;i++) {
		if(document.theForm.flat_types[i].selected) {
			flat_types_array.push(document.theForm.flat_types[i].value);
		}
	}
	house_types_array=Array();
	for(i=0;i<document.theForm.house_types.length;i++) {
		if(document.theForm.house_types[i].selected) {
			house_types_array.push(document.theForm.house_types[i].value);
		}
	}
	area_types_array=Array();
	for(i=0;i<document.theForm.area_types.length;i++) {
		if(document.theForm.area_types[i].selected) {
			area_types_array.push(document.theForm.area_types[i].value);
		}
	}
	document.theForm.bloc_values.value=bloc_types_array.join(',');
	document.theForm.flat_values.value=flat_types_array.join(',');
	document.theForm.house_values.value=house_types_array.join(',');
	document.theForm.area_values.value=area_types_array.join(',');
	if(document.theForm.email.value=="" || document.theForm.lastname.value=="" || document.theForm.firstname.value=="" || document.theForm.tel.value=="") {
	alert("Vous n'avez pas remplis tous les champs obligatoires");
	}
	else {
	document.theForm.submit();
	}
}

function getBudget(kind) {
	document.theForm.budget.options.length=0;
	if(kind=='rent') {
	budgets=Array("2","4","6");
	}
	else {
	budgets=Array("400","700","1.000","1.500");
	}
	document.theForm.budget.options[0]=new Option("<?= $lang[0]['lessthan']; ?> "+budgets[0]+".000 €", "<?= $lang[0]['lessthan']; ?> "+budgets[0]+".000", false, false);
	for(i=1;i<budgets.length;i++) {
	document.theForm.budget.options[i]=new Option("<?= $lang[0]['between']; ?> "+budgets[i-1]+" <?= $lang[0]['and']; ?> "+budgets[i]+".000 €", "<?= $lang[0]['between']; ?> "+budgets[i-1]+" <?= $lang[0]['and']; ?> "+budgets[i]+".000", false, false);
	}
	document.theForm.budget.options[i-1]=new Option("<?= $lang[0]['morethan']; ?> "+budgets[i-1]+".000 €", "<?= $lang[0]['morethan']; ?> "+budgets[i-1]+".000", false, false);
}
</script>
  <table border="0" width=100% cellpadding=0 cellspacing=0>
    <td align=right><img src='medias/corner.gif' alt="immo le lion - Brussels" /></td>
    </tr>
  </table>
  <div id='content' >
    <p align=center><b>
      <?= $_lang['4_1']['intro']; ?>
      </b></p>
  </div>
  <form method='GET' action='index.php' name='theForm'>
    <input type='hidden' name='level' value='1' />
    <input type='hidden' name='chap' value='4_1' />
    <input type='hidden' name='newItem' value='<?= $_GET['newItem']; ?>' />
	<input type='hidden' name='bloc_values' value='' />
	<input type='hidden' name='flat_values' value='' />
	<input type='hidden' name='house_values' value='' />
	<input type='hidden' name='area_values' value='' />
    <table border=0 class=detail cellpadding=5 celspacing=0 width=480>
      <tr>
        <td class='formlab'><?= $_lang['4_1']['sale']; ?></td>
        <td align=left><input type="radio" name="type" value="sale" onclick="getBudget('sale')" checked/></td>
        <td class='formlab'><?= $_lang['4_1']['rent']; ?></td>
        <td align=left><input type="radio" name="type" value="rent" onclick="getBudget('rent')"  /></td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang['4_1']['minimum_rooms']; ?></td>
        <td align=left><select name="rooms">
            <?php
		for($i=1;$i<10;$i++) {
		?>
            <option value="<?= $i; ?>">&nbsp;
            <?=$i; ?>
            &nbsp;</option>
            <?php
		}
		?>
          </select></td>
        <td class='formlab'><?= $_lang['4_1']['minimum_square']; ?></td>
        <td align=left><select name="square">
            <?php
		$squares=array(0,100,150,250,350);
		for($i=0;$i<count($squares)-1;$i++) {
		?>
            <option value="<?= $squares[$i+1]; ?>">
            <?= $lang[0]['between']; ?>
            <?= $squares[$i]; ?>
            <?= $lang[0]['and']; ?>
            <?= $squares[$i+1]; ?>
            m2</option>
            <?php
		}
		?>
            <option value="<?= $squares[$i]; ?>">
            <?= $lang[0]['morethan']; ?>
            <?= $squares[$i]; ?>
            m2</option>
          </select></td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang['4_1']['block']; ?>
          *</td>
        <td align=left colspan=3><select name="bloc_types" multiple />
          
          <?php
		for($i=0;$i<count($_lang['4_1']['block_types']);$i++) {
		?>
          <option value="<?= $_lang['4_1']['block_types'][$i]; ?>">
          <?= $_lang['4_1']['block_types'][$i]; ?>
          </option>
          <?php
		}
		
		?>
          </select></td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang['4_1']['flat']; ?>
          *
          <input type="checkbox" name="aflat" /></td>
        <td align=left colspan=3><select name="flat_types" multiple />
          
          <?php
		for($i=0;$i<count($_lang['4_1']['flat_types']);$i++) {
		?>
          <option value="<?= $_lang['4_1']['flat_types'][$i]; ?>">
          <?= $_lang['4_1']['flat_types'][$i]; ?>
          </option>
          <?php
		}
		
		?>
          </select></td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang['4_1']['house']; ?>
          *
          <input type="checkbox" name="ahouse" /></td>
        <td align=left colspan=3><select name="house_types" multiple />
          
          <?php
		for($i=0;$i<count($_lang['4_1']['house_types']);$i++) {
		?>
          <option value="<?= $_lang['4_1']['house_types'][$i]; ?>">
          <?= $_lang['4_1']['house_types'][$i]; ?>
          </option>
          <?php
		}
		
		?>
          </select></td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang['4_1']['ground']; ?>
          <input type="checkbox" name="aground" /></td>
        <td align=left colspan=3></td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang['4_1']['area']; ?></td>
        <td align=left colspan=3><select name="area_types" multiple />
          
          <?php
		for($i=0;$i<count($_lang['4_1']['area_types']);$i++) {
		?>
          <option value="<?= $_lang['4_1']['area_types'][$i]; ?>">
          <?= $_lang['4_1']['area_types'][$i]; ?>
          </option>
          <?php
		}
		
		?>
          </select></td>
      </tr>
      <tr>
        <td colspan=4>(*)
          <?= $_lang['4_1']['multiple']; ?></td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang['4_1']['budget']; ?></td>
        <td align=left colspan=3><select name="budget">
            <option></option>
          </select><script>getBudget('sale');</script></td>
      </tr>
    </table>
    <br />
    <table border=0 class=detail cellpadding=5 celspacing=0 width='480'>
      <tr>
        <td class='formlab'><?= $_lang[5]['title']; ?></td>
        <td align=left><select name='salutation' class='gender'>
            <option value='Mme' <?php if($row['salutation']=='Mme') { echo 'selected'; } ?>>
            <?= $_lang[5]['mrs']; ?>
            </option>
            <option value='M' <?php if($row['salutation']=='M') { echo 'selected'; } ?>>
            <?= $_lang[5]['mr']; ?>
            </option>
          </select></td>
        <td class='formlab'><?= $_lang[5]['language']; ?></td>
        <td align=left><input type='radio' name='language' checked />
          fr
          <input type='radio' name='language'  />
          en </td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang[5]['lastname']; ?>
          *</td>
        <td><input type='text' name='lastname'  value="<?=$row['lastname']; ?>"/></td>
        <td class='formlab'><?= $_lang[5]['firstname']; ?>
          *</td>
        <td><input type='text' name='firstname'   value="<?=$row['firstname']; ?>"/></td>
      </tr>
      <tr>
        <td class='formlab'>E-mail*</td>
        <td><input type='text' name='email'   value="<?=$row['email']; ?>"/></td>
        <td class='formlab'><?= $_lang[5]['phone']; ?>
          *</td>
        <td><input type='text' name='tel'   value="<?=$row['tel']; ?>"/></td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang[5]['street']; ?></td>
        <td><input type='text' name='street'   value="<?=$row['street']; ?>"/></td>
        <td class='formlab'><?= $_lang[5]['number']; ?></td>
        <td><input type='text' name='number'   value="<?=$row['number']; ?>"/></td>
      </tr>
      <tr>
        <td class='formlab'><?= $_lang[5]['zip']; ?>
        </td>
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
		?>
</textarea></td>
      </tr>
      <tr>
        <td colspan=4 class=detailb><input type='button'  onclick='checkTheFields()' value='<?= $_lang[5]['send']; ?>'/></td>
      </tr>
    </table>
  </form>
  <?php
  break;
  
  case 1:
  //foreach($_GET as $key=>$val) { echo $key.'=>'.$val.'<p>';  }
  $criterias="";
  $criterias.="Type:".$_GET['type']."\r\n";
  $criterias.="Quartier:".$_GET['bloc_values']."\r\n";
  if($_GET['aflat']=='on') {
  	$criterias.="Appartement:".$_GET['flat_values']."\r\n";
	}
	if($_GET['ahouse']=='on') {
  	$criterias.="Maison:".$_GET['house_values']."\r\n";
	}
	if($_GET['aground']=='on') {
  	$criterias.="Un terrain\r\n";
	}
$criterias.="Zone géographique:".$_GET['area_values']."\r\n";
  $criterias.="Budget:".$_GET['budget'].".000\r\n";
  $criterias.="Nombre de chambres:".$_GET['rooms']."\r\n";
  $criterias.="Surface:".$_GET['square']."m2\r\n";
  
  $message="email:".$_GET['email']."\r\nfirstname:".$_GET['firstname']."\r\nlastname:".$_GET['lastname']."\r\ntel:".$_GET['tel']."\r\n".$_GET['comments'];
  
  $message.="\r\nCritères:\r\n".$criterias;
  $message.="\r\nrue:".$_GET['street']."\r\nnum:".$_GET['number']."\r\ncode postal:".$_GET['zip']."\r\nville:".$_GET['city']."\r\npays:".$_GET['country']."\r\nfax:".$_GET['fax']."\r\n";
  //echo $message;
 if(mail('info@immo-lelion.be',"demande d'informations",$message,'From:'.$_GET['email'], "-finfo@immo-lelion.be")) {
  mail('ericgrivilers@hotmail.com',"demande d'informations",$message,'From:'.$_GET['email'], "-finfo@immo-lelion.be");
 
  ?>
  <br />
  <br />
  Merci pour tout ces renseignements.<br/>
  <br />
  Nous prendrons au plus vite contact avec vous.<br />
  <br />
  <?php
 }
  else {
   ?>
  <br />
  <br />
  Une erreur s'est produite lors de l'envoi du mail.<br/>
  Veuillez réessayer ou nous contacter par téléphone.<br />
  Merci.<br />
  <br />
  <?php
}
  break;
  
  }
  
  ?>
</div>
