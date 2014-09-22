<?php

class page {

	
	
	
	var $pageId;
	var $templateId;
	var $title;
	var $rank;
	var $templateName;
	var $templateFolder;
	var $keywords;
	var $description;
	var $areas=array();
	var $contents=array();
	var $db;
	var $styles=array();
	var $jss=array();
	
	function init() {
		//unset($_SESSION['elements']);
		$this->parseUrl();
		if(!isset($_GET['pageId'])) {
			$db=new db;
			$db->query="SELECT pageId FROM #__pages WHERE isHomepage='1'";
			$db->resultType='array';
			$db->setQuery();
			$_GET['pageId']=$db->output[0]['pageId'];
		}
		$this->pageId=$_GET['pageId'];
		$this->db->debug=true;
		$this->getTemplate();
		//$this->preProcess();
		$this->getContents();
		
		
	}
	
	function isFolder($pageId) {
		$isFolder=0;
		$pid=0;
		$db=new DB;
		$db->query="SELECT a.pageId,a.isFolder,count(b.parentId) as pid FROM #__pages a LEFT JOIN #__pages b ON b.parentId=a.pageId WHERE a.pageId='".$pageId."' GROUP BY b.parentId ";
		$db->setQuery();
		//echo $db->query;
		$isFolder=$db->output[0]['isFolder'];
		$pid=$db->output[0]['pid'];
		if($isFolder>0 && $pid>0) {
			while($isFolder==1 && $pid>0) {
				$db=new DB;
				$db->query="SELECT a.pageId,a.isFolder,count(b.parentId) as pid,dst FROM #__pages a LEFT JOIN #__pages b ON b.parentId=a.pageId LEFT JOIN #__aliases ON #__aliases.src=CONCAT('pageId=',a.pageId) WHERE a.parentId='".$pageId."' GROUP BY b.pageId ORDER BY a.rank ";
				$db->setQuery();
				$isFolder=$db->output[0]['isFolder'];
				$pid=$db->output[0]['pid'];
				$dst=$db->output[0]['dst'];
				$pageId=$db->output[0]['pageId'];
			}
			header("location:/".$dst);
			
		}
	}
	
