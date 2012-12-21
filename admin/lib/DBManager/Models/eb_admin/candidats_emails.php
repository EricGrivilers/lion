<?php
	class candidats_emails extends SQLTable {
		function candidats_emails(&$Con) {
			$involvedTables = array('eb_emails','eb_emails_types','eb_users','eb_recruteurs_info','eb_candidats_info');
			$this->_defaultFields = "eb_emails.*, eb_emails_types.*, eb_candidats_info.*, eb_candidats_info.nom as nom_candidat, eb_candidats_info.prenom as prenom_candidat, eb_candidats_info.email as candidat_email, eb_candidats_info.region as candidat_region, eb_recruteurs_info.*, eb_recruteurs_info.email as recruteur_email, eb_recruteurs_info.region as recruteur_region, eb_users.user_type, eb_users.deleted, eb_users.inscription_status";
			parent::SQLTable($Con,"eb_emails","eb_emails LEFT JOIN eb_emails_types ON eb_emails.email_type_id=eb_emails_types.id LEFT JOIN eb_candidats_info ON eb_emails.candidat_id=eb_candidats_info.user_id LEFT JOIN eb_recruteurs_info ON eb_emails.recruteur_id=eb_recruteurs_info.user_id LEFT JOIN eb_users ON eb_emails.candidat_id=eb_users.id","eb_emails.email_id",$involvedTables);
		}

		function Find($fieldsList = "",$Cond = "",$Order = "",$Limit="", $GroupBy="") {
			if(!$fieldsList) $fieldsList=$this->_defaultFields;
			return parent::Find($fieldsList,$Cond,$Order,$Limit,$GroupBy);
		}
	}
?>