<?php
	class candidats_comptes extends SQLTable {
		function candidats_comptes(&$Con) {
			$involvedTables = array('eb_candidats_mouvements','eb_users','eb_candidats_info');
			$this->_defaultFields = "eb_candidats_mouvements.*, eb_candidats_mouvements.enc_date as mouvement_date, eb_candidats_info.*, eb_users.deleted, eb_users.inscription_status";
			parent::SQLTable($Con,"eb_candidats_mouvements","eb_candidats_mouvements LEFT JOIN eb_candidats_info ON eb_candidats_mouvements.user_id=eb_candidats_info.user_id LEFT JOIN eb_users ON eb_candidats_mouvements.user_id=eb_users.id","eb_candidats_mouvements.id",$involvedTables);
		}

		function Find($fieldsList = "",$Cond = "",$Order = "",$Limit="", $GroupBy="") {
			if(!$fieldsList) $fieldsList=$this->_defaultFields;
			return parent::Find($fieldsList,$Cond,$Order,$Limit,$GroupBy);
		}
	}
?>