<?php


if($_POST['homeFr']) {
	$q="UPDATE lion_contents SET content=\"".mysql_real_escape_string($_POST['homeFr'])."\" WHERE contentId='10'";
	$r=mysql_query($q) or die(mysql_error());
	//echo "------------------".$q."-------------<br/>";	
	
	$q="UPDATE lion_contents SET content=\"".mysql_real_escape_string($_POST['homeEn'])."\" WHERE contentId='11'";
	$r=mysql_query($q) or die(mysql_error());	
	//echo "------------------".$q."-------------<br/>";	
	
	$q="UPDATE lion_contents SET content=\"".mysql_real_escape_string($_POST['internationalEn'])."\" WHERE contentId='8'";
	$r=mysql_query($q) or die(mysql_error());	
	//echo "------------------".$q."-------------<br/>";	
}

$q="SELECT * FROM lion_contents WHERE contentId=10";
$r=mysql_query($q) or die(mysql_error());
$fr=mysql_fetch_array($r);

$q="SELECT * FROM lion_contents WHERE contentId=11";
$r=mysql_query($q) or die(mysql_error());
$en=mysql_fetch_array($r);

//makeselect($valdisplay,$selectname,$arrayval,$rowselected,$selectclass,$extraparams,$defaultdisplay='',$excludeValues="");
?>
</form>
<h2>Homepage</h2>
<table>
<tr><td>Fr</td><td>En</td></tr>
<tr><td><textarea id="homeFr" name="homeFr" rows="20" cols="70" style="margin-left: auto; margin-right: auto;"><?php echo html_entity_decode($fr['content']); ?></textarea></td>
<td><textarea id="homeEn" name="homeEn" rows="20" cols="70" style="margin-left: auto; margin-right: auto;"><?php echo html_entity_decode($en['content']); ?></textarea></td>
</tr>
</table>

<?php



$q="SELECT * FROM lion_contents WHERE contentId=8";
$r=mysql_query($q) or die(mysql_error());
$en=mysql_fetch_array($r);

?>

<h2>International</h2>
<table>
<tr><td>Fr</td><td>En</td></tr>
<tr><td></td>
<td><textarea id="internationalEn" name="internationalEn" rows="20" cols="70" style="margin-left: auto; margin-right: auto;"><?php echo html_entity_decode($en['content']); ?></textarea></td>
</tr>
</table>

<input type='submit' value="Mettre à jour" />

</form>