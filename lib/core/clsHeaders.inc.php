<?php
class headers {
	var $pageId;
	var $templateId;
	var $templateFolder;


	function init() {
		$db=new DB;
		$db->query="SELECT #__elements.name FROM #__content2page LEFT JOIN #__contents ON #__contents.contentId=#__content2page.contentId LEFT JOIN #__elements ON #__elements.elementId=#__contents.elementId WHERE pageId='".$this->pageId."'  GROUP BY #__contents.elementId";
		$db->setQuery();
		$this->contents=$db->output;
	}


	function getStyles() {

		//$files = scandir(__root__."css/");
		$files = $this->php4_scandir(__root__."css/");

		foreach($files as $f) {
			if(!is_dir($f) && preg_match('/css/',$f) && !preg_match('/classic/',$f)) {
				$out.=$this->addCss(__web__."css/".$f);
			}
		}

		$files = $this->php4_scandir(__lib__."templates/".$this->templateFolder."/css/");

		foreach($files as $f) {
			if(!is_dir($f)) {
				$out.=$this->addCss(__web__."lib/templates/".$this->templateFolder."/css/".$f);
			}
		}


		foreach($this->contents as $c) {
			if(file_exists(__elem__.$c['name']."/css/")) {
				//$files = scandir(__elem__.$c['name']."/css/");
				$files = $this->php4_scandir(__elem__.$c['name']."/css/");
				foreach($files as $f) {
					if(!is_dir($f)) {
						$out.=$this->addCss(__web__."lib/elements/".$c['name']."/css/".$f);
					}
				}
			}
		}
		return $out;
	}

	function addCss($href,$media='screen,projection') {
		return "<link href=\"".$href."\" rel=\"stylesheet\" type=\"text/css\" media=\"".$media."\" />";
	}


	function getScripts() {
		//$files = scandir(__root__."js/");
		$files = $this->php4_scandir(__root__."js/");
		foreach($files as $f) {
			if(!is_dir($f) && preg_match('/js/',$f) && !preg_match('/classic/',$f)) {
				$out.=$this->addJs(__web__."js/".$f);
			}
		}
		foreach($this->contents as $c) {
			if(file_exists(__elem__.$c['name']."/js/")) {
				//$files = scandir(__elem__.$c['name']."/js/");
				$files = $this->php4_scandir(__elem__.$c['name']."/js/");
				foreach($files as $f) {
					if(!is_dir($f)) {
						$out.=$this->addJs(__web__."lib/elements/".$c['name']."/js/".$f);
					}
				}
			}
		}
		return $out;
	}


	function addJs($src) {
		return "<script type=\"text/javascript\" src=\"".$src."\"></script>";

	}





	function php4_scandir($dir,$listDirectories=false, $skipDots=true) {
		$dirArray = array();
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if (($file != "." && $file != ".." ) || $skipDots == true) {
					if($listDirectories == false) { if(is_dir($file)) { continue; } }
					if($file!=".DS_Store") {
						array_push($dirArray,basename($file));
					}

				}
			}
			closedir($handle);
		}
		sort($dirArray);
		return $dirArray;
	}


}
?>
