<?php
	class recruteurs_comptes extends SQLTable {
		function recruteurs_comptes(&$Con) {
			$involvedTables = array('eb_recruteurs_mouvements','eb_users','eb_recruteurs_info');
			$this->_defaultFields = "eb_recruteurs_mouvements.*, eb_recruteurs_mouvements.enc_date as mouvement_date, eb_recruteurs_info.*, eb_recruteurs_info.societe as societe, eb_users.deleted, eb_users.inscription_status";
			parent::SQLTable($Con,"eb_recruteurs_mouvements","eb_recruteurs_mouvements LEFT JOIN eb_recruteurs_info ON eb_recruteurs_mouvements.user_id=eb_recruteurs_info.user_id LEFT JOIN eb_users ON eb_recruteurs_mouvements.user_id=eb_users.id","eb_recruteurs_mouvements.id",$involvedTables);
		}

		function Find($fieldsList = "",$Cond = "",$Order = "",$Limit="", $GroupBy="") {
			if(!$fieldsList) $fieldsList=$this->_defaultFields;
			return parent::Find($fieldsList,$Cond,$Order,$Limit,$GroupBy);
		}
	}
?>