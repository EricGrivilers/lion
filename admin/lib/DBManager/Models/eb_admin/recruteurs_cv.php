<?php
	class recruteurs_cv extends SQLTable {
		function recruteurs_cv(&$Con) {
			$involvedTables = array('eb_recruteurs_cv','eb_users','eb_recruteurs_info','eb_candidats_info');
			$this->_defaultFields = "eb_recruteurs_cv.*, eb_recruteurs_cv.enc_date as action_date, eb_users.*, eb_recruteurs_info.*, eb_recruteurs_info.email as recruteur_email, eb_recruteurs_info.region as recruteur_region, eb_candidats_info.*, eb_candidats_info.nom as nom_candidat, eb_candidats_info.prenom as prenom_candidat, eb_candidats_info.email as candidat_email, eb_candidats_info.region as candidat_region";
			parent::SQLTable($Con,"eb_recruteurs_cv","eb_recruteurs_cv LEFT JOIN eb_users ON eb_recruteurs_cv.recruteur_id=eb_users.id LEFT JOIN eb_recruteurs_info ON eb_recruteurs_cv.recruteur_id=eb_recruteurs_info.user_id LEFT JOIN eb_candidats_info ON eb_recruteurs_cv.candidat_id=eb_candidats_info.user_id","eb_recruteurs_cv.id",$involvedTables);
		}

		function Find($fieldsList = "",$Cond = "",$Order = "",$Limit="", $GroupBy="") {
			if(!$fieldsList) $fieldsList=$this->_defaultFields;
			return parent::Find($fieldsList,$Cond,$Order,$Limit,$GroupBy);
		}
	}
?>