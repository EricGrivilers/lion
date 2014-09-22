<?php
include('../../incs/main.inc.php');
echo 'hhh='.$fieldname;
switch($filekind) {
	case 'textarea':
		if($save==true) {
		echo $elm1;
		?>
			<script>
				window.opener.theForm.<?php echo $fieldname; ?>.value="<?php echo $elm1; ?>";
			</script>
		<?php
		$content=$elm1;
		}
	break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Simple example</title>
<!-- tinyMCE -->
<script language="javascript" type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	// Notice: The simple theme does not use all options some of them are limited to the advanced theme
	tinyMCE.init({
		mode : "exact",
		theme : "simple",
		//mode : "exact",
		elements : "elm1",
		//insertlink_callback : "customInsertLink",
		//insertimage_callback : "customInsertImage",
		save_callback : "customSave"
	});
</script>
<!-- /tinyMCE -->

</head>
<body>
<script>
function saveMe() {
alert(document.forms[0].elm1.value);
}</script>
<table border=0 cellpadding=0 cellspacing=0 align=center><tr><td>
<form method="post" action="index.php">
<input type=hidden name=save value='true' />
<input type=hidden name=filekind value="<?php echo $filekind; ?>">
<input type=hidden name=fieldname value="<?php echo $fieldname; ?>">
<input type=hidden name=content value="">
<textarea name="elm1" rows="20" cols="40">
	<?php echo $content; ?>
</textarea>

</form>
</td></tr></table>
</body>
</html>