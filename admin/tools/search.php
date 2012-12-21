<div align=center> Réf.:
  <input type='text' name='reference' value="030/"/>
  <input type='submit' value='ok' class='search' />
  <input type='hidden' name='level' value='1' />
  &nbsp;&nbsp; </div>
<div align=center>
  <table border=0 cellpadding=0 cellspacing=0 width=298 >
    <tr>
      <td height=37 align=center background="medias/search_box_top.gif" class='title'>ou rechercher</td>
      <td rowspan=3 width=30>&nbsp;</td>
    </tr>
    <tr>
    
    <td align=center>
    
    <table border=0>
      <tr>
      
      <td class='search'>&nbsp;Type:</td>
      <td colspan=4>
      
      <select name="type" class='search'>
        <?php
				if(!isset($_SESSION['language'])) {
					$_SESSION['language']='fr';
				}
				$q="select * from type order by id";
				$r=mysql_query($q) or die(mysql_error());
				while($row=mysql_fetch_array($r)) {
				?>
        <option value='<?= $row['id']; ?>' <?php if($row['id']==$_POST['type']) { echo 'selected'; } ?> >
        <?= $row['type_'.$_SESSION['language']]; ?>
        </option>
        <?php
				}
				?>
        </td>
        
        </tr>
        
        <tr>
        
        <td class='search'>
        
        &nbsp;
        
        
        
        Prix:
        
        
        
        </td>
        
        <td colspan=4>
        
        <?php
		$pricesArray=array();
		$pricesRangesArray=array();
		$rangeArray=array();
		$q="select * from prices where type='".$_POST['searchfor']."' order by price ";
		$r=mysql_query($q) or die(mysql_error());
		while($row=mysql_fetch_array($r)) {
		array_push($pricesArray,$row['price']);
		
		}
		$i=0;
		array_push($rangeArray,$pricesArray[$i-1]."moins de ".makePrice($pricesArray[$i]));
		array_push($pricesRangesArray,"0|".$pricesArray[$i]);
		for($i=1;$i<count($pricesArray);$i++) {
			array_push($rangeArray,"entre ".makePrice($pricesArray[$i-1])." et ".makePrice($pricesArray[$i]));
			array_push($pricesRangesArray,$pricesArray[$i-1]."|".$pricesArray[$i]);
		}
		$i--;
		array_push($rangeArray,"plus de ".makePrice($pricesArray[$i]));
		array_push($pricesRangesArray,$pricesArray[$i]."|99999999");
		$i++;
		?>
        <select name='price' class='search'>
        
        <?php
		$q="select * from prices where type='".$_POST['searchfor']."' order by price ";
		$r=mysql_query($q) or die(mysql_error());
		for($j=0;$j<=$i;$j++) {
		?>
        <option value='<?= $pricesRangesArray[$j]; ?>' <?php
		if($_POST['price']==$pricesRangesArray[$j]) {
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
      
      <tr>
        <td class='search'>&nbsp;Région:</td>
        <td colspan=4><select name='location' class='search'>
            <option value=''>Sans importance</option>
            <option value='innercity' <?php
		if($_POST['location']=='innercity') {
		?>
		 selected 
		 <?php
		 }
		?>>Bruxelles</option>
            <option value='outercity' <?php
		if($_POST['location']=='outercity') {
		?>
		 selected 
		 <?php
		 }
		?>>Brabant wallon</option>
          </select></td>
      </tr>
      <!-- type -->
      <?php
	 if($_POST['chap']==0 || $_POST['chap']==5) {
	 ?>
      <tr>
        <td></td>
        <td class='search'>vente</td>
        <td><input type='radio' name='searchfor' value='sale' onclick="document.theForm.level.value=0;document.theForm.submit()" <?php
		if($_POST['searchfor']=='sale') {
		?>
		 checked 
		 <?php
		 }
		?>
		/></td>
        <td class='search'>location</td>
        <td><input type='radio' name='searchfor' value='rent' onclick="document.theForm.level.value=0;document.theForm.submit()" <?php
		if($_POST['searchfor']=='rent') {
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
        <td><input type='hidden' name='searchfor' value='<?= $_POST['searchfor']; ?>' /></td>
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
        <td class='search' >&nbsp;Trier</td>
        <td  class='search' >par prix</td>
        <td><input type='radio' name='orderby' value='price' checked/></td>
        <td class='search'>par localité</td>
        <td><input type='radio' name='orderby' value='location'/></td>
      </tr>
    </table>
    </td>
    
    </tr>
    <tr>
      <td align=center ><input type='checkbox' value='Y' name='actif' class='search' checked/> biens actifs seulement</td>
    </tr>
    <tr>
      <td align=center background="medias/search_box_bottom.gif" height=32><input type='submit' value='rechercher' class='search'/></td>
    </tr>
  </table>
</div>