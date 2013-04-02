<?php

class menu extends element {


	
	var $level=0;
	var $levelDepth=100;
	var $mainParentId=1;
	
	var $attributes;
	var $content;
	var $menuArray=array();
	var $homepageId;
	
	var $parents=array();
	var $excludedPages=array();
	var $onlySelected=0;
	function display() {
		/*
		if($this->params) {
			foreach($this->params as $k=>$v) {
				$this->$k=$v;
			}
		}
		*/
		
		
		$this->pageId=$_GET['pageId'];
		
		
			$this->getPages();
		
		//print_r($this);
		return $this->content;
	}
	
	
	
	function getPages() {
		$this->db=new DB;
		
		$q="SELECT * FROM #__pages LEFT JOIN #__aliases ON #__aliases.src=CONCAT('pageId=',#__pages.pageId) WHERE language='".$_SESSION['language']."' OR language='' ORDER BY parentId,rank ";
		
		$this->db->q=$q;
		$rows=$this->db->sqlquery();
		foreach($rows as $k=>$row) {
			$this->menuArray[$row['parentId']][$row['rank']]['pageId']=$row['pageId'];
			
			$this->menuArray[$row['parentId']][$row['rank']]['pageTitle']=l::t($row['title']);
			$this->menuArray[$row['parentId']][$row['rank']]['inMenu']=$row['inMenu'];
			$this->menuArray[$row['parentId']][$row['rank']]['isFolder']=$row['isFolder'];
			//$this->menuArray[$row['parentId']][$row['rank']]['alias']=$row['alias'];
			$this->menuArray[$row['parentId']][$row['rank']]['alias']=$row['dst'];
			$this->menuArray[$row['parentId']][$row['rank']]['menuPicts']=$row['menuPicts'];
			if($row['isHomepage']==1) {
				$this->homepageId=$row['pageId'];
			}
		}
		
		$this->getParent($this->pageId);
		
		
		if(empty($_GET['rootParent'])) {
			$_GET['rootParent']=$this->parents[count($this->parents)-1];
		}
		if($this->mainParentId=='*') {
			$this->mainParentId=$_GET['rootParent'];
		}
		
		$this->content=$this->getMenu($this->mainParentId,$this->level);
	}
	
	
	function getMenu($parentId,$level) {
	
		$out="<ul class='menu_level_".$level."' id='menu_".$this->contentId."'>";
		$i=0;
		if($l=count($this->menuArray[$parentId])) {
			foreach($this->menuArray[$parentId] as $k=>$v) {
				if($v['inMenu']==1 || $this->admin==1) {
					$class=array();
					if($v['pageId']==$this->pageId || in_array($v['pageId'],$this->parents)) {
						$class[]="current";
					}
					if($i==0) {
						$class[]="first";
					}
					else if($i==$l-1) {
						$class[]="last";
					}
					$out.="<li ";
					if($this->admin==true) {
						$out.=" id='pl_".$v['pageId']."' ";
						if($v['isFolder']==1) {
							$class[]='folder';
							
						}
					}
					$out.=" class='";
					if($class) {
						$out.=implode(" ",$class);
					}
					$out.=" span3'";
					$out.="><a href=\"/".$v['alias']."\" ";
					if($class) {
						$out.=" class='".implode(" ",$class)."' ";
					}
					$out.=">";
					if(!empty($v['menuPicts'])) {
						$out.=$this->setPictsMenu($v['pageId'],$v['pageTitle'],$v['menuPicts']);
					}
					else {
						$out.=l::t($v['pageTitle']);
						if($this->admin) {
							$out.=" (".$v['pageId'].")";	
						}
					}
					
					$out.="</a>";
					if(count($this->menuArray[$v['pageId']])>0 && $this->levelDepth>$level+1 ) {
						if($this->onlySelected==0 || in_array($v['pageId'],$this->parents)) {
							$out.=$this->getMenu($v['pageId'],$level+1);
						}
					}
					else if($this->admin) {
						$out.="<ul style='height:10px;background:#000000'><li>-</li></ul>";
					}
					$out.="</li>";
					if($this->separator && $i<$l-1) {
						$out.="<li class='menu_separator'></li>";
					}
					
				}
				$i++;
			}
		}
		$out.="</ul>";
		return $out;
	}
	
	
	function setPictsMenu($pageId,$pageTitle,$menuPicts) {
		$picts=explode(",",$menuPicts);
		foreach($picts as $k=>$v) {
			$picts[$k]=__web__."lib/templates/images/menu/".$_SESSION['language']."/".$v;
		}
		$out="<img src=\"";
		if($pageId==$_GET['pageId']) {
			$out.=$picts[1];
		}
		else {
			$out.=$picts[0];
		}
		$out.="\" alt=\"".$pageTitle."\" title=\"".$pageTitle."\" ";
		if(!$picts[2]) {
			$picts[2]=$picts[1];
		}
		if($pageId==$this->pageId) {
			$out.=" onmouseover=\"this.src='".$picts[1]."'\" ";
			$out.=" onmouseout=\"this.src='".$picts[1]."'\" ";
		}
		else {
			$out.=" onmouseover=\"this.src='".$picts[2]."'\" ";
			$out.=" onmouseout=\"this.src='".$picts[0]."'\" ";
		}
		$out.=" />";
		return $out;
	
	}
	
	
	function getParent($pageId) {
		$db=new DB;
		$db->query="SELECT pageId,parentId FROM #__pages WHERE pageId='".$pageId."'";
		$db->setQuery();
		$page=$db->output[0];
	 
		
		
		
		if($page['parentId']>0) {
			$this->parents[]=$page['pageId'];
			$this->getParent($page['parentId']);
		}
	}
	
	
	
	
}

?>