<?php
require_once('../config.inc.php');
include('config.inc.php');
include('main.inc.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Immobili�re Le Lion</title>
<!--<link href="../css/main.css" type="text/css" rel="stylesheet" />
<link href="../css/admin.css" type="text/css" rel="stylesheet" />-->
<script src="js/AC_RunActiveContent.js" language="javascript" type="text/javascript"></script>
<script src="js/jquery-1.4.2.min.js" language="javascript" type="text/javascript"></script>
<script src="js/jquery-ui-1.8.2.custom.min.js" language="javascript" type="text/javascript"></script>
<script src="js/swfobject.js" language="javascript" type="text/javascript"></script>
<script src="js/jquery.uploadify.v2.1.0.min.js" language="javascript" type="text/javascript"></script>

<!-- tinyMCE -->
<script>
function saveMe() {
document.forms[0].submit();
}
</script>
<script language="javascript" type="text/javascript" src="tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script>tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		//plugins : "save",
		theme_advanced_buttons1_add : "save,separator,cut,copy,paste,pastetext,pasteword,separator,fontselect,fontsizeselect,separator,forecolor,backcolor",
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
	});</script>
<script src="js/admin.js" language="javascript" type="text/javascript"></script>
<link href="css/uploadify.css" type="text/css"  rel="stylesheet"   />
<link href="css/admin.css" type="text/css" rel="stylesheet"  />
</head>
<body>
<?php
if(!isset($_SESSION['isadmin'])) {

	switch($_POST['level']) {
		default:
?>
<form name='theForm' action='index.php' method='post'>
  <input type='hidden' name='level' value='1'>
  <table border=0 cellpadding=0 cellspacing=0 width=104 align=center>
    <tr>
      <td width="20"></td>
      <td width="168"></td>
    </tr>
    <tr>
      <td  colspan=2><img src="../medias/logo.gif" alt="Immobili&egrave;re Le Lion"  vspace="10" /></td>
    </tr>
    <tr>
      <td rowspan=5>&nbsp;</td>
      <td>Login</td>
    </tr>
    <tr>
      <td><input type='text' name='login' /></td>
    </tr>
    <tr>
      <td>Password</td>
    </tr>
    <tr>
      <td><input type='password' name='password' /></td>
    </tr>
    <tr>
      <td><input type='submit' value='login' /></td>
    </tr>
  </table>
</form>
<?php
		break;
		case 1:
			$q="select * from adusers where login='".$_POST['login']."' and password='".$_POST['password']."'";
			$r=mysql_query($q) or die(mysql_error());
			if(mysql_num_rows($r)==1) {
			$_SESSION['isadmin']=true;
			?>
			<script>document.location='index.php?kind=item&action=edit&level=0'; </script>
			<?php
		}
		else {
?>
<script>document.location='index.php'; </script>
<?php
		}
		break;
	}
}
else {

	if(isset($_POST['kind'])) {
		$_GET['kind']=$_POST['kind'];
	}
	if(isset($_POST['action'])) {
		$_GET['action']=$_POST['action'];
	}
	if(isset($_POST['level'])) {
		$_GET['level']=$_POST['level'];
	}
	if(!isset($_GET['kind'])) {
		$_GET['kind']='item';
	}
	if(!isset($_GET['action'])) {
		$_GET['action']='add';
	}
	
?>
<form name='theForm' method='post' action='index.php' enctype="multipart/form-data">
<input type='hidden' name='kind' value='<?= $_GET['kind']; ?>' />
<input type='hidden' name='action' value='<?= $_GET['action']; ?>' />

  <table border=0 celppading=0 cellspacing=0 width=100% align=center>
    <tr>
      <td><img src="../medias/logo.gif" alt="Immobili&egrave;re Le Lion" width="188" height="100" vspace="10" /></td>
	 </tr>
	 <tr>
      <td class='title' valign='bottom' style="border-bottom: solid 1px #CCCCCC;"><a href='index.php?kind=item&action=edit&level=0' class='title' style="<?=($_GET['kind']=='item'?'color: red;':'');?>">Biens</a> 
	  | <a href='index.php?kind=quartiers' class='title' style="<?=($_GET['kind']=='quartiers'?'color: red;':'');?>">Quartiers</a> 
	<!--  | <a href='index.php?kind=images' class='title' style="<?=($_GET['kind']=='images'?'color: red;':'');?>">Photos site</a> -->
	  | <a href='index.php?kind=textes' class='title' style="<?=($_GET['kind']=='textes'?'color: red;':'');?>">Textes site</a> 
	  | <a href='index.php?kind=users' class='title' style="<?=($_GET['kind']=='users'?'color: red;':'');?>">Utilisateurs</a> 
	<!--  | <a href='http://www.immo-lelion.be/phpmv2/' class='title' style="<?=($_GET['kind']=='stats'?'color: red;':'');?>">Statistiques</a> 
	  | <a href='index.php?kind=location' class='title'>Localit�s</a>--><br /><br /></td>
    </tr>
	
	<tr><td><?php include ('tools/'.$_GET['kind'].'.php'); ?></td></tr>
	<?php
	if($_GET['kind']=='item') {?><tr><td colspan=2><?php include ('tools/'.$_GET['action'].$_GET['kind'].'.php'); ?></td></tr>
	<?php
	}
	?>
  </table>
</form>
<?php
}
?>
</body>
</html>
