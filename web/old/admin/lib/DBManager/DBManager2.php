<?php
/*
DBMANAGER DB CONNECTOR
copyright Cedric Billiet 2007
*/

//require_once("adodb/adodb.inc.php");


require_once("MDB2.php");
require("DBManager/SQLTable2.inc.php");

if(!class_exists('DBManager')){
	class DBManager {
		var $models_dir="models";

		function DBManager($dsn) {
			//$this->_Con = ADONewConnection($dbdriver);
			//$this->_Con->SetFetchMode(ADODB_FETCH_ASSOC);

			$this->_Con =& MDB2::factory($dsn);

			if (PEAR::isError($this->_Con)) {
				//die('oops');
				return PEAR::raiseError(__FILE__.' [ '.__LINE__.'] Erreur initialisation MDB2: '.$this->_Con->getMessage());
			}

			$this->_Con->setFetchMode(MDB2_FETCHMODE_ASSOC);
			$this->_Con->setOption('portability', MDB2_PORTABILITY_NONE);

			//MDB2 LOAD MANAGER TO GET COLUMN NAMES AND TYPES (listTableFields)- CED
			$this->_Con->loadModule('Reverse', null, true);
			$this->_Con->loadModule('Manager', null, true);


			//TABLES SIMPLES
			$this->TblQuartiers = & new SQLTable($this->_Con,"quartiers","","id");
			$this->TblSlideshow = & new SQLTable($this->_Con,"slideshow","","id");
			$this->TblBanners = & new SQLTable($this->_Con,"banners","","id");
			$this->TblChapters = & new SQLTable($this->_Con,"chapters","","id");
			$this->TblItems = & new SQLTable($this->_Con,"items","","num");
			$this->TblUsers2items = & new SQLTable($this->_Con,"users2items","","itemId");
			$this->TblLocations = & new SQLTable($this->_Con,"locations","","id");
			$this->TblType = & new SQLTable($this->_Con,"type","","id");
			$this->TblPhoto2item = & new SQLTable($this->_Con,"photo2item","","id");
			$this->TblChapters = & new SQLTable($this->_Con,"chapters","","id");


			//function SQLTable(&$oConn,$tableName,$viewName="",$primaryKey="",$involvedTables="")
			//VUES COMPLEXES
			$this->items = & new SQLTable($this->_Con,"items","items LEFT JOIN quartiers ON items.quartier_id=quartiers.id","items.num",array('items','quartiers'));
			$this->items->_defaultFields=array("items.*","quartiers.nom_quartier","quartiers.googlecode");
			$this->items->_havingFields=array();

			$this->slideshow = & new SQLTable($this->_Con,"slideshow","slideshow LEFT JOIN items ON slideshow.item_id=items.num","slideshow.item_id",array('slideshow','items'));
			$this->slideshow->_defaultFields=array("items.*","slideshow.ranking","slideshow.id");
			$this->slideshow->_havingFields=array();

		}
		
		function Query($sql) {
			return $this->_Con->query($sql);
		}
		
		function ErrorMsg() {
			return $this->_Con->ErrorMsg();
		}
	}
}
?>