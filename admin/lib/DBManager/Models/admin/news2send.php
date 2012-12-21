<?php

	class news2send extends SQLTable {
		function news2send(&$Con) {
			$this->involvedTables = array('dm_newsletters','dm_newsletters2sendings','dm_sendings');
			$this->_defaultFields = "dm_newsletters.*, dm_newsletters.id as id, dm_sendings.date_sent, dm_sendings.nbr_emails_sent";
			parent::SQLTable($Con,"dm_newsletters","dm_newsletters LEFT JOIN dm_newsletters2sendings ON dm_newsletters.id=dm_newsletters2sendings.newsletter_id LEFT JOIN dm_sendings ON dm_newsletters2sendings.sending_id=dm_sendings.id","id");
			/*
			$metaData_sendings = $this->_Con->metaColumns("dm_sendings");
			if(!empty($metaData_sendings)){
				foreach($metaData_sendings as $k=>$v) {
					if (!isset($this->metaData[$k])) $this->metaData[$k]=$v;
				}
			}
			*/
		}

		function Find($fieldsList = "",$Cond = "",$Order = "",$Limit="", $GroupBy="") {
			if(!$fieldsList) $fieldsList=$this->_defaultFields;
			return parent::Find($fieldsList,$Cond,$Order,$Limit,$GroupBy);
		}
	}
?>