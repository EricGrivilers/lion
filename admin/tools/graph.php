<?php
  
           header("Content-type: image/jpeg");
         
        // read the post data
			include('../../incs/config.inc.php');
			$q="select * from item_statsv2 where itemId='".$_GET['itemId']."'";
			$r=mysql_query($q) or die(mysql_error());
			if(!$row=mysql_fetch_array($r)) {
			
			$q="insert into item_statsv2 (itemId) value ('".$_GET['itemId']."')";
		$r=mysql_query($q) or die(mysql_error());
		$q="select * from item_statsv2 where itemId='".$_GET['itemId']."'";
		$r=mysql_query($q) or die(mysql_error());
		$row=mysql_fetch_array($r);
		}
		
		switch($_GET['period']) {
			case 'months':
				$chartwidth=240;
				$data=explode(",",$row['months']);
				$labels=array('','J','F','M','A','M','J','J','A','S','O','N','D');
				
			break;
			
			case 'days':
				$currentDay=date('z');
				
				$chartwidth=600;
				$datas=explode(",",$row['days']);
				$data=array();
				for($i=-7;$i<7;$i++) {
					$data[]=$datas[$i+$currentDay];
					//$tdate  = mktime(0, 0, 0, date("m")  , date("d")+$i, date("Y"));
					$labels[]=date('d/m',mktime(0, 0, 0, date("m")  , date("d")+$i, date("Y")));
				}
				
				
			break;
			
			case 'wdays':
				$chartwidth=240;
				$data=explode(",",$row['wdays']);
				$labels=array('','L','M','M','J','V','S','D');
				
			break;
			
			case 'weeks':
				$chartwidth=600;
				$data=explode(",",$row['weeks']);
				$labels=array();
				for($i=1;$i<=count($data);$i++) {
				
				$a++;
				if($a>=5) {
				$a=0;$labels[$i]=$i;
				}
				}
				//$labels=array('J','F','M','A','M','J','J','A','S','O','N','D');
				
			break;
		
		}
		$t=$data;
		sort($t);
		$max=$t[count($t)-1];
		if($max<1) {
		$max=1;
		}
		$tabheight=array();
		for($i=0;$i<count($data);$i++) {
			$tabheight[$i]=(200*$data[$i])/$max;
			$colors[$i][0]=rand(0,255);
			$colors[$i][1]=rand(0,255);
			$colors[$i][2]=rand(0,255);
		}
        //$data = array('3400','2570','245','473','1000','3456','780');
        $sum = array_sum($data);
		if($sum<1) {
		$sum=1;
		}
        
        $height = 255;
        $width = $chartwidth+50;
        
        $im = imagecreate($width,$height); // width , height px

        $white = imagecolorallocate($im,255,255,255); 
        $black = imagecolorallocate($im,0,0,0);   
        $red = imagecolorallocate($im,255,0,0);   


        imageline($im, 10, 5, 10, 230, $black);
        imageline($im, 10, 230, 300, 230, $black);
    

        $x = 15;   
        $y = 230;   
        $x_width = $chartwidth/count($data);  
        $y_ht = 0; 
       
        for ($i=1;$i<=count($data);$i++){
        	$red = imagecolorallocate($im,$colors[$i][0],$colors[$i][1],$colors[$i][2]); 
          $y_ht = ($data[$i]/$sum)* $height;    
          
              //imagerectangle($im,$x,$y,$x+$x_width,($y-$y_ht),$red);
			 imagefilledrectangle($im,$x,$y,$x+$x_width,($y-$tabheight[$i]),$red);
			  
              imagestring( $im,2,$x-1,$y+10,$data[$i],$black);
			  imagestring( $im,2,$x-1,$y,$labels[$i],$black);
			  
              
          $x += $x_width;  
         
        }
        
        imagejpeg($im);

?> 