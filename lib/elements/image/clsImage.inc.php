<?php
class image extends element {

	
	var $content;
	
	
	function display() {
		$this->getImage();
		return $this->content;
	}
	
	function getImage() {
		if(empty($this->content)) {
			$this->content="/lib/core/images/dummy_image.jpg";
		}
		$this->fileExplode=utils::parseFilename($this->content);
		$filename=$file[0];
		if($this->paramsArray['resample']>0 && !file_exists($this->fileExplode[2]."/".$this->fileExplode[0]."_".$this->paramsArray['width']."x".$this->paramsArray['height'].".".$this->fileExplode[1]) && ($this->paramsArray['width']>0 ||  $this->paramsArray['height']>0)) {
			$this->resample();
			
		}
		$out="<img src=\"".$this->content."\" ";
		if($this->params) {
			foreach($this->params as $var=>$value) {
				if($value!='') {
					$out.=" ".$var."=\"".$value."\" ";
				}
			}
		}
		$out.="/>";
		
		
		if($this->paramsArray['href']) {
			$lI=utils::parseFilename($this->paramsArray['href']);
			$t="<a href=\"".$this->paramsArray['href']."\" ";
			if(strtolower($lI[1]=='jpg') || strtolower($lI[1])=='gif') {
				$t.=" class='lightbox' ";	
			}
			$t.=">".$out."</a>";
			$out=$t;
		}
		if($this->paramsArray['title']) {
			$out.="<br/>".$this->paramsArray['title'];
		}
		$this->content =$out;
	}
	
	
	
	function resample() {
		//$this->fileExplode
		require_once(__lib__.'core/clsImageManip.inc.php');
	   $image = new imageManip();
	   $image->load(str_replace('//','/',__root__.$this->content));
	   if($this->paramsArray['width']>0 && ($this->paramsArray['height']=='' || $this->paramsArray['height']==0)) {
			  $image->resizeToWidth($this->paramsArray['width']);   
	   }
	   else if($this->paramsArray['height']>0 && ($this->paramsArray['width']=='' || $this->paramsArray['width']==0)) {
			  $image->resizeToHeight($this->paramsArray['height']);   
	   }
	 	else {
			 $image->resize($this->paramsArray['width'],$this->paramsArray['height']);
		}
		
		$newFileName=$this->fileExplode[2]."/".$this->fileExplode[0]."_".round($image->newSize[0])."x".round($image->newSize[1]).".".$this->fileExplode[1];
	   $image->save(str_replace('//','/',__root__.$newFileName));
	   $this->content=$newFileName;
	   $db=new DB;
	   $db->query="UPDATE #__contents SET content=\"".$newFileName."\" WHERE contentId='".$this->contentId."'";
	   $db->resultType='none';
	   $db->setQuery();
	}
}

?>