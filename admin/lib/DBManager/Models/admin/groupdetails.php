<?php
	class groupdetails extends SQLTable {
		function groupdetails(&$Con) {
			$this->involvedTables = array('dm_subscribers','dm_subscribers2groups');
			$this->_defaultFields = "dm_subscribers.*, dm_subscribers2groups.*";
			parent::SQLTable($Con,"dm_subscribers","dm_subscribers2groups LEFT JOIN dm_subscribers ON dm_subscribers.id=dm_subscribers2groups.subscriber_id","id");
		}

		function Find($fieldsList = "",$Cond = "",$Order = "",$Limit="", $GroupBy="") {
			if(!$fieldsList) $fieldsList=$this->_defaultFields;
			return parent::Find($fieldsList,$Cond,$Order,$Limit,$GroupBy);
		}
	}
?>