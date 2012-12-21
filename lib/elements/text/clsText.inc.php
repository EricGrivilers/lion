<?php
class text {

	
	
	var $attributes;
	var $content;
	var $defaultContent="<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum sed tellus sed risus ullamcorper ultrices malesuada eu lacus. Maecenas egestas, lacus non tempor sagittis, mauris est eleifend libero, non fringilla eros neque et nulla. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Suspendisse dictum ligula sit amet lorem feugiat posuere. Phasellus in felis nulla, a posuere enim. Praesent id dolor eget ante mattis condimentum. Etiam in justo ligula, vitae scelerisque enim. Sed varius commodo lacus, vitae pretium urna condimentum placerat. Quisque dignissim lacinia hendrerit. Vivamus nec erat ut lacus luctus tincidunt mattis in velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; </p>";
	
	
	function display() {
		if($this->content=='NULL') {
			$this->content=$this->defaultContent;
		}
		return $this->content;
	}
	
	/*
	function replace($str)  {
	
		$str=preg_replace("/é/","&eacute;",$str);
		$str=preg_replace("/É/","&Eacute;",$str);
		$str=preg_replace("/è/","&egrave;",$str);
		$str=preg_replace("/ê/","&ecirc;",$str);
		$str=preg_replace("/ë/","&euml;",$str);
		
		$str=preg_replace("/à/","&agrave;",$str);
		$str=preg_replace("/À/","&Agrave;",$str);
		
		$str=preg_replace("/ù/","&ugrave;",$str);
		$str=preg_replace("/ù/","&ucirc;",$str);
		$str=preg_replace("/û/","&ucirc;",$str);
		
		$str=preg_replace("/ï/","&iuml;",$str);
		$str=preg_replace("/î/","&icirc;",$str);
		
		$str=preg_replace("/ô/","&ocirc;",$str);
		$str=preg_replace("/ç/","&ccedil;",$str);
		$str=preg_replace("/œ/","oe",$str);
		
		$str=preg_replace("/«/",'"',$str);
		$str=preg_replace("/»/",'"',$str);
		
		$str=preg_replace("/’/","'",$str);
		$str=preg_replace("/…/","...",$str);
		
		$str=preg_replace("/©/","&copy;",$str);
		
		return $str;
	}
	*/
}

?>