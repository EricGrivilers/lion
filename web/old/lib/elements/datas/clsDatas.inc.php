<?php

class datas {
	var $maxRows=0;
	var $datas=array();
	var $fields=array();
	var $key=0;
	
	var $limitStart=0;
	var $limitLength=25;
	var $fillToEnd=true;
	
	function getTable() {
		
		$out.="<form class='dataGrid'>";
		$out.="<input type='hidden' class='orderBy' value='".$this->orderBy."' />";
		$out.="<input type='hidden' class='limitStart' value='".$this->limitStart."' />";
		$out.="<input type='hidden' class='limitLength' value='".$this->limitLength."' />";
		$out.="<input type='hidden' class='orderSort' value='".$this->orderSort."' />";
		if($this->trClick) {
			$out.="<input type='hidden' name='onclick' value=\"".$this->trClick."\" />";
			
		}
		$out.="<table class=\"dataGrid\">";
		$out.=$this->addHeader();
		$out.="<tbody>";
		$i=1;
		foreach($this->datas as $k=>$data) {
			$out.=$this->addRow($data);
			$i++;
		}
		if($this->fillToEnd && $i<=$this->limitLength) {
			while($i<=$this->limitLength) {
				$out.=$this->addRow(NULL);
				$i++;
			}
		}
		$out.="</tbody>";
		$out.=$this->addFooter();
		$out.="</table>";
		if($this->nextBack) {
			$out.=$this->getNextBack();
		}
		$out.="</form>";
		return $out;
	}
	
	function addHeader() {
		
		$out.="<thead><tr>";
		foreach($this->fields as $k=>$l) {
			$out.="<th rel=\"".$l['tf']."\" ";
			if($l['tf']==$this->orderBy) {
			  $out.=" class='current ".strtolower($this->orderSort)."' "; 
			  }
			$out.=">".$l['l']."</th>";
		}
		$out.="</tr></thead>";
		return $out;
	}
	
	function addFooter() {
		$out.="<tfoot><tr>";
		foreach($this->fields as $k=>$l) {
			$out.="<td ></td>";
		}
		$out.="</tr></tfoot>";
		return $out;
	}
	
	function addRow($d) {
		$od=array('odd','even');
		$o=0;
		$out="<tr rel=\"".$d[$this->key]."\" class=\"".$od[$o]."\">";
		foreach($this->fields as $k=>$l) {
			$out.="<td rel='".$l['tf']."'>";
			if($d) {
				$label=$d[$l['f']];
				if($l['tf']=='button') {
					$label=$l['f'];
					$label="<a onclick=\"".$this->fillVar($l['onclick'],$d)."\">".$label."</a>";
				}
				else if($l['a']) {
					$label="<a href=\"".$this->fillVar($l['a'],$d)."\">".$label."</a>";
				}
				else if($l['onclick']) {
					$label="<a onclick=\"".$this->fillVar($l['onclick'],$d)."\">".$label."</a>";
				}
				$out.=$label;
			}
			else {
				$out.="&nbsp;";
			}
			
			$out.="</td>";
			$o=!$o;
		}
		$out.="</tr>";
		return $out;
	}
	
	
	function getNextBack() {
		$out.="<div class='nextback'><table><tr>";
		if($this->limitStart>0) {
			$out.="<td><a onclick=\"$.datas.nextDatas($(this),-1,".$this->limitStart.",".$this->limitLength.")\">back</a></td>";
		}
		$cPage=$this->limitStart/$this->limitLength+1;
		
		$out.="<td>pp. <input type='text' class='limitStart' value='".$cPage."' size='4'  />/".(floor($this->maxRows/$this->limitLength)+1)." | <a onclick=\"$.datas.jumpToPage($(this),".$this->limitLength.")\">go</a></td>";
		if($this->limitStart<($this->maxRows-$this->limitLength)){
			$out.="<td><a onclick=\"$.datas.nextDatas($(this),1,".$this->limitStart.",".$this->limitLength.")\">next</a></td>";
		}
		$out.="</tr></table></div>";
		return $out;
	}
	
	function fillVar($a,$d) {
		$regexp = "/{(.*)}/Ui";
		$p=array();
		$r=array();
		preg_match_all($regexp, $a, $out, PREG_PATTERN_ORDER);
		foreach($out[0] as $k=>$v) {
			$p[]="/".$v."/";
		}
		foreach($out[1] as $k=>$v) {
			$r[]=$d[$v];
		}
		return preg_replace($p,$r,$a);
		
	}
}

?>