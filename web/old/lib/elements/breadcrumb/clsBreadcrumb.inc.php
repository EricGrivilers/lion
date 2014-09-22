<?php

class breadcrumb extends element {

	var $pages=array();
	var $label="Vous êtes ici : ";
	var $separator=" / ";
	function display() {
		$this->getBreadcrumb();
		return $this->content;
	}
    
	
	function getBreadcrumb() {
		$this->pageId=$_GET['pageId'];
		$this->getParent($this->pageId);
		$this->content=$this->label.implode($this->separator,array_reverse($this->pages));
	}
	
	function getParent($pageId) {
		$db=new DB;
		$db->query="SELECT title,parentId,dst FROM #__pages LEFT JOIN #__aliases ON #__aliases.src LIKE '%pageId=".$pageId."' WHERE pageId='".$pageId."'";
		$db->setQuery();
		$page=$db->output[0];
	 
		$this->pages[]="<a href=\"".$page['dst']."\">".$page['title']."</a>" ;
		
		
		if($page['parentId']>0) {
			$this->getParent($page['parentId']);
		}
	}
    
   }
    
?>