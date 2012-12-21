<?php
	class recruteurs_offres extends SQLTable {
		function recruteurs_offres(&$Con) {
			$involvedTables = array('eb_offres','eb_recruteurs_info','eb_operations');
			$this->_defaultFields = "eb_offres.*, eb_offres.region as region_offre, eb_recruteurs_info.*, eb_users.inscription_status, eb_users.deleted, (SELECT COUNT(*) FROM eb_operations WHERE eb_offres.user_id = eb_operations.recruteur_id AND operation_type=8 AND eb_offres.id=eb_operations.offre_id) AS saved, (SELECT COUNT(*) FROM eb_operations WHERE eb_offres.user_id = eb_operations.recruteur_id AND operation_type=7 AND eb_offres.id=eb_operations.offre_id) AS sollicitated";
			parent::SQLTable($Con,"eb_offres","eb_offres LEFT JOIN eb_recruteurs_info ON eb_offres.user_id=eb_recruteurs_info.user_id LEFT JOIN eb_users ON eb_offres.user_id=eb_users.id","eb_offres.id",$involvedTables);
		}

		function Find($fieldsList = "",$Cond = "",$Order = "",$Limit="", $GroupBy="") {
			if(!$fieldsList) $fieldsList=$this->_defaultFields;
			return parent::Find($fieldsList,$Cond,$Order,$Limit,$GroupBy);
		}
	}
?>