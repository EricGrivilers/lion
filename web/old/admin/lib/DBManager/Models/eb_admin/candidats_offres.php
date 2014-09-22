<?php
	class candidats_offres extends SQLTable {
		function candidats_offres(&$Con) {
			//$involvedTables = array('eb_offres','eb_candidats');
			//$this->_defaultFields = "eb_offres.*, eb_offres.region as region_offre, eb_candidats_info.*, eb_users.inscription_status, eb_users.deleted, (SELECT COUNT(*) FROM eb_recruteurs_2_candidats WHERE eb_offres.user_id = eb_recruteurs_2_candidats.recruteur_id AND initiateur='candidat' AND link_type='saved' AND eb_offres.id=eb_recruteurs_2_candidats.offre_id) AS saved, (SELECT COUNT(*) FROM eb_recruteurs_2_candidats WHERE eb_offres.user_id = eb_recruteurs_2_candidats.recruteur_id AND initiateur='candidat' AND link_type='sollicitated' AND eb_offres.id=eb_recruteurs_2_candidats.offre_id) AS sollicitated";
			//parent::SQLTable($Con,"eb_offres","eb_offres LEFT JOIN eb_candidats_info ON eb_offres.user_id=eb_candidats_info.user_id LEFT JOIN eb_users ON eb_offres.user_id=eb_users.id","eb_offres.id",$involvedTables);

			$involvedTables = array('eb_candidats_offres','eb_candidats_info','eb_offres','eb_users');
			$this->_defaultFields = "eb_candidats_offres.*, eb_offres.*, eb_offres.region as region_offre, eb_candidats_info.*, eb_users.inscription_status, eb_users.deleted";
			parent::SQLTable($Con,"eb_candidats_offres","eb_candidats_offres LEFT JOIN eb_candidats_info ON eb_candidats_offres.candidat_id=eb_candidats_info.user_id LEFT JOIN eb_users ON eb_candidats_offres.candidat_id=eb_users.id LEFT JOIN eb_offres ON eb_candidats_offres.offre_id=eb_offres.id","eb_candidats_offres.id",$involvedTables);
		}

		function Find($fieldsList = "",$Cond = "",$Order = "",$Limit="", $GroupBy="") {
			if(!$fieldsList) $fieldsList=$this->_defaultFields;
			return parent::Find($fieldsList,$Cond,$Order,$Limit,$GroupBy);
		}
	}
?>