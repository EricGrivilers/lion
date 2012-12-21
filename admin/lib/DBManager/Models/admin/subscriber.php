<?php
	class subscriber extends SQLTable {
		function subscriber(&$Con) {
			$this->involvedTables = array('dm_subscribers');
			$this->_defaultFields = "dm_subscribers.*";
			parent::SQLTable($Con,"dm_subscribers","dm_subscribers","id");
		}
		
		function Save($params) {
			if ($params["id"]==0) {
				if (!isset($params["date_created"])) $params["date_created"] = date("Y-m-d H:i");
			}
			$params["date_updated"]=date("Y-m-d H:i");
			return parent::Save($params);
		}

		/*
		function lastInsert_Id() {
			$rsLastInsert_id = parent::Query("SELECT LAST_INSERT_ID() as idz");
			if($rowItem = $rsLastInsert_id->FetchRow()) {
				return $rowItem["idz"];
			}
		}

		function Find($fieldsList = "",$Cond = "",$Order = "",$Limit="", $GroupBy="") {
			if(empty($fieldsList)){
				$fieldsList = $this->_defaultFields;
			}
			return parent::Find($fieldsList,$Cond,$Order,$Limit,$GroupBy);
		}

		function getNlSent() {
			return parent::Query("SELECT dm_newsletters.title,dm_newsletters.id FROM dm_newsletters INNER JOIN dm_mailings WHERE dm_mailings.newsletter_id=dm_newsletters.id GROUP BY dm_mailings.newsletter_id ORDER BY dm_newsletters.title");
		}

		function getNlNotSent() {
			return parent::Query("SELECT id,title FROM dm_newsletters WHERE id NOT IN(SELECT newsletter_id FROM dm_mailings)");
		}

		function getUserGroups($id) {
			$aUserGroups=array();
			$rsUserGroups=parent::Query("SELECT group_id FROM dm_subscribers2groups WHERE subscriber_id='".$id."'");
			while($rowItem = $rsUserGroups->FetchRow()){
				array_push($aUserGroups,$rowItem["group_id"]);
			}
			return $aUserGroups;
		}

		function setUserGroups($id,$groups) {
			if(is_null($groups)) {
				return true;
			}
			else {
				foreach($groups as $k => $groupid) {
					parent::Query("INSERT IGNORE INTO dm_subscribers2groups SET group_id='".$groupid."' , subscriber_id='".$id."'");
				}
			}
		}
		*/
	}
?>