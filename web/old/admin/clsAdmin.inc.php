<?php
class admin {
	
	var $orderLimit=0;
	var $tableFields=array(
		'items'=>array('num','reference','locfr','photo','updateDate'),
		'locations'=>array('id','fr','zip'),
		'users'=>array('labelId','name','updateDate'),
		'images'=>array('artistId','firstname','lastname','updateDate'),
	);
	
	
	
	
	
	function init() {
		session_start();
		?>
         <div class="fg-toolbar ui-widget-header ui-corner-all ui-helper-clearfix">
   <a  onclick="$.admin.getList('items')" class='fg-button ui-state-default fg-button-icon-left ui-corner-all'>Biens</a>
   <a  onclick="$.admin.getList('locations')" class='fg-button ui-state-default fg-button-icon-left ui-corner-all'>Quartiers</a>
   <a  onclick="$.admin.getList('users')" class='fg-button ui-state-default fg-button-icon-left ui-corner-all'>Utilisateurs</a>
   <a  onclick="$.admin.getList('images')" class='fg-button ui-state-default fg-button-icon-left ui-corner-all'>Slideshow</a>
    
    
   
    <div id='pageEditor'></div> </div>
        <?php
		
	}
	
	
	function edit() {
		$out.="<input type='hidden' id='elementName' value='".$this->elementName."' />";
		$out.=$this->dataId;
		if(method_exists($this,'edit'.ucfirst($this->elementName))) {
			$out.=call_user_func(array($this,'edit'.ucfirst($this->elementName)));
		}
		return $out;
	}
	
	function saveDatas() {
		if(method_exists($this,'save'.ucfirst($this->datas['elementName']))) {
			$out.=call_user_func(array($this,'save'.ucfirst($this->datas['elementName'])));
		}
		return $out;
	}
	
	/*
	
	function autofill() {
		$out='';
		$db=new DB;
		
		switch($this->autofillA['type']) {
			default:
				$db->query="SELECT * FROM #__artists WHERE LOWER(firstname) REGEXP \"".strtolower($this->autofillA['queryString'])."\" OR LOWER(lastname) REGEXP \"".strtolower($this->autofillA['queryString'])."\"  ORDER BY firstname,lastname";
			break;
			
			case 'band':
				$db->query="SELECT * FROM #__bands WHERE LOWER(name) REGEXP \"".strtolower($this->autofillA['queryString'])."\" ORDER BY name";
				$idField='bandId';
			break;
			
			case 'label':
				$db->query="SELECT * FROM #__labels WHERE LOWER(name) REGEXP \"".strtolower($this->autofillA['queryString'])."\" ORDER BY name";
				$idField='labelId';
			break;
			
		}
		
		
		$db->setQuery();
		
		if($this->autofillA['type']=='artist') {
			$artists=$db->output;
			if(count($db->output)>0) {
				foreach($artists as $k=>$artist) {
					$name=$artist['firstname'];
					if($artist['lastname']!='') {
						$name.=",".$artist['lastname'];
					}
					$label=$name;
					$name=str_replace($this->autofillA['queryString'],"<b>".$this->autofillA['queryString']."</b>",$name);
					$db=new DB;
					$db->query="SELECT #__artist2record.category,GROUP_CONCAT(CONCAT(#__records.title,' (',#__bands.name,')') SEPARATOR ', ') AS r FROM #__artist2record LEFT JOIN #__records ON #__records.recordId=#__artist2record.recordId LEFT JOIN #__band2record ON #__band2record.recordId=#__artist2record.recordId LEFT JOIN #__bands ON #__bands.bandId=#__band2record.bandId WHERE artistId='".$artist['artistId']."' GROUP BY #__artist2record.artistId,#__artist2record.category";
					$db->setQuery();
					//echo $db->query;
					$records=$db->output;
					if(count($db->output)>0) {
						$name.="<div class='sub'>";
						foreach($records as $k=>$record) {
							$name.="<b>".substr($record['category'],0,3)."</b>: ".$record['r']."<br>";
						}
						$name.="</div>";
					}
					
					$out.="<li class='autoLi'  rel='".$artist['artistId']."' title=\"".$label."\" >".$name."</li>";
				}
			}
			else {
				$out='empty';
			}
		}
		else if(count($db->output)>0) {
			foreach($db->output as $k=>$data) {
				$out.="<li class='autoLi'  rel='".$data[$idField]."' title=\"".$data['name']."\" >".$data['name']."</li>";
			}
		}
		return $out;
	
	}
	
	
	*/
	
	
	
	
	
	
	function addData() {
		$db=new DB;
		$db->query="INSERT INTO #__".$this->elementName." (insertDate) VALUES (now())";
		$db->resultType='none';
		$db->setQuery();
		
		return mysql_insert_id();
	}
	
	
	
	
	
	
	
