<?php
	class newsletter extends SQLTable {
		function newsletter(&$Con) {
			$this->involvedTables = array('dm_newsletters','dm_countries');
			$this->_defaultFields = "dm_newsletters.*, dm_languages.*";
			parent::SQLTable($Con,"","dm_newsletters LEFT JOIN dm_languages ON dm_newsletters.lang=dm_languages.iso","id",$this->involvedTables);
		}

		/*
		function returnNextIncrement() {
			$rsNextIncrement = parent::Query("SHOW TABLE STATUS LIKE 'dm_newsletters'");
			if($rowItem = $rsNextIncrement->FetchRow()) {
				return $rowItem["Auto_increment"];
			}
		}
		*/
		
		function Save($params) {
			//print_r($params);
			if ($params["id"]==0) {
				if (!isset($params["date_created"])) $params["date_created"] = date("Y-m-d H:i");
			}else{
				unset($params["ref_num"]);
			}
			$params["date_updated"]=date("Y-m-d H:i");
			return parent::Save($params);
		}

		function Find($fieldsList = "",$Cond = "",$Order = "",$Limit="", $GroupBy="") {
			if(!$fieldsList) $fieldsList=$this->_defaultFields;
			return parent::Find($fieldsList,$Cond,$Order,$Limit,$GroupBy);
		}
	}
?>