<?php
	class stats_recruteurs extends SQLTable {
		function stats_recruteurs(&$Con) {
			$involvedTables = array('eb_recruteurs_info','eb_users','eb_operations','eb_offres');
			$this->_defaultFields = "
				eb_recruteurs_info.*, 
				eb_users.inscription_status, eb_users.inscription_date, 
				eb_users.deleted, 
				(SELECT COUNT(*) FROM eb_offres WHERE eb_offres.user_id = eb_recruteurs_info.user_id) AS nboffres, 
				(SELECT COUNT(DISTINCT(operation_id)) FROM eb_operations WHERE recruteur_id=eb_recruteurs_info.user_id AND push_offres=1) AS nbpush, 
				(SELECT COUNT(*) FROM eb_operations WHERE recruteur_id=eb_recruteurs_info.user_id AND operation_type=3) as nbcvsaved, 
				(SELECT COUNT(*) FROM eb_operations WHERE recruteur_id=eb_recruteurs_info.user_id AND operation_type=1) as nbcvsollicitated, 
				(SELECT COUNT(*) FROM eb_operations WHERE recruteur_id=eb_recruteurs_info.user_id AND operation_type=7) as nbcvreceived, 
				(SELECT COUNT(*) FROM eb_operations WHERE recruteur_id=eb_recruteurs_info.user_id AND (operation_type=6 OR operation_type=11)) as nbhandshake, 
				(SELECT COUNT(*) FROM eb_operations WHERE recruteur_id=eb_recruteurs_info.user_id AND (operation_type=1 OR operation_type=2 OR operation_type=3 OR operation_type=7)) as avg1, 
				((SELECT COUNT(*) FROM eb_operations WHERE recruteur_id=eb_recruteurs_info.user_id AND (operation_type=1 OR operation_type=2 OR operation_type=3 OR operation_type=7))/(SELECT COUNT(*) FROM eb_offres WHERE eb_offres.user_id = eb_recruteurs_info.user_id)) as avgcvoffres, 
				(SELECT COUNT(*) FROM eb_operations WHERE recruteur_id=eb_recruteurs_info.user_id AND operation_type=2) as nbcvbought,
				(SELECT SUM(ABS(montant_eurocents)) FROM eb_recruteurs_mouvements WHERE eb_recruteurs_mouvements.user_id = eb_recruteurs_info.user_id AND montant_eurocents<0) AS amountpaid
			";
			$this->_havingFileds = "
				(SELECT COUNT(*) FROM eb_offres WHERE eb_offres.user_id = eb_recruteurs_info.user_id) AS nboffres, 
				(SELECT COUNT(DISTINCT(operation_id)) FROM eb_operations WHERE recruteur_id=eb_recruteurs_info.user_id AND push_offres=1) AS nbpush, 
				(SELECT COUNT(*) FROM eb_operations WHERE recruteur_id=eb_recruteurs_info.user_id AND operation_type=3) as nbcvsaved, 
				(SELECT COUNT(*) FROM eb_operations WHERE recruteur_id=eb_recruteurs_info.user_id AND operation_type=1) as nbcvsollicitated, 
				(SELECT COUNT(*) FROM eb_operations WHERE recruteur_id=eb_recruteurs_info.user_id AND operation_type=7) as nbcvreceived, 
				(SELECT COUNT(*) FROM eb_operations WHERE recruteur_id=eb_recruteurs_info.user_id AND (operation_type=6 OR operation_type=11)) as nbhandshake, 
				(SELECT COUNT(*) FROM eb_operations WHERE recruteur_id=eb_recruteurs_info.user_id AND (operation_type=1 OR operation_type=2 OR operation_type=3 OR operation_type=7)) as avg1, 
				((SELECT COUNT(*) FROM eb_operations WHERE recruteur_id=eb_recruteurs_info.user_id AND (operation_type=1 OR operation_type=2 OR operation_type=3 OR operation_type=7))/(SELECT COUNT(*) FROM eb_offres WHERE eb_offres.user_id = eb_recruteurs_info.user_id)) as avgcvoffres, 
				(SELECT COUNT(*) FROM eb_operations WHERE recruteur_id=eb_recruteurs_info.user_id AND operation_type=2) as nbcvbought,
				(SELECT SUM(ABS(montant_eurocents)) FROM eb_recruteurs_mouvements WHERE eb_recruteurs_mouvements.user_id = eb_recruteurs_info.user_id AND montant_eurocents<0) AS amountpaid
			";
			parent::SQLTable($Con,"eb_recruteurs_info","eb_recruteurs_info LEFT JOIN eb_users ON eb_recruteurs_info.user_id=eb_users.id","eb_recruteurs_info.user_id",$involvedTables);
		}

		function Find($fieldsList = "",$Cond = "",$Order = "",$Limit="", $GroupBy="",$having="") {
			if(!$fieldsList) $fieldsList=$this->_defaultFields;
			return parent::Find($fieldsList,$Cond,$Order,$Limit,$GroupBy,$having);
		}
	}
?>