<?php
	class candidats_list extends SQLTable {
		function candidats_list(&$Con) {
			$involvedTables = array('eb_candidats_info');
			//$this->_defaultFields = "eb_candidats_info.*, eb_users.inscription_status, eb_users.deleted, (SELECT COUNT(*) FROM eb_recruteurs_2_candidats WHERE eb_recruteurs_2_candidats.candidat_id = eb_candidats_info.user_id AND (link_type='sollicitated' OR link_type='bought')) AS nbsollicitations, (SELECT COUNT(*) FROM eb_candidats_2_certifications WHERE eb_candidats_2_certifications.user_id=eb_candidats_info.user_id AND certification_type='cert_addr') AS cert_addr, (SELECT COUNT(*) FROM eb_candidats_2_certifications WHERE eb_candidats_2_certifications.user_id=eb_candidats_info.user_id AND certification_type='cert_dipl') AS cert_dipl, (SELECT COUNT(*) FROM eb_candidats_2_certifications WHERE eb_candidats_2_certifications.user_id=eb_candidats_info.user_id AND certification_type='cert_exp') AS cert_exp";
			$this->_defaultFields = "eb_candidats_info.*, eb_users.inscription_status, eb_users.inscription_date, eb_users.deleted, (SELECT COUNT(*) FROM eb_operations WHERE eb_operations.candidat_id = eb_candidats_info.user_id AND operation_type=1) AS nbsollicitations";
			parent::SQLTable($Con,"eb_candidats_info","eb_candidats_info LEFT JOIN eb_users ON eb_candidats_info.user_id=eb_users.id","eb_candidats_info.user_id",$involvedTables);
		}

		function Find($fieldsList = "",$Cond = "",$Order = "",$Limit="", $GroupBy="") {
			if(!$fieldsList) $fieldsList=$this->_defaultFields;
			return parent::Find($fieldsList,$Cond,$Order,$Limit,$GroupBy);
		}
	}
?>