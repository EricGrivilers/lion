<?php
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
		
		function Find($fieldsList = "",$Cond = "",$Order = "",$Limit="",$groupBy='',$having='') {
			//echo('aaaaaaaaaa');
			$sql = "SELECT ";
			
			if ($fieldsList) 
				$sql .= " $fieldsList ";
			else
				$sql .= " * ";
			
			$sql .= " FROM $this->_viewName ";
			if ($Cond) $sql .= " WHERE $Cond ";
			if($groupBy) $sql .="GROUP BY $groupBy";
			if($having) $sql .=" HAVING $having";
			if ($Order) $sql .= " ORDER BY $Order ";
			if ($Limit) {
				$params = explode(",",$Limit);
				if (count($params) == 1)
					return $this->_Con->SelectLimit($sql,$params[0]);
				else					
					$sql .= " LIMIT $Limit"; // NOT PORTABLE
			}
			//echo($sql."<br>");
			$_SESSION['sql_requests']['find'][]=$sql;
			$retval = $this->_Con->Execute($sql) or die ($this->_Con->ErrorMsg());
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

			$retval = $this->_Con->Execute($sql) or die ($this->_Con->ErrorMsg());
			return $retval;
		}
		
		function DeleteItem($sCond) {
			$sql = "DELETE FROM $this->_tableName ";
			if (!empty($sCond)) $sql .= " WHERE $sCond";
			//echo($sql."<br>");
			$_SESSION['sql_requests']['delete'][]=$sql;
			return $this->_Con->Execute($sql);
		}
		
		function InsertItem($aParams) {
			if (!is_array($aParams)) return false;
			if (empty($aParams)) return false;

			$qParams = array();
			foreach($aParams as $k=>$v) {
				$qParams["`$k`"]="'$v'";

			}
			
			$sql = "INSERT INTO $this->_tableName (" . implode(",",array_keys($qParams)) .") values (" . implode(",",array_values($qParams)) . ")" ;
			$_SESSION['sql_requests']['insert'][]=$sql;
			//echo($sql."<br>");
			$this->_Con->Execute($sql);

			$rs = $this->Query("SELECT last_insert_id() AS last_id FROM $this->_tableName");
			if ($rs) {
				$row = $rs->fetchRow();
				return $row["last_id"];
			}
			return 0;
		}
		
		function UpdateItem($aParams,$sCond) {
			if (!is_array($aParams)) return false;
			if (empty($aParams)) return false;
			
			$sql = "UPDATE $this->_tableName set ";			
			$sv = array();			
			foreach($aParams as $k=>$v) {
					$sv[] = " `$k`='$v'";
			}
			$sql .= implode(",",$sv);
			
			if (!empty($sCond)) $sql .= " WHERE $sCond";
			$_SESSION['sql_requests']['update'][]=$sql;
			//echo($sql."<br>");
			return $this->_Con->Execute($sql);
		}
	}
?>