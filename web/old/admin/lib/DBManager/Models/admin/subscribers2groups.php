<?php

class subscribers2groups extends SQLTable {
		function subscribers2groups(&$Con) {
			$this->involvedTables = array('dm_sendings','dm_tracking','dm_mailings','dm_newsletters','dm_links');
			$this->_defaultFields = "dm_subscribers2groups.*";
			parent::SQLTable($Con,"dm_subscribers2groups","","subscriber_id");
		}

	/*
	function getSubs2Groups() {
			$rsSubs2Groups = parent::Query("SELECT subscriber_id, count(*) AS ngrp FROM dm_subscribers2groups GROUP BY subscriber_id");
			$aSubs2Groups= array();
				while($rowItem = $rsSubs2Groups->FetchRow()) {
						$aSubs2Groups[$rowItem["subscriber_id"]]=$rowItem["ngrp"];
			}
			return $aSubs2Groups;
		}

	function getGroups2Subs() {
			$rsGroups2Subs = parent::Query("SELECT  group_id , count(*) AS usr FROM dm_subscribers2groups GROUP BY group_id");
			$aGroups2Subs= array();
				while($rowItem = $rsGroups2Subs->FetchRow()) {
						$aGroups2Subs[$rowItem["group_id"]]=$rowItem["usr"];
			}
			return $aGroups2Subs;
		}

	function getSubscribersInGroups($groups) {
		$rsSubsInGroups=parent::Query("SELECT subscriber_id FROM dm_subscribers2groups WHERE group_id IN (".$groups.") GROUP BY subscriber_id");
		while($rowItem = $rsSubsInGroups->FetchRow()){
			$aSubsInGroups[]=$rowItem["subscriber_id"];
		}
		return $aSubsInGroups;
	}


	function getLonelySubs() {
		$rsLonelySubs=parent::Query("select id from dm_subscribers left join dm_subscribers2groups on dm_subscribers.id=dm_subscribers2groups.subscriber_id where dm_subscribers2groups.subscriber_id IS NULL");
		while($rowItem = $rsLonelySubs->FetchRow()){
			$aLonelySubs[]=$rowItem["id"];
		}
		return $aLonelySubs;
	}
	*/


}

?>