<?php
include('../../incs/main.inc.php');
if(!isset($alink)){$alink='0';}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Document sans titre</title>
</head>

<body>
<script>
function createLink() {
	//linkArray=Array();
	linkArray=document.theForm.alink.value.split('|');
	for(i=0;i<document.theForm._parent.length+2;i++) {
	//alert(i);
		if(document.theForm._parent[i].selected) {
		//alert(document.theForm._parent[i].value);
			t=document.theForm._parent[i].value.split('|');
			linkArray.push(t[0]);
			//alert(t[0]);
			break;
		}
	}
	//alert(linkArray.toString());
	document.theForm.parentId.value=t[0];
	document.theForm.alink.value=linkArray.join('|');
	//alert(document.theForm.alink.value);
	document.theForm.submit();
}
function insertLink() {
	t=document.theForm.alink.value.split('|');
	alink='index.php?chapId='+t[1]+'&itemId='+t[2];
	window.opener.theForm.<?php echo $fieldname; ?>.value=alink;
	window.close();
		
}
</script>
<form name="theForm" action="link_browser.php" method='post'>
<input type='hidden' name='parentId' value='0'>
<input type='hidden' name='fieldname' value='<?php echo $fieldname; ?>'>
<input type='hidden' name='language' value='<?php echo $language;?>'>
<input type='hidden' name='alink' value='<?php echo $alink; ?>'>
<table border=0 cellpadding=0 cellspacing=0 width=90%>
<tr><td><select name="_parent" onChange="createLink()">

<?php
if(!isset($_parent)) {
	$parentId=0;
	$parentName='Browse site';
}
else {
	$_parent=explode('|',$_parent);
	$parentId=$_parent[0];
	$parentName=$_parent[1];
}
?>
<option><?php echo $parentName; ?></option>
<option>-----------</option>
<?php
switch($parentId) {
	default:
		$q="select * from city2_chapters where parent='".$parentId."'";
	break;
	
	case 4://galleries
		$q="select *,id AS rank from city2_galleries";
	break;
}
echo $q;
$r=mysql_query($q) or die(mysql_error());
while($row=mysql_fetch_array($r)) {
?>
<option value='<?php echo $row['rank']."|".$row[$language]; ?>'><?php echo $row[$language]; ?></option>
<?php
}
?>
</select></td><td width=1%><input type='button' onclick='insertLink()' value='insert link'></td></tr></table>
</form>
</body>
</html>
