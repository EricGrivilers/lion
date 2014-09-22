<?php
	class DataRow {
		function DataRow($oDataModel,$id=0,$updateFields) {
			$this->_model = $oDataModel;
			$rs = $this->_model->Find("","id=$id");
			if ($row = $rs->fetchrow()) {
				foreach($row as $k => $v) {
					$this->$$k = $v;
				}
			}
			$this->_updateFields = $updateFields;
		}
		
		function Validate() {
			global $HTTP_GET_VARS,$HTTP_POST_VARS;
			$aVars = array_merge($HTTP_GET_VARS,$HTTP_POST_VARS);
		
			$this->Errors = array();
			foreach($this->_updateFields as $field) {
				if(!empty($aVars[$field])) {
					if (!$this->_validateField($field,$aVars[$field])) $this->Errors[] = $field; 
					else
						$this->_validFields[$field]=$aVars[$field];
				}
			}	
			
			return (count($this->Errors) == 0);
		}
		
		function _validateField($field,$value) {
			return true;
		}
		
		function Save() {
		}	
	}
?>