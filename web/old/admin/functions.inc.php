<?php
if(!function_exists('printr')){
	function printr($array){
		echo('<pre style="text-align: left;">');
		print_r($array);
		echo('</pre><br />');
	}
}



if(!function_exists('trimAll')){
	function trimAll($input){
		$input = trim($input);
		$output = ereg_replace( ' +', '', $input );
		return $output;
	}
}



if(!function_exists('cropimage')){
	function cropimage($nw, $nh, $source, $stype, $dest) {
		$size = getimagesize($source);
		$w = $size[0];
		$h = $size[1];

		switch($stype) {
			case 'gif':
				$simg = imagecreatefromgif($source);
				break;
			case 'jpg':
				$simg = imagecreatefromjpeg($source);
				break;
			case 'png':
				$simg = imagecreatefrompng($source);
				break;
		}

		$dimg = imagecreatetruecolor($nw, $nh);
		$wm = $w/$nw;
		$hm = $h/$nh;
		$h_height = $nh/2;
		$w_height = $nw/2;

		if($w> $h) {
			$adjusted_width = $w / $hm;
			$half_width = $adjusted_width / 2;
			$int_width = $half_width - $w_height;
			imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
		} elseif(($w <$h) || ($w == $h)) {
			$adjusted_height = $h / $wm;
			$half_height = $adjusted_height / 2;
			$int_height = $half_height - $h_height;
			imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);
		} else {
			imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
		}
		imagejpeg($dimg,$dest,100);
	}
}



if(!function_exists('getVarsOLD')){
	function getVarsOLD($cleanup=true){
		global $_GET,$_POST;

		$aVars = array_merge($_GET,$_POST);

		if($cleanup){
			if(!get_magic_quotes_gpc()){
				foreach($aVars as $k=>$v){
					$tp[strip_tags($k)]=htmlentities(strip_tags($v),ENT_QUOTES);
				}
			}else{
				foreach($aVars as $k=>$v){
					$tp[strip_tags($k)]=htmlentities(strip_tags(stripslashes($v)),ENT_QUOTES);
				}
			}
			$aVars=$tp;
		}
		return $aVars;
	}
}


if(!function_exists('getVarsOLD2')){

	function getVarsOLD2($aVars, $cleanup=true){

		if($cleanup){
			if(!get_magic_quotes_gpc()){
				foreach($aVars as $k=>$v){
					if(is_array($v)){
						$tp[strip_tags($k)]=getVars($v,$cleanup);
					}else{
						$tp[strip_tags($k)]=htmlentities(strip_tags($v),ENT_QUOTES);
					}
				}
			}else{
				foreach($aVars as $k=>$v){
					if(is_array($v)){
						$tp[strip_tags($k)]=getVars($v,$cleanup);
					}else{
						$tp[strip_tags($k)]=htmlentities(strip_tags(stripslashes($v)),ENT_QUOTES);
					}
				}
			}
			$aVars=$tp;
		}
		return $aVars;
	}

}



if(!function_exists('getVars')){

	function getVars($aVars, $cleanup=true){

		if($cleanup){
			if(get_magic_quotes_gpc()){
				if(ini_get('magic_quotes_sybase')){
					foreach($aVars as $k=>$v){
						if(is_array($v)){
							$tp[strip_tags($k)]=getVars($v,$cleanup);
						}else{
							$tp[strip_tags($k)]=htmlentities(strip_tags(str_replace("''", "'", $v)),ENT_QUOTES);
						}
					}
				}else{
					foreach($aVars as $k=>$v){
						if(is_array($v)){
							$tp[strip_tags($k)]=getVars($v,$cleanup);
						}else{
							$tp[strip_tags($k)]=htmlentities(strip_tags(stripslashes($v)),ENT_QUOTES);
						}
					}
				}
			}else{
				foreach($aVars as $k=>$v){
					if(is_array($v)){
						$tp[strip_tags($k)]=getVars($v,$cleanup);
					}else{
						$tp[strip_tags($k)]=htmlentities(strip_tags($v),ENT_QUOTES);
					}
				}
			}
			$aVars=$tp;
		}
		return $aVars;
	}

}


