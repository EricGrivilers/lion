<?php

class site {

	var $siteId;
	var $domain;
	var $subDomain;
	var $db;
	var $menuArray=array();
	var $homepageId;
	var $languages;
	var $language;
	
	
	function setLanguage() {
		if(isset($_GET['language'])) {
			$_SESSION['language']=$_GET['language'];
		}
		if(empty($_SESSION['language'])) {
			$_SESSION['language']='fr';
		}
	}
	
	
}
?>