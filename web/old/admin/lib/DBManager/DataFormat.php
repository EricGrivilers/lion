<?php
	
	/** DataFormat
	*   Output formatter 
	*/
	class DataFormat {
		function DataFormat($aRows,$aKeys) {
			global $logger;
			$this->_model = $oDataModel;
			$rs = $this->_model->Find("","id=$id");
			if ($row = $rs->FetchRow()) {
				foreach($row as $k => $v) {
					$this->$$k = $v;
				}
			}
			//$logger->debug_var("aUpdateFields",$aUpdateFields,1);
			$this->_updateFields = $aUpdateFields;
			if (is_array($aValidationsRules) && count($aValidationRules))
				$this->_validationRules = $aValidationRules;
		}
		
		function Validate() {
			global $_GET,$_POST,$logger;
			$aVars = array_merge($_GET,$_POST);
		
			$this->Errors = array();
			foreach($this->_updateFields as $field) {
				if(!empty($aVars[$field])) {
					if (!$this->_validateField($field,$aVars[$field])) $this->Errors[] = $field; 
					else
						$this->_validFields[$field]=$aVars[$field];
				}
			}	
			//$logger->debug_var("_validFields",$this->_validFields,1);
			return (count($this->Errors) == 0);
		}
		
		function _validateField($field,$value) {
			//TODO handle validations rules
			return true;
		}
		
		function Save() {
			global $logger;
			//$logger->debug_var("_validFields",$this->_validFields,1);
			if (isset($this->_validFields)) {				
				return $this->_model->Save($this->_validFields);
			}
		}	
	}
?>