if(!function_exists('SQLVars')){

	function SQLVars($SQLVars){


		if(get_magic_quotes_gpc()){
			if(ini_get('magic_quotes_sybase')){
				foreach($aVars as $k=>$v){
					if(is_array($v)){
						$tp[strip_tags(str_replace("''", "'", $k))]=getVars($v,$cleanup);
					}else{
						$tp[strip_tags(str_replace("''", "'", $k))]=mysql_real_escape_string(htmlentities(strip_tags(str_replace("''", "'", $v)),ENT_QUOTES));
					}
				}
			}else{
				foreach($aVars as $k=>$v){
					if(is_array($v)){
						$tp[strip_tags(stripslashes($k))]=getVars($v,$cleanup);
					}else{
						$tp[strip_tags(stripslashes($k))]=mysql_real_escape_string(htmlentities(strip_tags(stripslashes($v)),ENT_QUOTES));
					}
				}
			}
		}else{
			foreach($aVars as $k=>$v){
				if(is_array($v)){
					$tp[strip_tags($k)]=getVars($v,$cleanup);
				}else{
					$tp[strip_tags($k)]=mysql_real_escape_string(htmlentities(strip_tags($v),ENT_QUOTES));
				}
			}
		}
		$aVars=$tp;

		return $aVars;
	}

}

if(!function_exists('makePrice')){
	function makePrice($price) {
	$l=strlen($price);
	$a=array();
	for($i=0;$i<$l;$i++) {
		$a[$i]=substr($price,$i,1);
	}
	$t=0;
	$price='';
	for($i=$l;$i>=0;$i--) {
		$price=$a[$i].$price;
		$t++;
		if($t==4) {
		
		$price=".".$price;
		}
		if($t==7 && $l>6) {
		
		$price=".".$price;
		}
	}
	return $price;
	}
}


//MENUS
if(!function_exists('tree')){
	function tree($id)  {
		global $DBManager;
		global $validids;

		$r = $DBManager->Menus->Find("","child_of=".$id,"order_by");
		while ($rowItem = $r->FetchRow()) {
			$array[$rowItem['id']]['content_type']=$rowItem['content_type'];
			$array[$rowItem['id']]['title']=$rowItem['title_'.CURRENT_LANG];
			$array[$rowItem['id']]['title_fr']=$rowItem['title_fr'];
			$array[$rowItem['id']]['title_en']=$rowItem['title_en'];
			$array[$rowItem['id']]['title_nl']=$rowItem['title_nl'];
			$array[$rowItem['id']]['order_by']=$rowItem['order_by'];
			$array[$rowItem['id']]['child_of']=$rowItem['child_of'];
			$array[$rowItem['id']]['id']=$rowItem['id'];
			$array[$rowItem['id']]['page_id']=$rowItem['page_id'];
			$array[$rowItem['id']]['forceExpand']=1;
			$array[$rowItem['id']]['url']=ROOT_URL.'index.php?menuid='.$rowItem['id'];
			$validids[]=$rowItem['id'];
			$array[$rowItem['id']]['sub']=tree($rowItem['id']);
		}
		return (isset($array) ? $array : false); 
	}
}


if(!function_exists('check_date')){
	function check_date($dd,$mm,$yyyy){
		if(checkdate($mm,$dd,$yyyy)){
			return true;
		}else{
			return false;
		}
	}
}



