<?php
	class group extends SQLTable {
		function group(&$Con) {
			$this->involvedTables = array('dm_groups','dm_subscribers2groups');
			$this->_defaultFields = "dm_groups.*, COUNT(dm_subscribers2groups.subscriber_id) as nbusers";
			parent::SQLTable($Con,"dm_groups","dm_groups LEFT JOIN dm_subscribers2groups ON dm_groups.id=dm_subscribers2groups.group_id","id");
		}
		
		function Save($params) {
			if ($params["id"]==0) {
				if (!isset($params["date_created"])) $params["date_created"] = date("Y-m-d H:i");
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