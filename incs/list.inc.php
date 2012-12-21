<?php
if($_GET['isprofile']=='true') {
	$q="select * from users2search where userId='".$_SESSION['userId']."'";
	$r=mysql_query($q) or die(mysql_error());
	if(mysql_affected_rows($Connect)==1) {
		$q="update users2search set type='".$_GET['type']."',price='".$_GET['prix']."',location='".$_GET['location']."',searchfor='".$searchfor."' where userId='".$_SESSION['userId']."'";
		$r=mysql_query($q) or die(mysql_error());
	}
	else {
		$q="insert into users2search (userId, type,price,location,searchfor) values ('".$_SESSION['userId']."','".$_GET['type']."','".$_GET['prix']."','".$_GET['location']."','".$searchfor."')";
		$r=mysql_query($q) or die(mysql_error());
	}
	$_SESSION['type']=$_GET['type'];
	$_SESSION['prix']=$_GET['prix'];
	$_SESSION['location']=$_GET['location'];
	$_SESSION['searchfor']=$searchfor;
	//rrrrrrrrrrrr
	include('incs/searchmail.inc.php');
			//rrrrrrrrrrrrrrr
}

?>
<table border=0 width='100%' cellpadding=0 cellspacing=0><tr><td align='center'>
<?php
if($_GET['newsearch']) {
?>
<div id='item'><table width='100%' cellpadding='0' cellspacing='0'><tr><td align=right><img src='medias/corner.gif' /></td></tr></table>
<?php

include('search.php');
?>
</div>
<?php
}
else if(!isset($_GET['type']) && !isset($_GET['type'])) {
?><div id='item'>
<table width='100%' cellpadding='0' cellspacing='0'><tr><td align=right><img src='medias/corner.gif' /></td></tr></table>
<?php
include('search.php');
?>
</div>
<?php
}
?>
</td></tr></table>