if(!function_exists('calcul_joursferies')){
	function calcul_joursferies($month,$day,$year){
		$resultat=false;

		$jf1=$year-1900;
		$jf2=$jf1%19;
		$jf3=intval((7*$jf2+1)/19);
		$jf4=(11*$jf2+4-$jf3)%29;
		$jf5=intval($jf1/4);
		$jf6=($jf1+$jf5+31-$jf4)%7;
		$jfj=25-$jf4-$jf6;
		$jfm=4;
		if ($jfj<=0){
			$jfm=3;
			$jfj=$jfj+31;
			}


		$paques=$jfm."/".$jfj;
		$lunpaq=date("n/j",mktime(12,0,0,$jfm,$jfj+1,$year));
		$ascension=date("n/j",mktime(12,0,0,$jfm,$jfj+39,$year));
		$pent=date("n/j",mktime(12,0,0,$jfm,$jfj+49,$year));
		$lunpent=date("n/j",mktime(12,0,0,$jfm,$jfj+50,$year));

		$JourFerie= Array("1/1","5/1","7/21","8/15","11/1","11/11","12/25","$paques","$lunpaq","$ascension","$pent","$lunpent");
		$nbj=0;
		$val=	$lien=date("n/j", mktime(0,0,0,$month,$day,$year));
		  while ($nbj<count($JourFerie)){

			if ($JourFerie[$nbj] && $JourFerie[$nbj]==$val){
			$resultat=true;
			$nbj=15;
			}
			$nbj++;
		  }
		return( $resultat );
	}
}

// String => yyyy-mm-dd hh:mm:ss OU yyyy-mm-dd
if(!function_exists('parseDate')){
	function parseDate($date,$mode="") {
		if(DATE_FORMAT!="en") {
			$exp_dt=explode(" ",$date);
			$exp_d=explode("-",$exp_dt[0]);

			if(!empty($mode) && $mode=="dateonly"){
				return $exp_d[2]."-".$exp_d[1]."-".$exp_d[0];
			}else{
				return $exp_d[2]."-".$exp_d[1]."-".$exp_d[0]." ".$exp_dt[1];
			}
			
		}else{
			if(!empty($mode) && $mode=="dateonly"){
				$exp_dt=explode(" ",$date);
				return $exp_dt[0];
			}else{
				return $date;
			}
			
		}
	}
}

if(!function_exists('utf8dec')){
	function utf8dec($string){
		$string = html_entity_decode(htmlentities($string." ", ENT_COMPAT, 'UTF-8'));
		return substr($string, 0, strlen($string)-1);
	}
}

if(!function_exists('strtoupper_utf8')){
	function strtoupper_utf8($string){
		$string=utf8dec($string);
		$string=strtoupper($string);
		$string=htmlentities($string);
		return $string;
	}
}

if(!function_exists('getNextValidDate')){
	function getNextValidDate($day,$month,$year) {
		return date("d-m-Y",mktime(0,0,0,$month,$day,$year));
	}
}

if(!function_exists('makeselect')){
	function makeselect($valdisplay,$selectname,$arrayval,$rowselected,$selectclass,$extraparams,$defaultdisplay='',$excludeValues="") {
		$out="<select name='$selectname' id='$selectname' class='$selectclass' $extraparams >";

		if(is_array($excludeValues)){
			if(!empty($defaultdisplay) && ($rowselected=="" || in_array($rowselected,$excludeValues))){$out.="<option SELECTED>".$defaultdisplay."</option>";}
			elseif($rowselected==''){$out.="<option SELECTED></option>";}
			else {$out.="<option></option>";}
		}else{
			if(!empty($defaultdisplay) && ($rowselected=="")){$out.="<option SELECTED>".$defaultdisplay."</option>";}
			elseif($rowselected==''){$out.="<option SELECTED></option>";}
			else {$out.="<option></option>";}
		}

		

		if(is_array($arrayval)){

			foreach($arrayval as $k=>$v){
				if($valdisplay=='vv'){
					if($v==$rowselected){$out.="<option SELECTED value='$v'>$v</option>";} else {$out.="<option value='$v'>$v</option>";}
				} elseif($valdisplay=='kv'){
					if($k==$rowselected){$out.="<option SELECTED value='$k'>$v</option>";} else {$out.="<option value='$k'>$v</option>";}
				} elseif($valdisplay=='kk'){
					if($k==$rowselected){$out.="<option SELECTED value='$k'>$k</option>";} else {$out.="<option value='$k'>$k</option>";}
				}
			}
		}
		$out.="</select>";
		return $out;
	}
}

