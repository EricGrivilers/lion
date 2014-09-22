<?php
	class stats_candidats extends SQLTable {
		function stats_candidats(&$Con) {
			$involvedTables = array('eb_candidats_info','eb_users','eb_operations','eb_offres');
			$this->_defaultFields = "
				eb_candidats_info.*, 
				eb_users.inscription_status, eb_users.inscription_date, 
				eb_users.deleted, 
				(SELECT COUNT(*) FROM eb_operations WHERE candidat_id=eb_candidats_info.user_id AND operation_type=8) as nboffressaved, 
				(SELECT COUNT(*) FROM eb_operations WHERE candidat_id=eb_candidats_info.user_id AND operation_type=1) as nboffressollicitated, 
				(SELECT COUNT(*) FROM eb_operations WHERE candidat_id=eb_candidats_info.user_id AND operation_type=7) as nboffresreceived, 
				(SELECT COUNT(*) FROM eb_operations WHERE candidat_id=eb_candidats_info.user_id AND operation_type=2) as nbcvbought, 
				(SELECT COUNT(*) FROM eb_operations WHERE candidat_id=eb_candidats_info.user_id AND operation_type=6) as nbcvaccepted, 
				(SELECT COUNT(*) FROM eb_operations t1 WHERE t1.operation_type=7 AND t1.candidat_id=eb_candidats_info.user_id AND t1.operation_id NOT IN (SELECT operation_id FROM eb_operations WHERE candidat_id=t1.candidat_id AND operation_id=t1.operation_id AND (operation_type=5 OR operation_type=6))) as nbcvwaiting, 
				(SELECT COUNT(*) FROM eb_operations WHERE candidat_id=eb_candidats_info.user_id AND operation_type=5) as nbcvdeclined, 
				(SELECT SUM(ABS(montant_eurocents)) FROM eb_candidats_mouvements WHERE eb_candidats_mouvements.user_id = eb_candidats_info.user_id AND montant_eurocents<0) AS amountpaid
			";
			$this->_havingFileds = "
				(SELECT COUNT(*) FROM eb_operations WHERE candidat_id=eb_candidats_info.user_id AND operation_type=8) as nboffressaved, 
				(SELECT COUNT(*) FROM eb_operations WHERE candidat_id=eb_candidats_info.user_id AND operation_type=1) as nboffressollicitated, 
				(SELECT COUNT(*) FROM eb_operations WHERE candidat_id=eb_candidats_info.user_id AND operation_type=7) as nboffresreceived, 
				(SELECT COUNT(*) FROM eb_operations WHERE candidat_id=eb_candidats_info.user_id AND operation_type=2) as nbcvbought, 
				(SELECT COUNT(*) FROM eb_operations WHERE candidat_id=eb_candidats_info.user_id AND operation_type=6) as nbcvaccepted, 
				(SELECT COUNT(*) FROM eb_operations t1 WHERE t1.operation_type=7 AND t1.candidat_id=eb_candidats_info.user_id AND t1.operation_id NOT IN (SELECT operation_id FROM eb_operations WHERE candidat_id=t1.candidat_id AND operation_id=t1.operation_id AND (operation_type=5 OR operation_type=6))) as nbcvwaiting, 
				(SELECT COUNT(*) FROM eb_operations WHERE candidat_id=eb_candidats_info.user_id AND operation_type=5) as nbcvdeclined, 
				(SELECT SUM(ABS(montant_eurocents)) FROM eb_candidats_mouvements WHERE eb_candidats_mouvements.user_id = eb_candidats_info.user_id AND montant_eurocents<0) AS amountpaid
			";
			parent::SQLTable($Con,"eb_candidats_info","eb_candidats_info LEFT JOIN eb_users ON eb_candidats_info.user_id=eb_users.id","eb_candidats_info.user_id",$involvedTables);
		}

		function Find($fieldsList = "",$Cond = "",$Order = "",$Limit="", $GroupBy="",$having="") {
			if(!$fieldsList) $fieldsList=$this->_defaultFields;
			return parent::Find($fieldsList,$Cond,$Order,$Limit,$GroupBy,$having);
		}
	}


	
?>