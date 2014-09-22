<?php
 

if(isset($_GET['searchfor'])) {
 $_GET['searchfor']=$_GET['searchfor'];
 }
  if(isset($_GET['prix'])) {
 $_GET['prix']=$_GET['prix'];
 }
  if(isset($_GET['location'])) {
 $_GET['location']=$_GET['location'];
 }
  if(!isset($_GET['searchfor'])) {
 $_GET['searchfor']='sale';
 }
 if($_GET['chap']==2) {
 $_GET['searchfor']='rent';
 }
 else if(!isset($_GET['searchfor'])){
 	$_GET['searchfor']='sale';
 }
 if(isset($_GET['searchfor'])) {
 	$searchfor=$_GET['searchfor'];
 }
 ?>
<?php


if($isprofile=='true') {
 //if($_GET['searchfor']=='rent') { $tchap=2;}
 //else if($_GET['searchfor']=='sale') { $tchap=1;}
 
?>
<form action='index.php' method='GET' name='searchForm' id="searchForm">
<!--<input type='hidden' name='chap' value='<?= $_GET['chap'] ;?>' />-->
<input type='hidden' name='tab' value='<?= $_GET['tab'] ?>' />
<input type='hidden' name='saveandsearch' value='0'/>

<?php
}
else {
?>
<form action='index.php' name='searchForm' id="searchForm">
<?php
}
?>
  <input type=hidden name='isprofile' value='<?= $isprofile; ?>'>
  <input type=hidden name='chap' value='<?= $_GET['chap'] ; ?>'>

  <table border=0 cellpadding=0 cellspacing=0 width=298>
    <tr>
      <td height=37 align=left background="medias/search_box_top.gif" class='title'><img src="medias/bullet.gif" alt="estate belgium | immobilier belgique" width="53" height="28" align="absmiddle" longdesc="http://www.immo-lelion.be" /><?= $_lang[0]['search']; ?></td>
      <td rowspan=3 width=30>&nbsp;</td>
    </tr>
    <tr>
    <td background="medias/search_box_bkg.gif" align=left>
    
    <table border=0>
      <tr>
      
      <td class='search'>&nbsp;Type:</td>
      <td colspan=4>
      <select name="type" class='search'>
        <?php
				if(!isset($_SESSION['language'])) {
					$_SESSION['language']='fr';
				}
				$q="select * from type where id!=4 order by id";
				$r=mysql_query($q) or die(mysql_error());
				while($row=mysql_fetch_array($r)) {
				?>
        			<option value='<?= $row['id']; ?>' <?php if($row['id']==$_GET['type']) { echo 'selected'; } ?> >
						<?= $row['type_'.$_SESSION['language']]; ?>
        			</option>
				<?php
				}
				?>
        </td>
        
        </tr>
        
        <tr>
        
        <td class='search'>
        &nbsp;<?= $lang[0]['price']; ?>
        </td>
        
        <td colspan=4>
        
        <?php
		if($_GET['chap']==1) {
		$searchfor="sale";
		$tchap=1;
		}
		else if($_GET['chap']==2) {
		$searchfor="rent";
		$tchap=2;
		}
		$pricesArray=array();
		$pricesRangesArray=array();
		$rangeArray=array();
		$q="select * from prices where type='".$searchfor."' order by price ";
		$r=mysql_query($q) or die(mysql_error());
		while($row=mysql_fetch_array($r)) {
		array_push($pricesArray,$row['price']);
		
		}
		$i=0;
		array_push($rangeArray,$pricesArray[$i-1].$lang[0]['lessthan']." ".makePrice($pricesArray[$i]));
		array_push($pricesRangesArray,"0|".$pricesArray[$i]);
		for($i=1;$i<count($pricesArray);$i++) {
			array_push($rangeArray,$lang[0]['between']." ".makePrice($pricesArray[$i-1])." ".$lang[0]['and']." ".makePrice($pricesArray[$i]));
			array_push($pricesRangesArray,$pricesArray[$i-1]."|".$pricesArray[$i]);
		}
		$i--;
		array_push($rangeArray,$lang[0]['morethan']." ".makePrice($pricesArray[$i]));
		array_push($pricesRangesArray,$pricesArray[$i]."|99999999");
		$i++;
		?>
        <select name='prix' class='search'>
        
        <?php
		$q="select * from prices where type='".$_GET['searchfor']."' order by price ";
		$r=mysql_query($q) or die(mysql_error());
		for($j=0;$j<=$i;$j++) {
		?>
        <option value='<?= $pricesRangesArray[$j]; ?>' <?php
		if($_GET['prix']==$pricesRangesArray[$j]) {
		?>
		 selected 
		 <?php
		 }
		?>>
        <?= $rangeArray[$j]." €"; ?>
        </option>
        <?php
		}
		?>
      </select>
      </td>
      
      </tr>
    <!--  
      <tr>
        <td class='search'>&nbsp;Région:</td>
        <td colspan=4><select name='location' class='search'>
            <?php
		$q="select * from locations order by fr,nl";
		$r=mysql_query($q) or die(mysql_error());
		while($row=mysql_fetch_array($r)) {
		?>
            <option value='<?= $row['zip']; ?>' <?php
		if($_GET['location']==$row['zip']) {
		?>
		 selected 
		 <?php
		 }
		?>>
            <?= $row['fr']." (".$row['zip'].")"; ?>
            </option>
            <?php
		}
		?>
          </select></td>
      </tr>-->
	   <tr>
        <td class='search'>&nbsp;<?= $lang[0]['location']; ?></td>
        <td colspan=4><select name='location' class='search'>
		<option value=''><?= $lang[0]['doesntmatter']; ?></option>
            <option value='innercity' <?php
		if($_GET['location']=='innercity') {
		?>
		 selected 
		 <?php
		 }
		?>><?= $_lang[0]['brussels']; ?></option>
           <option value='outercity' <?php
		if($_GET['location']=='outercity') {
		?>
		 selected 
		 <?php
		 }
		?>><?= $_lang[0]['brabant']; ?></option>
           
          </select></td>
      </tr>
      <!-- type -->
      <?php
	 if($_GET['chap']==0 || $_GET['chap']==5) {
	 ?>
      <tr>
        <td></td>
        <td class='search'><?= $lang[0]['sale']; ?></td>
        <td><input type='radio' name='searchfor' value='sale' onclick="switchSearchFor('sale')" <?php
		if($searchfor=='sale') {
		?>
		 checked 
		 <?php
		 }
		?>
		/></td>
        <td class='search'><?= $lang[0]['rent']; ?></td>
        <td><input type='radio' name='searchfor' value='rent' onclick="switchSearchFor('rent')" <?php
		if($searchfor=='rent') {
		?>
		 checked 
		 <?php
		 }
		?>/></td>
      </tr>
      <?php
	  }
	  else {
	  ?>
	  <tr>
        <td><input type='hidden' name='searchfor' value='<?= $_GET['searchfor']; ?>' /></td>
        <td ></td>
        <td></td>
        <td ></td>
        <td></td>
      </tr>
	  <?php
	  }
	  ?>
      <!-- // type -->
      <tr>
        <td class='search' >&nbsp;<?= $lang[0]['order']; ?></td>
        <td  class='search' ><?= $lang[0]['byprice']; ?></td>
        <td><input type='radio' name='orderby' value='prix' checked/></td>
        <td class='search'><?= $lang[0]['bylocation']; ?></td>
        <td><input type='radio' name='orderby' value='locfr'/></td>
      </tr>
    </table>
    </td>
    </tr>
    
    <tr>
      <td align=center background="medias/search_box_bottom.gif" height=32>
	  <?php
	  if($_GET['chap']==5) {
	  ?>
	  <input type='button' value='<?php if($_GET['chap']==5) { echo $_lang[0]['registerand']; } ?><?= $_lang[0]['search']; ?>' class='search' onclick="listResult()"/>
	  <?php
	  }
	  else {
	  ?><input type='button' value='<?= $_lang[0]['search']; ?>' class='search' onclick="listResult()"/>
	  <?php
	  }
	  ?></td>
    </tr>
  </table>
  <?php
  if($_GET['chap']==0) {
  ?>
  <br />
  <table border=0 cellpadding=14 cellspacing=0 width=268 align=left>
    <tr>
      <td height=37 align=center background="medias/search_box_bkg_2.gif" >
 <?= $_lang[0]['searchbyref']; ?>:<br />
    <input type='text' name='reference' value="030/"/>
    <input type='button' value='ok' class='search' onclick="listResult()"/>
  &nbsp;&nbsp;</td></tr></table>
  <?php
  }
  ?>
</form>