if(!function_exists('logThis')){
	function logThis($file,$string,$writemode,$die){

		if(!$fpOut = @fopen($file,$writemode)){
			return false;
		}
		if(!@fputs($fpOut,$string)){
			return false;
		}
		if(!@fclose($fpOut)){
			return false;
		}
		if($die==1){
			die();
		}
	}
}

if(!function_exists('duration')){
	function duration($dt1,$dt2="",$mode="",$output=""){

		if($mode=='diff' || empty($mode)){
			$t1=strtotime($dt1);
			$t2=strtotime($dt2);
			//echo($t1.' '.$t2.'<br />');
			if($t2<$t1){
				return false;
			}
			$diff=$t2-$t1;
		}elseif($mode=='unique'){
			$diff=$dt1;
		}

		if($diff>=0){

			if($output=="h-i"){
				//HOURS
			(floor($diff/3600)>0) ? $duration['h']=floor($diff/3600) : $duration['h']=0;
			(floor($diff/3600)>0) ? $diff-=$duration['h']*3600 : '';

			//MINUTES
			(floor($diff/60)>0) ? $duration['i']=floor($diff/60) : $duration['i']=0;
			(floor($diff/60)>0) ? $diff-=$duration['i']*60 : '';
			
			}else{

				//DAYS
				(floor($diff/86400)>0) ? $duration['d']=floor($diff/86400) : $duration['d']=0;
				(floor($diff/86400)>0) ? $diff-=$duration['d']*86400 : '';

				//HOURS
				(floor($diff/3600)>0) ? $duration['h']=floor($diff/3600) : $duration['h']=0;
				(floor($diff/3600)>0) ? $diff-=$duration['h']*3600 : '';

				//MINUTES
				(floor($diff/60)>0) ? $duration['i']=floor($diff/60) : $duration['i']=0;
				(floor($diff/60)>0) ? $diff-=$duration['i']*60 : '';

				//SECONDS
				($diff>0) ? $duration['s']=$diff : $duration['s']=0;

			}
			return $duration;
		}
		return false;
	}
}

if(!function_exists('date_compare')){
	function date_compare ( $a_year, $a_month, $a_day, $b_year, $b_month, $b_day )
	{
	$final_result = 9; //set it to random  right now
	if($a_year >$b_year)
	  {
	   $final_result = 1; //the year 1 is greater then year 2 so no more check required
	  }//year greater
	else if($a_year == $b_year) //the years are the same ..so check the month
	  {
	   if($a_month > $b_month)
	   {
		 $final_result = 1; //the months are greater and so no more checks needed
	   }//greater month check
	   else
	   if($a_month == $b_month)//same months ..so check dates
	   {
			 if($a_day > $b_day)
			 {
			 $final_result = 1; //the days solve the problem :)
			 }
			 else if($a_day == $b_day)
			 {
			 $final_result = 0; //the dates are identical too..so date1 becomes equal to date 2
			 }
			 else
			 {
			   $final_result = 2; //the date1 < $date 2
			 }
	   }//equal month check
	   else
	   {
		 $final_result = 2;
	   }//month1  < month2
	  }//year same
	else
	   {
	   $final_result = 2; //the year 1 is smaller then year 2 ..hence date1 < date2
	   }
	return $final_result;
	}
}

if(!function_exists('globit')){
	function globit($type, $pattern, $path){
		$pathdelim=eregi( "windows" , getenv("OS") ) ? "\\" : "/";
		if($type=='fichier'){
			//echo($path.$pathdelim.$pattern);
			foreach (glob($path.$pathdelim.$pattern) as $item) {
				//echo($item);
				$out[]=$item;
			}
			//print_r($out);
			return $out;
		} elseif($type=='dossier'){
			foreach (glob($path.$pattern, GLOB_ONLYDIR) as $item) {
				$out[]=$item;
			}
			return $out;
		}
	}
}
?>