<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Full featured example</title>
<!-- TinyMCE -->
<script language="javascript" type="text/javascript" src="jscripts/tiny_mce/tiny_mce_src.js"></script>
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
<!-- /TinyMCE -->
</head>
<body>

<form method="post" action="index_advanced.php?save=true">
	<textarea id="elm1" name="elm1" rows="15" cols="15" style="width: 100%">
	&lt;span class=&quot;example1&quot;&gt;Test header 1&lt;/span&gt;&lt;br /&gt;
	&lt;span class=&quot;example2&quot;&gt;Test header 2&lt;/span&gt;&lt;br /&gt;
	&lt;span class=&quot;example3&quot;&gt;Test header 3&lt;/span&gt;&lt;br /&gt;
	Some &lt;b&gt;element&lt;/b&gt;, this is to be editor 1. &lt;br /&gt; This editor instance has a 100% width to it.
	&lt;p&gt;Some paragraph. &lt;a href=&quot;http://www.sourceforge.net&quot;&gt;Some link&lt;/a&gt;&lt;/p&gt;
	&lt;img src=&quot;logo.jpg&quot;&gt;
	</textarea>
	<!--<br />
	<input type="submit" name="save" value="Submit" />
	<input type="reset" name="reset" value="Reset" />-->
</form>

</body>
</html>