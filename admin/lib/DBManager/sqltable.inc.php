<?php
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
			if (empty($viewName)) {
				$this->_viewName = $tableName;
			}
			else {
				$this->_viewName = $viewName;
			}

			if(is_array($involvedTables)){

				$this->metaData = array();

				$this->_involvedTables = $involvedTables;

				foreach($involvedTables as $k=>$v){

					$tp = $this->_Con->MetaColumns($v);
					if(!is_array($tp)){$tp=array();}
					$this->metaData = array_merge($this->metaData,$tp);

				}

			}else{
				$this->metaData = $this->_Con->MetaColumns($this->_tableName);
			}
			//print_r($this->metaData);


			
			
			if (!empty($primaryKey)) $this->_primaryKey = $primaryKey;
			
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
			return $this->_Con->Execute($sql);
		}
		
		function Find($fieldsList = "",$Cond = "",$Order = "",$Limit="",$groupBy='') {
			$sql = "select ";
			
			if ($fieldsList) 
				$sql .= " $fieldsList ";
			else
				$sql .= " * ";
			
			$sql .= " from $this->_viewName ";
			if ($Cond) $sql .= " where $Cond ";
			if($groupBy) $sql .="group by $groupBy";
			if ($Order) $sql .= " Order by $Order ";
			if ($Limit) {
				$params = explode(",",$Limit);
				if (count($params) == 1)
					return $this->_Con->SelectLimit($sql,$params[0]);
				else					
					$sql .= " limit $Limit"; // NOT PORTABLE
			}
			if($_SERVER['REMOTE_ADDR']=='217.64.254.235'){
				//echo($sql."<br>");
			}
			echo($sql."<br>");
			$retval = $this->_Con->Execute($sql) or die ($this->_Con->ErrorMsg());
			return $retval;
		}
		
		function FindInTable($fieldsList = "",$Cond = "",$Order = "",$Limit="") {
			$sql = "select ";
			
			if ($fieldsList) 
				$sql .= " $fieldsList ";
			else
				$sql .= " * ";
			
			$sql .= " from $this->_tableName ";
			if ($Cond) $sql .= " where $Cond ";
			if ($Order) $sql .= " Order by $Order ";
			if ($Limit) $sql .= " limit $Limit";

			$retval = $this->_Con->Execute($sql) or die ($this->_Con->ErrorMsg());
			return $retval;
		}
		
		function DeleteItem($sCond) {
			$sql = "delete from $this->_tableName ";
			if (!empty($sCond)) $sql .= " where $sCond";
			//echo($sql."<br>");
			return $this->_Con->Execute($sql);
		}
		
		function InsertItem($aParams) {
			if (!is_array($aParams)) return false;
			if (empty($aParams)) return false;

			$qParams = array();
			foreach($aParams as $k=>$v) {
				$qParams["`$k`"]="'$v'";

			}
			
			$sql = "insert into $this->_tableName (" . implode(",",array_keys($qParams)) .") values (" . implode(",",array_values($qParams)) . ")" ;
			//echo($sql."<br>");
			$this->_Con->Execute($sql);

			$rs = $this->Query("select last_insert_id() as last_id from $this->_tableName");
			if ($rs) {
				$row = $rs->fetchRow();
				return $row["last_id"];
			}
			return 0;
		}
		
		function UpdateItem($aParams,$sCond) {
			if (!is_array($aParams)) return false;
			if (empty($aParams)) return false;
			
			$sql = "update $this->_tableName set ";			
			$sv = array();			
			foreach($aParams as $k=>$v) {
					$sv[] = " `$k`='$v'";
			}
			$sql .= implode(",",$sv);
			
			if (!empty($sCond)) $sql .= " where $sCond";
			//die($sql."<br>");
			return $this->_Con->Execute($sql);
		}
	}
}
?>