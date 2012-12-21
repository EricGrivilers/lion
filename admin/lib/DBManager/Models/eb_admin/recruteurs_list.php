<?php
	class recruteurs_list extends SQLTable {
		function recruteurs_list(&$Con) {
			$involvedTables = array('eb_recruteurs_info');
			$this->_defaultFields = "eb_recruteurs_info.*, eb_users.inscription_status, eb_users.inscription_date, eb_users.deleted, (SELECT COUNT(*) FROM eb_offres WHERE eb_offres.user_id = eb_recruteurs_info.user_id) AS nboffres, (SELECT COUNT(*) FROM eb_recruteurs_cv WHERE eb_recruteurs_cv.recruteur_id = eb_recruteurs_info.user_id) AS nbcv, (SELECT SUM(montant_eurocents) FROM eb_recruteurs_mouvements WHERE eb_recruteurs_mouvements.user_id = eb_recruteurs_info.user_id AND montant_eurocents>0) AS paid, (SELECT SUM(montant_eurocents) FROM eb_recruteurs_mouvements WHERE eb_recruteurs_mouvements.user_id = eb_recruteurs_info.user_id) AS solde";
			parent::SQLTable($Con,"eb_recruteurs_info","eb_recruteurs_info LEFT JOIN eb_users ON eb_recruteurs_info.user_id=eb_users.id","eb_recruteurs_info.user_id",$involvedTables);
		}

		function Find($fieldsList = "",$Cond = "",$Order = "",$Limit="", $GroupBy="") {
			if(!$fieldsList) $fieldsList=$this->_defaultFields;
			return parent::Find($fieldsList,$Cond,$Order,$Limit,$GroupBy);
		}
	}
?>