	function parseUrl() {
	
		/* immolion*/
		/*if($_GET['q']>0) {
			$db=new DB;
			$db->query="SELECT location FROM items WHERE reference='030/".$_GET['q']."' ";
			$db->setQuery();
			if($r=$db->output[0]) {
				if($r['location']=='Y') {
					$_GET['q']="location/".$_GET['q'];
				}
				else {
					$_GET['q']="vente/".$_GET['q'];
				}
			}
		}
		*/
		$db=new DB;
		$db->query="SELECT src from #__aliases WHERE dst='".$_GET['q']."' LIMIT 0,1";
		$db->setQuery();
		$rows=$db->output;
		$t=explode("&",$rows[0]['src']);
		foreach($t as $q)  {
			$tt=explode("=",$q);
			$_GET[$tt[0]]=$tt[1];
		}
		
		$this->isFolder($_GET['pageId']);
		
		
		$t=explode('/',$_GET['q']);
		if($t[0]=='vente' && $t[1]>0) {
			//header('location:'.__web__."vente?ref=".$t[1]);
			$_GET['pageId']=5;	
			$_GET['ref']=$t[1];	
		}
		if($t[0]=='location' && $t[1]>0) {
			//header('location:'.__web__."location?ref=".$t[1]);
			$_GET['pageId']=6;	
			$_GET['ref']=$t[1];	
		}
		//$_GET['pageId']=$rows[0]['pageId'];
		//print_r($_GET);
	}
	
	
	function getTemplate() {
		$db=new DB;
		//$this->db->debug=true;
		$db->query="SELECT * from #__pages LEFT JOIN #__templates ON #__templates.templateId=#__pages.templateId where #__pages.pageId='".$this->pageId."'";
		$db->setQuery();
		$rows=$db->output;
		$this->templateId=$rows[0]['templateId'];
		$this->templateName=$rows[0]['name'];
		$this->title=$rows[0]['title'];
		$this->templateFolder=$rows[0]['folder'];
		$this->keywords=$rows[0]['keywords'];
		$this->description=$rows[0]['description'];
		$this->areas=explode(",",$rows[0]['areas']);
		define(__tplFolder__,__lib__."templates/".$this->templateName."/");
		
		if($_GET['ref']>0) {
			$db=new DB;
			$db->query="SELECT * FROM items WHERE reference='030/".$_GET['ref']."'";
			$db->setQuery();
			//echo $db->query;
			//print_r($db->output);
			$r=$db->output[0];
			//print_r($r);
			if($r['type']==1) {
				$ttype="Maison";
			}
			else if($r['type']==2) {
				$ttype="Appartement";
			}
			if($r['location']=='Y') {
				$ltype="Location";
			}
			else if($r['type']==2) {
				$ltype="Vente";
			}
			$this->description=$r['descrfr'];
			$this->title=$ltype." ".$ttype." - ".$r['locfr']." - Immobilière Le Lion";
		}
		//$_SESSION['page']['areas']=$this->areas;
	}
	
	
	
	
	
	
	function getContents() {
	
		$rows=array();
		//foreach($this->areas as $k=>$area) {
			$db=new DB;
			//$this->db->debug=true;
			$q="SELECT #__contents.*,#__content2page.rank,#__content2page.area,#__content2page.public as isVisible, #__elements.name as elementName,#__elements.label as elementLabel,#__elements.type FROM #__contents LEFT JOIN #__content2page ON #__content2page.contentId=#__contents.contentId LEFT JOIN #__elements ON #__elements.elementId=#__contents.elementId WHERE (language='".$_SESSION['language']."' OR language='') AND (pageId='".$this->pageId."' OR #__contents.global='1') ";
			if(!$_SESSION['admin']) {
				$q.=" AND  #__content2page.public='1' ";
			}
			$q.="ORDER BY #__content2page.rank";
			
			$db->query=$q;
			$db->setQuery();
			$rows=$db->output;
	
	
			$i=0;
			//while($rows[$i]) {
			foreach($rows as $row) {
				$params=json_decode($rows[$i]['params']);
				$styles=json_decode($rows[$i]['styles']);
				
			
				$area=$row['area'];

				if($this->toDisplay($params)) {
					/*$this->contents[$area][$rows[$i]['rank']]['elementId']=$rows[$i]['elementId'];
					$this->contents[$area][$rows[$i]['rank']]['elementName']=$rows[$i]['elementName'];
					$this->contents[$area][$rows[$i]['rank']]['elemType']=$rows[$i]['elemType'];
					$this->contents[$area][$rows[$i]['rank']]['elementLabel']=$rows[$i]['elementLabel'];
					$this->contents[$area][$rows[$i]['rank']]['contentId']=$rows[$i]['contentId'];
					$this->contents[$area][$rows[$i]['rank']]['title']=$rows[$i]['title'];
					$this->contents[$area][$rows[$i]['rank']]['content']=$rows[$i]['content'];
					$this->contents[$area][$rows[$i]['rank']]['classes']=$rows[$i]['classes'];
					$this->contents[$area][$rows[$i]['rank']]['styles']=$styles;
				//	if(function_exists("json_decode")) {
					$this->contents[$area][$rows[$i]['rank']]['params']=$params;
				//	}
					$this->contents[$area][$rows[$i]['rank']]['isVisible']=$rows[$i]['isVisible'];
					*/
					$this->contents[$area][$row['rank']]=$row;
					$this->contents[$area][$row['rank']]['styles']=$styles;
					$this->contents[$area][$row['rank']]['params']=$params;
				}
				$i++;
			}
			
		//}
		
		
		
	
	
	}
	
	
	function getElements($area) {

		$counter=1;
		$out='';
		if($this->contents[$area]) {
		
			foreach($this->contents[$area] as $rank=>$content) {
				$element=new element;
				$element->elementId=$content['elementId'];
				$element->elementName=$content['elementName'];
				$element->elemType=$content['elemType'];
				$element->elementLabel=$content['elementLabel'];
				$element->counter=$counter;
				$element->classes=$content['classes'];
				$element->area=$area;
				$element->contentId=$content['contentId'];
				$element->elementTitle=$content['title'];
				$element->elementContent=$content['content'];
				$element->params=$content['params'];
				$element->styles=$content['styles'];
				$element->isVisible=$content['isVisible'];
				$out.=$element->getContent();
				//$this->styles[]=$element->getStyles();
				$counter++;
			}
		}
		
		return $out;
			
	}
	
	
	function toDisplay($params) {
		$excludedPages=array();
		if($params->ePP>0) {
			$db=new DB;
			$db->query="SELECT pageId FROM #__pages WHERE parentId='".$params->ePP."'";
			$db->setQuery();
			
			foreach($db->output as $k=>$page) {
				$excludedPages[]=$page['pageId'];
			}
		}
		//if(in_array($_GET['pageId'],$excludedPages)) {
		if(in_array($this->pageId,$excludedPages)) {
			return false;
		}
		return true;
	}


}
?>