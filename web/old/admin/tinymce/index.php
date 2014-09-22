<?php
//tinymce/index.php?filekind=textarea&fieldname='+field+'&content='+content,'editor','width=380,height=380');

if(!isset($elm1)) {$elm1=$content;}
if($filekind=='textarea') {
	if(isset($elm1)) {?>
	<script>
		window.opener.theForm.<?php echo $fieldname; ?>.value="<?php echo htmlentities($elm1); ?>";
		</script>
		<?php
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>editor</title>
<!-- tinyMCE -->
<script>
function saveMe() {
document.forms[0].submit();
}
</script>
<script language="javascript" type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<!-------------<script language="javascript" type="text/javascript">
	// Notice: The simple theme does not use all options some of them are limited to the advanced theme
	tinyMCE.init({
		mode : "textareas",
		theme : "simple"
	});
</script>---------->
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		plugins : "save",
		theme_advanced_buttons1_add_before : "save,separator",
		//theme_advanced_buttons1_add : "fontselect,fontsizeselect",
		//theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,zoom,separator,forecolor,backcolor",
		//theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
		//theme_advanced_buttons3_add_before : "tablecontrols,separator",
		//theme_advanced_buttons3_add : "emotions,iespell,flash,advhr,separator,print,separator,ltr,rtl,separator,fullscreen",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		content_css : "example_full.css"
	    //plugin_insertdate_dateFormat : "%Y-%m-%d",
	   // plugin_insertdate_timeFormat : "%H:%M:%S",
		//extended_valid_elements : "hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
		//external_link_list_url : "example_link_list.js",
		//external_image_list_url : "example_image_list.js",
		//flash_external_list_url : "example_flash_list.js",
		//file_browser_callback : "fileBrowserCallBack",
		//paste_use_dialog : false,
		//theme_advanced_resizing : true,
		//theme_advanced_resize_horizontal : false,
		//theme_advanced_link_targets : "_something=My somthing;_something2=My somthing2;_something3=My somthing3;"
	});

	function fileBrowserCallBack(field_name, url, type, win) {
		// This is where you insert your custom filebrowser logic
		alert("Filebrowser callback: field_name: " + field_name + ", url: " + url + ", type: " + type);

		// Insert new URL, this would normaly be done in a popup
		win.document.forms[0].elements[field_name].value = "someurl.htm";
	}
</script>
<!-- /tinyMCE -->

</head>
<body>
<table border=0 cellpadding=0 cellspacing=0 align=center><tr><td>
<form method="post" action="index.php">
<input type=hidden name=filekind value=<?php echo $filekind; ?> />
<input type=hidden name=fieldname value=<?php echo $fieldname; ?> />
<textarea id="elm1" name="elm1" rows="20" cols="40">
	<?php echo $elm1; ?>
</textarea>
</form>
</td></tr></table>
</body>
</html>