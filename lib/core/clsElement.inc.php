<?php

class element extends page {

	var $elementId;
	var $elementName;
	var $elementTitle;
	var $counter;
	var $area;
	var $classes;
	var $contentId;
	var $elementContent;
	var $params;
	var $styles;
	var $db;
	var $isVisible;

	
	var $margins=array('margin-top','margin-right','margin-bottom','margin-left');
	
					
					
	function element() {
		$_SESSION['elements'][]=get_class($this);
	}
	
	function getContent() {
			$out="<div id='element_".$this->area."_".$this->counter."_".$this->contentId."_".$this->elementId."' ";
			$cl=array();
			if(!empty($this->classes)) {
				
				$cl[]=$this->classes;
			}
			$cl[]="element";
			//$cl[]=$this->elementName;
			if($_SESSION['admin']) {
				
				$cl[]="admin_element";
				$cl[]="admin_".$this->elementName;
				$cl[]=$this->elemType;
			}
			if(count($cl)>0) {
				$out.="class=\"".implode(" ", $cl)."\" ";
			}
			if($_SESSION['admin']) {
			$out.=" title=\"".$this->elementLabel."\" ";
			$out.=" rel=\"".$this->elementName."\" ";
			}
			if($this->styles) {
				$out.=" style=\"";
				foreach($this->styles as $k=>$v) {
						if($v!=='') {
							if(in_array($k,$this->margins)) {
								$v.='px';
							}
							$out.=$k.":".$v.";";
						}
					}
					$out.="\"";
			}
			
			$out.=">";
			if($this->isVisible==1) {
				require_once(__lib__."elements/".$this->elementName."/cls".ucfirst($this->elementName).".inc.php");
				$myElement=new $this->elementName;
				$myElement->params=$this->params;
				if($this->params) {
					$myElement->paramsArray=array();
					foreach($this->params as $k=>$v) {
						if($v!=='') {
							$myElement->$k=stripslashes($v);
							$myElement->paramsArray[$k]=stripslashes($v);
						}
					}
				}
				if($this->styles) {
					$myElement->stylesArray=array();
					foreach($this->styles as $k=>$v) {
						if($v!=='') {
							$myElement->stylesArray[$k]=stripslashes($v);
						}
					}
				}
				
				//$myElement->content=utils::closetags($this->elementContent);
				$myElement->content=$this->elementContent;
				$myElement->contentId=$this->contentId;
				if($this->elementTitle!='') {
					$out.=$this->elementTitle;
				}
			
				
				
				if($myElement->func) {
					$out.="<econtent>".call_user_func(array($myElement,$myElement->func))."</econtent>";
					//$out.=$myElement->display();
				}
				else {
					$out.="<econtent>".$myElement->display()."</econtent>";
				}
			}
			$out.="</div>";
		
		//$out.="<br class='clearfloat' />";
		
		return $out;
	}
	
	
	function getStyleName($styleName) {
		$tArray=array();
		for($i=0;$i<strlen($styleName);$i++) {
			if($styleName[$i]==strtoupper($styleName[$i])) {
				$tArray[]="-".strtolower($styleName[$i]);
			}
			else {
				$tArray[]=$styleName[$i];
			}
		
		}
		return implode("",$tArray);
	}
	
	
	
	
}

?>