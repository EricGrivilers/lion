<?php
	class adminMenu extends SQLTable {
		function adminMenu(&$Con) {
			$involvedTables = array('eb_admin_users_2_panels','eb_admin_panels');
			$this->_defaultFields = "eb_admin_users_2_panels.*, eb_admin_panels.*";
			parent::SQLTable($Con,"eb_admin_users_2_panels","eb_admin_users_2_panels LEFT JOIN eb_admin_panels ON eb_admin_users_2_panels.panel_id=eb_admin_panels.id","eb_admin_users_2_panels.panel_id",$involvedTables);
		}

		function Find($fieldsList = "",$Cond = "",$Order = "",$Limit="", $GroupBy="") {
			if(!$fieldsList) $fieldsList=$this->_defaultFields;
			return parent::Find($fieldsList,$Cond,$Order,$Limit,$GroupBy);
		}
	}
?>