<?php
if(isset($_GET['type']) || isset($_GET['type'])) {	




if(!isset($_GET['orderby'])) {
	$_GET['orderby']='prix';
	$_SESSION['orderby']='prix';
}
else {
$_SESSION['orderby']=$_GET['orderby'];
}
if(!isset($_GET['start'])) {
$_GET['start']=0;
}


if($_GET['fulldisplay']) {
$limit='';
}
else {
$limit=" limit ".$_GET['start'].",10";
}
if($_GET['prix']) {
	$_GET['prix']=$_GET['prix'];
}
/*
if($_GET['orderby']) {

	if($_GET['orderby']=='prix') {$_GET['orderby']='prix';}
	
}
*/
if($_GET['type']) {
	$_GET['type']=$_GET['type'];
}
if($_GET['location']) {
	$_GET['location']=$_GET['location'];
}
$anyMoreWhere='';
$params='';
if($_GET['prix']) {
	$priceArray=explode('|',$_GET['prix']);
	$min=$priceArray[0];
	$max=$priceArray[1];
	$anyMoreWhere=" and prix>".$min." and prix<=".$max." ";
	$params.="&prix=".$_GET['prix'];
}
if($_GET['orderby']) {
	$params.="&orderby=".$_GET['orderby'];
}
if($_GET['type'] && $_GET['type']!='no') {
	$anyMoreWhere.=" and items.type='".$_GET['type']."' ";
	$params.="&type=".$_GET['type'];
}
if($_GET['location']) {
	if($_GET['location']=='innercity') {
	$anyMoreWhere.=" and locations.innercity='true' ";
	}
	else if ($_GET['location']=='outercity') {
	$anyMoreWhere.=" and locations.innercity='false' ";
	}
	$params.="&location=".$_GET['location'];
}
if($_GET['chap']==6) {
	$anyMoreWhere.=" and DATE_SUB(CURDATE(),INTERVAL 15 DAY) <= `update`";
}
if($_GET['chap']==1) {
	$tq="select count(*) from items,locations where items.zip=locations.zip and actif='Y' and location!='Y' ".$anyMoreWhere."";
	$q="select * from items,locations where items.zip=locations.zip and actif='Y' and location!='Y' ".$anyMoreWhere."  group by items.num order by ".$_SESSION['orderby']." asc,num asc ".$limit;
}
else if($_GET['chap']==6){
//news
if($_GET['searchfor']=='rent') {
	$Y="='Y'";
}
else {
	$Y="!='Y'";
}
	$tq="select count(*) from items,locations where items.zip=locations.zip and actif='Y' and location".$Y." ".$anyMoreWhere."";
	$q="select * from items,locations where items.zip=locations.zip and actif='Y' and location".$Y." ".$anyMoreWhere."  group by items.num order by ".$_SESSION['orderby']." asc,num asc ".$limit;
}
else {
	$tq="select count(*) from items,locations where items.zip=locations.zip and actif='Y' and location='Y' ".$anyMoreWhere."";
	$q="select * from items,locations where items.zip=locations.zip and actif='Y' and location='Y' ".$anyMoreWhere."  group by items.num order by ".$_SESSION['orderby']." asc,num asc ".$limit;
}

if(isset($_GET['reference']) && $_GET['reference']!='030/') {
$tq="select count(*) from items where items.reference='".$_GET['reference']."' and actif='Y' group by items.num";
$q="select * from items where items.reference='".$_GET['reference']."' and actif='Y'  group by items.num";
}
//echo $q;
$tr=mysql_query($tq) or die(mysql_error());
$m = mysql_result($tr, 0, 0); 
$r=mysql_query($q) or die(mysql_error());
if($m<=0) {
?>
<div id='item' align=right>
  <table border="0" width=100% cellpadding=0 cellspacing=0>
    <tr>
	<td align='center' class='descro' ><br /><br /><b><?= $_lang[0]['novacancy']; ?>.</b><br /><br /><br /><br /></td></tr></table></div>
<?php
}
while($row=mysql_fetch_array($r)) {
$tPrix= $row['prix'];
if($tPrix==99999999 || $tPrix<100) {
	$tPrix=$_lang[1]['ondemand'];
}
else {
	$tPrix=makePrice($tPrix)." &euro;";
}
if($row['surdemande']=='Y') {
	$tPrix=$_lang[1]['ondemand'];
}
if($row['vendu']=='Y') {
	if($row['location']=='Y') {
		$tPrix='<b>'.$_lang[1]['loue'].'</b>';
	}
	else {
		$tPrix='<b>'.$_lang[1]['vendu'].'</b>';
	}
}
if($row['enoption']=='Y') {
	$tPrix='<b>'.$_lang[1]['option'].'</b>';
}
?>
<div id='item' align=right>
  <table border="0" width=100% cellpadding=0 cellspacing=0>
    <tr>
	<td width=16 bgcolor="#020258"></td>
      <td width="250" align=left class='loc' bgcolor="#020258"></td>
	  <td width="16" align=left class='loc' bgcolor="#020258"></td>
	  <td width="309" bgcolor="#020258" ></td>
	  <td width="52" bgcolor="#020258"></td>
      <td width=133 bgcolor="#020258"></td>
      <td width=34 bgcolor="#020258"></td>
    </tr>
	 <tr>
	<td rowspan=3 width=16></td>
      <td colspan=4 align=left class='loc'><?= $row['locfr'];?></td>
	  
      <td ></td>
      <td valign="top" ><img src='medias/corner.gif' alt="immo le lion - Brussels" /></td>
    </tr>
    <tr>
      <td rowspan=3 align=left><?php if(file_exists('photos/'.$row['photo'].'.jpg')) { ?><a href='index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>&detailId=<?= $row['num']; ?>' class=price>
	  <img src='photos/<?= $row['photo'];?>.jpg' class='thumbnail' width=250 border='0'/></a><?php } else {?> <img src='medias/dummy.jpg' class='thumbnail' border='0'/><?php }?></td>
     
	  <td rowspan=2 align=left width=16>&nbsp;</td>
	  <td height="23" colspan=3 align='left' class='price' >
	    <?= $tPrix;?>
      </td>
	  <td rowspan=2></td>
    </tr>
	<?php if($_SESSION['language']=='fr') {
	?><tr>
	  <td height="23" colspan="3" align=left class='descro'>
	    <?= $row['descrfr'];?>
	  </td>
    </tr>
	<?php
	}
	else if(strlen($row['descruk'])>1) {
	?><tr>
	  <td height="23" colspan="3" align=left class='descro'>
	    <?= $row['descruk'];?>
	  </td>
    </tr>
	<?php
	}
	else {
	?>
	<tr>
	  <td height="23" colspan="3" align=left class='descro'>
	    <table border=0 class="detail" cellspacing=0 cellpadding=5 ><tr>
		 <td class=detaila align=right>surface:</td>
          <td class=detailb align=left><?php if($row['area']>0) {echo $row['area']."&nbsp;m²";} else { echo "N/A";} ?></td>
		  <td class=detaila  align=right>room(s):</td>
          <td class=detailb align=left><?= $row['rooms']; ?></td>
		  <td class=detaila  align=right>bathroom(s):</td>
		  <td class=detailb align=left><?= $row['bathrooms']; ?></td>
		  <td class=detaila  align=right>garage(s):</td>
		  <td class=detailb align=left><?= $row['garages']; ?></td>
		  <td class=detaila><?= $row['garden']; ?></td>
		  </tr></table>
	  </td>
    </tr>
	<?php
	}
	?>
	<tr>
	  <td></td>
	  <td align=left>&nbsp;</td>
	  <td colspan="2" align=left class=price><a href="index.php?<?= $params; ?>&chap=5&newItem=<?= $row['num']; ?>" class=yellow><?= $_lang[0]['addtofavorite']; ?></a>
        <?php if($row['reference']!='') {?>
        (ref.:
        <?= $row['reference'];?>
        )
      <?php }?></td>
	  <td align="right"><span class="price"><a href='index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>&detailId=<?= $row['num']; ?>' class=price><u><?= $_lang[0]['details']; ?> >></u></a></span></td>
	  <td></td>
    </tr>
	<tr><td colspan=7>&nbsp;</td></tr>
  </table>
</div>
<?php
}

if(!isset($_GET['fulldisplay'])) {
?>
<div id='item' align=center> <div class='newsearch'><a href='index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>&newsearch=true' class='yellow'><?= $_lang[0]['newsearch']; ?></a></div><br /><a href='index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>&fulldisplay=true' class='yellow'><?= $_lang[0]['showall']; ?></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;<a href='index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>&orderby=prix' class='yellow'><?= $_lang[0]['showbyprice']; ?></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;<a href='index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>&orderby=locfr' class='yellow'><?= $_lang[0]['showbylocality']; ?></a><br />
<?php
$currentPage=($_GET['start']+10)/10;
if($_GET['start']>0) {?>
<a href="index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>&start=<?= $_GET['start']-10; ?>&searchfor=<?= $_GET['searchfor'] ; ?>"><img border='0' src='medias/back.gif' hspace=2/ ></a>
<?
}
if(!isset($_GET['fulldisplay'])) {
	for($i=1;$i<($m/10)+1;$i++) {
	$isonoff='';
	if($currentPage==$i) {
	$isonoff='_on';
	}
		?>
			&nbsp;<a href="index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>&start=<?= ($i-1)*10; ?>" class='yellow<?= $isonoff; ?>'><?= $i; ?></a>&nbsp;
		<?
	}
}

if($_GET['start']<$m-10 ) {?>
<a href="index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>&start=<?= $_GET['start']+10; ?>&searchfor=<?= $_GET['searchfor'] ; ?>"><img border='0' src='medias/next.gif' hspace=2 /></a>
<?
}
?>
</div>
<?php
}
else {
?>
<div id='item' align=center><div class='newsearch'><a href='index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>&newsearch=true' class='yellow'><?= $_lang[0]['newsearch']; ?></a></div><br /><a href='index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>' class='yellow'><?= $_lang[0]['showby10']; ?></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;<a href='index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>&fulldisplay=true&orderby=prix' class='yellow'><?= $_lang[0]['showbyprice']; ?></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;<a href='index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>&fulldisplay=true&orderby=locfr' class='yellow'><?= $_lang[0]['showbylocality']; ?></a><br />
<?php
}



}
?>