	function listAll() {
		$db=new DB;
		$db->query="SELECT COUNT(".$this->tableFields[$this->listType['type']][0].") as m FROM ".$this->listType['type']."  ";
		if($_POST['filter']) {
			$db->query.=" WHERE LOWER(".$this->tableFields[$this->listType['type']][1].") LIKE '".$_POST['filter']."%' ";
		}
		//echo $db->query;
		
		$db->setQuery();
		$m=$db->output[0]['m'];
		$db=new DB;
		$db->query="SELECT * ";
		$db->query.=" FROM ".$this->listType['type']." ";
		if($_POST['filter']) {
			$db->query.=" WHERE LOWER(".$this->tableFields[$this->listType['type']][1].") LIKE '".$_POST['filter']."%' ";
		}
		$db->query.=" ORDER BY LOWER(".$this->tableFields[$this->listType['type']][1].") LIMIT ".$this->orderLimit.",15 ";
		$db->setQuery();
		//echo $db->query;
		
		$this->datas=$db->output;
		$fields=$this->tableFields[$this->listType['type']];
		$out.="<div style='margin-bottom:5px'><a onclick=\"$.admin.addData('".$this->listType['type']."')\" class='fg-button ui-state-default fg-button-icon-left ui-corner-all' >ajouter</a></div>";
		$out.="<input type='hidden' name='filter' value='".$_POST['filter']."' />";
		$out.="<table class='list'>";
		$out.="<thead><tr>";
		foreach($fields as $e=>$f) {
			$out.="<th>".$f."</th>";	
		}
		
		$out.="</tr></thead>";
		$out.="<tbody class='admin'>";
		
		$idField=$fields[0];
		
		
		foreach($this->datas as $k=>$data) {
			$out.="<tr class='admin' onclick=\"$.admin.editData('".substr($this->listType['type'],0,strlen($this->listType['type'])-1)."','".$data[$idField]."')\">";
			foreach($fields as $e=>$f) {
			
				if($f=='photo') {
					$out.="<td><img src='/photos/thumbs/".$data[$f].".jpg' height='50' /></td>";
				}
				else {
				$out.="<td>".$data[$f]."</td>";
				}
			}
			$out.="</tr>";
		}
		$out.="</tbody>";
		$out.="</table>";
		$out.="<div style='text-align:center'>";
		if($this->orderLimit>0) {
			$out.=" <a onclick=\"nextBack('".$this->listType['type']."','".($this->orderLimit-15)."')\"> prev </a> - ";		
		}
		$out.=($this->orderLimit+1)."-".($this->orderLimit+15)."/".$m;
		if($this->orderLimit+15<$m) {
			$out.=" -  <a onclick=\"nextBack('".$this->listType['type']."','".($this->orderLimit+15)."')\"> next </a> ";		
		}
		$out.="</div>";
		
		
		return $out;
		
	}
	
	
	
	function editItem() {
		$db=new DB;
		$db->query="SELECT * FROM items WHERE num='".$this->dataId."'";
		$db->setQuery();
		$this->item=$db->output[0];
		
		$db=new DB;
		$db->query="SELECT * FROM locations ORDER BY zip,fr";
		$db->setQuery();
		$this->locations=$db->output;
		foreach($this->locations as $l) {
			$t=$l['zip']." - ".$l['fr'];
			$selectLocations.="<option value='".$t."' ";
			if($this->item['locfr']==$l['zip']." ".$l['fr']) {
				$selectLocations.=" selected ";
			}
			$selectLocations.=">".$t."</option>";	
		}
		
		
		$db=new DB;
		$db->query="SELECT * FROM quartiers ORDER BY nom_quartier";
		$db->setQuery();
		$this->quartiers=$db->output;
		foreach($this->quartiers as $l) {
			$t=$l['nom_quartier'];
			$selectQuartiers.="<option value='".$t."' ";
			if($l['id']==$this->item['quartier_id']) {
				$selectQuartiers.=" selected ";
			}
			$selectQuartiers.=">".$t."</option>";	
		}
		
		$db=new DB;
		$db->query="SELECT * FROM photo2item WHERE item_id='".$this->item['num']."' ORDER BY ranking";
		echo $db->query;
		$db->setQuery();
		$this->picts=$db->output;
		//print_r($this->picts);
		foreach($this->picts as $p) {
			//echo 'rr--';
			//$imgs.="<li><img src='".__web__."images/thumbs/".$p['photo']."'/></li>";
			$imgs.="<li><img src='http://www.immo-lelion.be/photos/thumbs/".$p['photo']."'/></li>";
		}
		//echo $imgs;
		
		$template=new template('.');
		$template->set_file("template",__lib__."elements/item/editItem.tpl");
		
		foreach($this->item as $k=>$v) {
			$template->set_var($k, $v);	
		}
		if($this->item['option']=='Y') {
			$template->set_var('hasOption', 'checked');	
		}
		if($this->item['actif']=='Y') {
			$template->set_var('isActive', 'checked');	
		}
		if($this->item['surdemande']=='Y') {
			$template->set_var('surDemande', 'checked');	
		}
		
		if($this->item['location']=='Y') {
			$template->set_var('isLocation', 'checked');	
		}
		else {
			$template->set_var('isVente', 'checked');	
		}
		//$template->set_var('reference', $this->item['reference']);	
		//$template->set_var('prix', $this->item['prix']);	
		$template->set_var('locations', $selectLocations);	
		$template->set_var('quartiers', $selectQuartiers);	
		$template->set_var('images', $imgs);	
		
		
		$template->parse("parse", "template");
		$out.=$template->p("parse",false);
		return $out;
	}
}


?>