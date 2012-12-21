<?php
	class statistic extends SQLTable {
		function statistic(&$Con) {
			//$this->_defaultFields = "dm_mailings.*, dm_newsletters.title as title, COUNT(dm_sendings.subscriber_id) as nbsent";
			//parent::SQLTable($Con,"dm_mailings","dm_mailings LEFT JOIN dm_newsletters on dm_mailings.newsletter_id=dm_newsletters.id LEFT JOIN dm_sendings ON dm_sendings.mailing_id=dm_mailings.id","id");
			$this->involvedTables = array('dm_mailings','dm_newsletters');
			$this->_defaultFields = "dm_mailings.*, dm_newsletters.title as title";
			parent::SQLTable($Con,"dm_mailings","dm_mailings LEFT JOIN dm_newsletters on dm_mailings.newsletter_id=dm_newsletters.id","id");
		}

		/*
		function returnNextIncrement() {
			$rsNextIncrement = parent::Query("SHOW TABLE STATUS LIKE 'dm_mailings'");
			if($rowItem = $rsNextIncrement->FetchRow()) {
				return $rowItem["Auto_increment"];
			}
		}

		function returnMax($sField) {
			$rsReturnMax = parent::Query("SELECT MAX(".$sField.") FROM dm_mailings");
			if($rowItem = $rsReturnMax->FetchRow()) {
				foreach($rowItem as $v){
					return $v;
				}
			}
		}
		
		function lastInsert_Id() {
			$rsLastInsert_id = parent::Query("SELECT LAST_INSERT_ID() as idz");
			if($rowItem = $rsLastInsert_id->FetchRow()) {
				return $rowItem["idz"];
			}
		}

		function getNewsletterName($sMailingId) {
			$rsNewsletter = parent::Query("SELECT dm_newsletters.title FROM dm_newsletters INNER JOIN dm_mailings ON dm_newsletters.id=dm_mailings.newsletter_id WHERE dm_mailings.id=$sMailingId");
			if($rowItem = $rsNewsletter->FetchRow()) {
				return $rowItem["title"];
			}
		}
		*/


		function Save($params) {
			//print_r($params);
			if ($params["id"]==0) {
				if (!isset($params["date_created"])) $params["date_created"] = date("Y-m-d H:i");
			}else{
			}
			return parent::Save($params);
		}

		function Find($fieldsList = "",$Cond = "",$Order = "",$Limit="", $GroupBy="") {
			if(!$fieldsList) $fieldsList=$this->_defaultFields;
			return parent::Find($fieldsList,$Cond,$Order,$Limit,$GroupBy);
		}
	}
?>