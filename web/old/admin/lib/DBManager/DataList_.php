<?php
	class DataList {
		var $AllowPaging = true;
		var $PageSize = 10;
		var $NoLimit = false;
		var $StartRow = 0;
		var $NavBarParams=array();
		
		var $SortField="";
		var $SortDir="ASC";
		var $SearchType="";

		var $FilterValues=array();
		
		function DataList(&$oSqlTable,$aFilterFields) {
			$this->_model = $oSqlTable;
			$this->_filterFields = $aFilterFields;

		}
		
		function getRows($extra_cond='' , $groupBy='' , $countGroupBy="",$excludeValues="") {
			//global $aVars;
			global $HTTP_GET_VARS,$HTTP_POST_VARS;
			$aVars = array_merge($HTTP_GET_VARS,$HTTP_POST_VARS);
			
			$aWhere = array();
			//$this->FilterValues=Array();
			//print_r($this->_filterFields);
			foreach($this->_filterFields as $field) {
				if(is_array($excludeValues)){
					if (!empty($aVars[$field]) && !in_array(htmlentities(strip_tags($aVars[$field]),ENT_QUOTES),$excludeValues)) {
						$aVars[$field]=htmlentities(strip_tags($aVars[$field]),ENT_QUOTES);
						$this->FilterValues[$field] = $aVars[$field];
						$t = $this->_getFieldFilter($field,$aVars[$field]);
						if (!empty($t)) $aWhere[]= $t;
						//echo($aVars[$field]."<br>");
					}
				}else{
					if (!empty($aVars[$field])) {
						$aVars[$field]=htmlentities(strip_tags($aVars[$field]),ENT_QUOTES);
						$this->FilterValues[$field] = $aVars[$field];
						$t = $this->_getFieldFilter($field,$aVars[$field]);
						if (!empty($t)) $aWhere[]= $t;
						//echo($aVars[$field]."<br>");
					}
				}
				
			}
			//print_r($this->FilterValues);

			if(!empty($extra_cond)) $aWhere[]= $extra_cond;

			//print_r($aWhere);
			$sWhere = implode(" and ", $aWhere);
			//echo($sWhere);


			if(!empty($countGroupBy)){
				$rsTotal = $this->_model->Find("count(distinct(".$countGroupBy.")) as total",$sWhere);
			}
			elseif(!empty($groupBy)) {
				$rsTotal = $this->_model->Find("count(distinct(".$groupBy.")) as total",$sWhere);
			}
			else {
			$rsTotal = $this->_model->Find("count(*) as total",$sWhere);
			}

			
			$row = $rsTotal->FetchRow();
			$total = $row["total"];

			$this->NavBarParams = $this->getNavBarParams($total,'',$this->PageSize);
			//print_r($this->NavBarParams);
			if(!empty($this->SortField)){
				//print_r($this->SortField);
				$sortfields='';
				if(is_array($this->SortField)){
					foreach($this->SortField as $k=>$v){
						$sortfields.=' '.$v.' '.$this->SortDir[$v].', ';
					}
					$sortfields=substr_replace($sortfields, '', -2, 2);
				}else{
					$sortfields = $this->SortField.' '.$this->SortDir;
				}
			}else{
				$sortfields="";
			}


			if(!$this->NoLimit){
				$limit = $this->NavBarParams["startrow"] . "," . $this->PageSize;
			}else{
				$limit="";
			}

			$rs = $this->_model->Find("",$sWhere,$sortfields,$limit,$groupBy);
			
			$aRows = $rs->GetRows();
			
			return $aRows;
		}
		
		function _getFieldFilter($field,$value) {
			$sFilter="";
			$meta = $this->_model->metaData;
			foreach($meta as $oField) {
				if (strtolower($field)===$oField->name) {
					switch(strtolower($oField->type)) {
						case "nvarchar":

						case "varchar":

							if(!empty($this->SearchType[$field])){
								if($this->SearchType[$field]=='firstFixed'){
									$sFilter = "$field like '$value%'";
								}elseif($this->SearchType[$field]=='lastFixed'){
									$sFilter = "$field like '%$value'";
								}else{
									$sFilter = "$field like '%$value%'";
								}
							}else{
								$sFilter = "$field like '%$value%'";
							}
							
							break;

						case "tinytext":

							if(!empty($this->SearchType[$field])){
								if($this->SearchType[$field]=='firstFixed'){
									$sFilter = "$field like '$value%'";
								}elseif($this->SearchType[$field]=='lastFixed'){
									$sFilter = "$field like '%$value'";
								}else{
									$sFilter = "$field like '%$value%'";
								}
							}else{
								$sFilter = "$field like '%$value%'";
							}

							break;

						case "mediumtext":

							if(!empty($this->SearchType[$field])){
								if($this->SearchType[$field]=='firstFixed'){
									$sFilter = "$field like '$value%'";
								}elseif($this->SearchType[$field]=='lastFixed'){
									$sFilter = "$field like '%$value'";
								}else{
									$sFilter = "$field like '%$value%'";
								}
							}else{
								$sFilter = "$field like '%$value%'";
							}

							break;

						case "longtext":

							if(!empty($this->SearchType[$field])){
								if($this->SearchType[$field]=='firstFixed'){
									$sFilter = "$field like '$value%'";
								}elseif($this->SearchType[$field]=='lastFixed'){
									$sFilter = "$field like '%$value'";
								}else{
									$sFilter = "$field like '%$value%'";
								}
							}else{
								$sFilter = "$field like '%$value%'";
							}

							break;

						default:

							$sFilter = "$field = '$value'"; 
							break;

					}
				}
			}

			return $sFilter;
		}

		function getNavBarParams($all_items_count,$exclude_params='',$pagesize=10) {
			global $HTTP_GET_VARS,$HTTP_POST_VARS;
			$mm_vars = array_merge($HTTP_GET_VARS,$HTTP_POST_VARS);
			//global $aVars;
			//$mm_vars=$aVars;
			
			$mm_pagenum=0;	
			$mm_totalrows=0;
			$mm_excludes = array();
			$mm_maxrows=$pagesize;
			
			if (is_array($exclude_params)) $mm_excludes = $exclude_params;
			if ($pagesize) $mm_maxrows = $pagesize;
			
			if (isset($mm_vars['pagenum'])) $mm_pagenum = $mm_vars['pagenum'];
			$mm_startrow = $mm_pagenum * $mm_maxrows;
			
			if (isset($mm_vars['totalrows']) && $mm_vars['totalrows']>0) {
				$mm_totalrows = $HTTP_GET_VARS['totalrows'];
			}
			else {
				$mm_totalrows = $all_items_count;
			}	
			
			//$temp = @ceil($mm_totalrows/$mm_maxrows);	
			$mm_totalpages = @ceil($mm_totalrows/$mm_maxrows);// + ($mm_totalrows%$mm_maxrows > 0 ?1:0);//temps; //($temp <= 1 ?1:$temp - 1);	
			//die($mm_totalpages);
			$mm_querystring = '';
			$newParams = array();
			$newParams2=array();
			$newParams3=array();

			foreach($mm_vars as $key=>$val) {
				if(!in_array($key,$mm_excludes) && stristr($key,"pagenum")== false && stristr($key,"totalrows")==false) {
					//echo($val);
					$val2=htmlentities(strip_tags($val));
					//echo("-".$val."<br>");
					array_push($newParams,"$key=$val2");
				}
				if(!in_array($key,$mm_excludes) && stristr($key,"totalrows")==false && stristr($key,"delid")==false && stristr($key,"restoreid")==false && stristr($key,"showengine")==false) {
					$val2=htmlentities(strip_tags($val));
					array_push($newParams2,"$key=$val2");
				}
				if(!in_array($key,$mm_excludes) && stristr($key,"totalrows")==false && stristr($key,"viewby")==false) {
					$val2=htmlentities(strip_tags($val));
					array_push($newParams3,"$key=$val2");
				}
			}
			
			if ($newParams) {
				$mm_querystring=implode('&',$newParams);
			}
			if ($newParams2) {
				$print_query=implode('&',$newParams2);
			}else{
				$print_query='';
			}
			//echo($print_query);
			if ($newParams3) {
				$print_query2=implode('&',$newParams3);
			}else{
				$print_query2="";
			}

			$mm_querystring = sprintf("&totalrows=%d%s",$mm_totalrows, ($mm_querystring?'&':'') . $mm_querystring);
			//$print_query = sprintf("&totalrows=%d%s",$mm_totalrows, ($print_query?'&':'') . $print_query);
			$_SESSION['last_datalist_query']=$print_query;
		
			return array("querystring"=>$mm_querystring,
					"print_query"=>$print_query,
					"print_query2"=>$print_query2,
					"totalrows"=>$mm_totalrows,
					"startrow"=>$mm_startrow,
					"pagenum"=>$mm_pagenum,
					"first_link"=>sprintf("pagenum=%d%s",0, $mm_querystring),
					"previous_link"=>sprintf("pagenum=%d%s",max(0, $mm_pagenum - 1), $mm_querystring),
					"first_row"=>$mm_totalrows?$mm_startrow + 1:0,
					"last_row"=>min($mm_startrow + $mm_maxrows, $mm_totalrows),
					"next_link"=>sprintf("pagenum=%d%s", min($mm_totalpages, $mm_pagenum + 1), $mm_querystring),
					"last_link"=>sprintf("pagenum=%d%s", $mm_totalpages - 1 , $mm_querystring),
					"totalpages"=>$mm_totalpages);		
		}
	}
		
?>