<?php
/*
SQLTABLE DB CONNECTOR
copyright Cedric Billiet 2007
*/

if(!class_exists('SQLTable')){
	class SQLTable {
	
		var $_Con;
		var $_tableName;
		var $_viewName;
		var $_primaryKey;
		var $metaData;
		var $_involvedTables;

		function SQLTable(&$oConn,$tableName,$viewName="",$primaryKey="",$involvedTables="") {
			if (!$oConn) die("invalid connection!");
			$this->_Con =& $oConn;
			$this->_tableName = $tableName;
			$this->involvedTables = $involvedTables;
			//echo($this->tableInfo($this->_tableName));

			
			//print_r($this->_Con->reverse->tableInfo($this->_tableName));

			if (empty($viewName)) {
				$this->_viewName = $tableName;
			}
			else {
				$this->_viewName = $viewName;
			}

			if (!empty($primaryKey)) $this->_primaryKey = $primaryKey;
			
		}



		function getMetaData(){

			if(is_array($this->involvedTables)){
				$this->tblFields = array();
				$this->metaData = array();

				foreach($this->involvedTables as $k=>$v){

					//$tp = $this->_Con->MetaColumns($v);
					//$tp = $this->_Con->reverse->tableInfo($v,false);
					
					//$tp = $this->_Con->manager->listTableFields($v);
					//$this->tblFields = array_merge($this->tblFields,$tp);

					$rs = $this->_Con->query("SHOW COLUMNS FROM ".$v);
					while($rowItem=$rs->FetchRow()){
						//$tp[$rowItem['Field']]=$rowItem['Type'];
						$type=$rowItem['type'];
						$field=$rowItem['field'];

						if (preg_match("/^(.+)\((\d+),(\d+)/", $type, $query_array)) {
							$tp[$field] = $query_array[1];
						} elseif (preg_match("/^(.+)\((\d+)/", $type, $query_array)) {
							$tp[$field] = $query_array[1];
						} elseif (preg_match("/^(enum)\((.*)\)$/i", $type, $query_array)) {
							$tp[$field] = $query_array[1];
						} else {
							$tp[$field] = $type;
						}
					}
					$this->metaData = array_merge($this->metaData,$tp);

				}

			}else{
				//$this->metaData = $this->_Con->MetaColumns($this->_tableName);
				//$this->metaData = $this->_Con->reverse->tableInfo($this->_tableName,false);
				//$this->tblFields = $this->_Con->manager->listTableFields($this->_tableName);
				$rs = $this->_Con->query("SHOW COLUMNS FROM ".$this->_tableName);
				while($rowItem=$rs->fetchRow()){
					//$this->metaData[$rowItem['field']]=$rowItem['type'];

					$type=$rowItem['type'];
					$field=$rowItem['field'];

					if (preg_match("/^(.+)\((\d+),(\d+)/", $type, $query_array)) {
						$this->metaData[$field] = $query_array[1];
					} elseif (preg_match("/^(.+)\((\d+)/", $type, $query_array)) {
						$this->metaData[$field] = $query_array[1];
					} elseif (preg_match("/^(enum)\((.*)\)$/i", $type, $query_array)) {
						$this->metaData[$field] = $query_array[1];
					} else {
						$this->metaData[$field] = $type;
					}
				}


				/*
				if (preg_match("/^(.+)\((\d+),(\d+)/", $type, $query_array)) {
					$fld->type = $query_array[1];
					$fld->max_length = is_numeric($query_array[2]) ? $query_array[2] : -1;
					$fld->scale = is_numeric($query_array[3]) ? $query_array[3] : -1;
				} elseif (preg_match("/^(.+)\((\d+)/", $type, $query_array)) {
					$fld->type = $query_array[1];
					$fld->max_length = is_numeric($query_array[2]) ? $query_array[2] : -1;
				} elseif (preg_match("/^(enum)\((.*)\)$/i", $type, $query_array)) {
					$fld->type = $query_array[1];
					$arr = explode(",",$query_array[2]);
					$fld->enums = $arr;
					$zlen = max(array_map("strlen",$arr)) - 2; // PHP >= 4.0.6
					$fld->max_length = ($zlen > 0) ? $zlen : 1;
				} else {
					$fld->type = $type;
					$fld->max_length = -1;
				}
				*/


			}
			/*
			if(is_array($this->tblFields)){
				foreach ($this->tblFields as $k=>$v) {
					//print_r($v);
					$this->metaData[$k]['name']=$v['field'];
					$this->metaData[$k]['type']=$v['type'];
					//$this->metaData[$k]['type']=$this->_Con->reverse->getTableFieldDefinition($this->_tableName, $v);
				}
			}
			*/
			//print_r($this->metaData);
			return($this->metaData);

		}
		
		function Save($params) {
			if (empty($this->_primaryKey)) return false;
			if ($params[$this->_primaryKey]) {
				$this->UpdateItem($params,"$this->_primaryKey=" . $params[$this->_primaryKey]);
			}
			else {
				unset($params[$this->_primaryKey]);
				return $this->InsertItem($params);
			}
			
		}
		function Query($sql) {
			//echo($sql."<br>");
			//$rs=& $this->_Con->Execute($sql);
			return $this->_Con->query($sql);
		}
		
		function Find($fieldsList = "",$Cond = "",$Order = "",$Limit="",$groupBy='',$having='') {
			global $modlog;

			$sql = "SELECT ";
			
			if($fieldsList){

				$sql .= " $fieldsList ";

			}elseif(!empty($this->_defaultFields) && is_array($this->_defaultFields)){

				$sql .= implode(', ',$this->_defaultFields);

			}else{

				$sql .= " * ";

			}
			
			$sql .= " FROM $this->_viewName ";
			if ($Cond) $sql .= " WHERE $Cond ";
			if($groupBy) $sql .="GROUP BY $groupBy";
			if($having) $sql .=" HAVING $having";
			if ($Order) $sql .= " ORDER BY $Order ";
			if ($Limit) {
				$params = explode(",",$Limit);
				if (count($params) == 1){
					//return $this->_Con->SelectLimit($sql,$params[0]);
					if(!$this->_Con->SetLimit($params[0])){
						return false;
					}
				}else{				
					$sql .= " LIMIT $Limit"; // NOT PORTABLE
				}
			}
			//echo($sql."<br>");
			if(DEBUGMODE){
				//$modlog->Log('['.basename(__FILE__).'][L:'.__LINE__.'] '.$sql);
			}
			//$_SESSION['sql_requests']['find'][]=$sql;
			$retval = $this->_Con->query($sql) or die ($this->_Con->ErrorMsg());
			return $retval;
		}
		
		function FindInTable($fieldsList = "",$Cond = "",$Order = "",$Limit="",$groupBy='',$having='') {
			$sql = "SELECT ";
			
			if ($fieldsList) 
				$sql .= " $fieldsList ";
			else
				$sql .= " * ";
			
			$sql .= " FROM $this->_tableName ";
			if ($Cond) $sql .= " WHERE $Cond ";
			if($having) $sql .=" HAVING $having";
			if ($Order) $sql .= " ORDER BY $Order ";
			if ($Limit) $sql .= " LIMIT $Limit";

			$retval = $this->_Con->query($sql) or die ($this->_Con->ErrorMsg());
			return $retval;
		}
		
		function DeleteItem($sCond) {

			global $modlog;

			$sql = "DELETE FROM $this->_tableName ";
			if (!empty($sCond)) $sql .= " WHERE $sCond";
			//$_SESSION['sql_requests']['delete'][]=$sql;
			//echo($sql."<br>");
			if(DEBUGMODE){
				//$modlog->Log('['.basename(__FILE__).'][L:'.__LINE__.'] '.$sql);
			}
			return $this->_Con->query($sql);
		}
		
		function InsertItem($aParams) {

			global $modlog;

			if (!is_array($aParams)) return false;
			if (empty($aParams)) return false;

			$qParams = array();
			foreach($aParams as $k=>$v) {
				$qParams["`".mysql_real_escape_string($k)."`"]="'".mysql_real_escape_string($v)."'";

			}
			
			$sql = "INSERT INTO $this->_tableName (" . implode(",",array_keys($qParams)) .") values (" . implode(",",array_values($qParams)) . ")" ;
			//$_SESSION['sql_requests']['insert'][]=$sql;

			//echo($sql."<br>");
			if(DEBUGMODE){
				//$modlog->Log('['.basename(__FILE__).'][L:'.__LINE__.'] '.$sql);
			}
			


			$this->_Con->query($sql);

			$rs = $this->Query("SELECT last_insert_id() AS last_id FROM $this->_tableName");
			if ($rs) {
				$row = $rs->fetchRow();
				return $row["last_id"];
			}
			return 0;
		}
		
		function UpdateItem($aParams,$sCond) {

			global $modlog;

			if (!is_array($aParams)) return false;
			if (empty($aParams)) return false;
			
			$sql = "UPDATE $this->_tableName set ";			
			$sv = array();			
			foreach($aParams as $k=>$v) {
					$sv[] = " `".mysql_real_escape_string($k)."`='".mysql_real_escape_string($v)."'";
			}
			$sql .= implode(",",$sv);
			
			if (!empty($sCond)) $sql .= " WHERE $sCond";
			//$_SESSION['sql_requests']['update'][]=$sql;

			//echo($sql."<br>");
			if(DEBUGMODE){
				//$modlog->Log('['.basename(__FILE__).'][L:'.__LINE__.'] '.$sql);
			}


			return $this->_Con->query($sql);
		}
	}
}
?>