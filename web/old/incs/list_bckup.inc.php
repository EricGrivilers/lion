<table border=0 width='100%' cellpadding=0 cellspacing=0><tr><td align='center'>
<?php
include('search.php');
?>
</td></tr></table>


<?php
	
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
if($_GET['price']) {
	$_GET['price']=$_GET['price'];
}
if($_GET['orderby']) {
	$_GET['orderby']=$_GET['orderby'];
}
if($_GET['type']) {
	$_GET['type']=$_GET['type'];
}
if($_GET['location']) {
	$_GET['location']=$_GET['location'];
}
$anyMoreWhere='';
$params='';
if($_GET['price']) {
	$priceArray=explode('|',$_GET['price']);
	$min=$priceArray[0];
	$max=$priceArray[1];
	$anyMoreWhere=" and prix>=".$min." and prix<=".$max." ";
	$params.="&price=".$_GET['price'];
}
if($_GET['type']) {
	//$anyMoreWhere.=" ";
	$params.="&type=".$_GET['type'];
}
if($_GET['location']) {
	$anyMoreWhere.=" and zip2item.locationZip=".$_GET['location']." ";
	$params.="&location=".$_GET['location'];
}
if($_GET['chap']==1) {
	$tq="select count(*) from items,zip2item where items.num=zip2item.itemId and actif='Y' and location!='Y' ".$anyMoreWhere."";
	$q="select * from items,zip2item where  items.num=zip2item.itemId and actif='Y' and location!='Y' ".$anyMoreWhere." order by ".$_SESSION['orderby']." asc,num asc ".$limit;
}
else {
	$tq="select count(*) from items,zip2item where items.num=zip2item.itemId and actif='Y' and location='Y' ".$anyMoreWhere."";
	$q="select * from items,zip2item where items.num=zip2item.itemId and actif='Y' and location='Y' ".$anyMoreWhere." order by ".$_SESSION['orderby']." asc,num asc ".$limit;
}

$tr=mysql_query($tq) or die(mysql_error());
$m = mysql_result($tr, 0, 0); 
$r=mysql_query($q) or die(mysql_error());
if($m<=0) {
?>
<div id='item' align=right>
  <table border="0" width=100% cellpadding=0 cellspacing=0>
    <tr>
	<td align='center' class='descro' ><br /><br /><b>Aucun bien, actuellement, ne correspond à votre demande.<br />Néanmoins, tous nos biens ne sont pas en ligne, n'hésitez donc pas à <a href='index.php?<?= $params; ?>&chap=4' class='yellow'>nous contacter</a>.</b><br /><br /><br /><br /></td></tr></table></div>
<?php
}
while($row=mysql_fetch_array($r)) {
$tPrix= $row['prix'];
if($tPrix==99999999 || $tPrix<100) {
$tPrix='prix sur demande';
}
else {
$tPrix=makePrice($tPrix)." &euro;";
}
if($row['vendu']=='Y') {
$tPrix='<b>V E N D U</b>';
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
      <td rowspan=3 align=left><a href='index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>&detailId=<?= $row['num']; ?>' class=price><img src='http://www.immo-lelion.be/photos/<?= $row['photo'];?>.jpg' class='thumbnail' width=250 border='0'/></a></td>
     
	  <td rowspan=2 align=left width=16>&nbsp;</td>
	  <td height="23" colspan=3 align='left' class='price' >
	    <?= $tPrix;?>
      </td>
	  <td rowspan=2></td>
    </tr>
	<tr>
	  <td height="23" colspan="3" align=left class='descro'>
	    <?= $row['descrfr'];?>
	  </td>
    </tr>
	<tr>
	  <td></td>
	  <td align=left>&nbsp;</td>
	  <td colspan="2" align=left class=price><a href="index.php?<?= $params; ?>&chap=5&newItem=<?= $row['num']; ?>" class=yellow>ajouter à ma
	      sélection</a>
        <?php if($row['reference']!='') {?>
        (ref.:
        <?= $row['reference'];?>
        )
      <?php }?></td>
	  <td align="right"><span class="price"><a href='index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>&detailId=<?= $row['num']; ?>' class=price><u>en
      savoir plus >></u></a></span></td>
	  <td></td>
    </tr>
	<tr><td colspan=7>&nbsp;</td></tr>
  </table>
</div>
<?php
}

if(!isset($_GET['fulldisplay'])) {
?>
<div id='item' align=center> <a href='index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>&fulldisplay=true' class='yellow'>tout afficher</a> | <a href='index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>&orderby=prix' class='yellow'>afficher par prix</a> | <a href='index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>&orderby=locfr' class='yellow'>afficher par commune</a><br />
<?php
$currentPage=($_GET['start']+10)/10;
if($_GET['start']>0) {?>
<a href="index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>&start=<?= $_GET['start']-10; ?>"><img border='0' src='medias/back.gif' hspace=2/ ></a>
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
<a href="index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>&start=<?= $_GET['start']+10; ?>"><img border='0' src='medias/next.gif' hspace=2 /></a>
<?
}
?>
</div>
<?php
}
else {
?>
<div id='item' align=center> <a href='index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>' class='yellow'>afficher par 10</a> | <a href='index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>&fulldisplay=true&orderby=prix' class='yellow'>afficher par prix</a> | <a href='index.php?<?= $params; ?>&chap=<?= $_GET['chap']; ?>&fulldisplay=true&orderby=locfr' class='yellow'>afficher par commune</a><br />
<?php
}

?>