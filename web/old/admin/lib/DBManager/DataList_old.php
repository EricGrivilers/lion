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
		
		function getRows($extra_cond='' , $groupBy='' , $countGroupBy="",$excludeValues="",$having='') {
			//global $aVars;
			global $_GET,$_POST;
			$aVars = array_merge($_GET,$_POST);
			
			$aWhere = array();
			//$this->FilterValues=Array();
			//print_r($this->_filterFields);
			foreach($this->_filterFields AS $field) {
				if(is_array($excludeValues)){
					if (strlen($aVars[$field]) && !in_array(htmlentities(strip_tags($aVars[$field]),ENT_QUOTES),$excludeValues)) {
						$aVars[$field]=htmlentities(strip_tags($aVars[$field]),ENT_QUOTES);
						$this->FilterValues[$field] = $aVars[$field];
						$t = $this->_getFieldFilter($field,$aVars[$field]);
						if (!empty($t)) $aWhere[]= $t;
						//echo($aVars[$field]."<br>");
						//echo($field."<br>");
					}
				}else{
					if (strlen($aVars[$field])) {
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

			if(!empty($having)){
				if(!empty($countGroupBy)){
					$rsTotal = $this->_model->Find("COUNT(DISTINCT(".$countGroupBy.")) AS total, ".$this->_model->_havingFileds,$sWhere,'','',$countGroupBy,$having);
				}
				elseif(!empty($groupBy)) {
					$rsTotal = $this->_model->Find("COUNT(DISTINCT(".$groupBy.")) AS total, ".$this->_model->_havingFileds,$sWhere,'','',$groupBy,$having);
				}
				else {
					$rsTotal = $this->_model->Find("COUNT(*) AS total, ".$this->_model->_havingFileds,$sWhere,'','',$this->_model->_primaryKey,$having);
				}
			}else{
				if(!empty($countGroupBy)){
					$rsTotal = $this->_model->Find("COUNT(DISTINCT(".$countGroupBy.")) AS total",$sWhere,'','','',$having);
				}
				elseif(!empty($groupBy)) {
					$rsTotal = $this->_model->Find("COUNT(DISTINCT(".$groupBy.")) AS total",$sWhere,'','','',$having);
				}
				else {
					$rsTotal = $this->_model->Find("COUNT(*) AS total",$sWhere,'','','',$having);
				}
			}

			
			$row = $rsTotal->FetchRow();
			$total = $row["total"];
			$this->NavBarParams = $this->getNavBarParams($total,'',$this->PageSize,$excludeValues);
			//print_r($this->NavBarParams);
			if(!empty($this->SortField)){
				//print_r($this->SortField);
				$sortfields='';
				if(is_array($this->SortField)){
					foreach($this->SortField AS $k=>$v){
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

			$rs = $this->_model->Find("",$sWhere,$sortfields,$limit,$groupBy,$having);
			
			$aRows = $rs->GetRows();
			
			return $aRows;
		}
		
		function _getFieldFilter($field,$value) {
			$sFilter="";
			$meta = $this->_model->metaData;
			foreach($meta AS $oField) {
				//echo($oField->name.' aa '.$field.'<br />');
				if (strtolower($field)===$oField->name) {
					//echo("aaaa<br>");
					switch(strtolower($oField->type)) {
						case "nvarchar":

						case "varchar":

							if(!empty($this->SearchType[$field])){
								if($this->SearchType[$field]=='firstFixed'){
									$sFilter = "$field LIKE '$value%'";
								}elseif($this->SearchType[$field]=='lastFixed'){
									$sFilter = "$field LIKE '%$value'";
								}else{
									$sFilter = "$field LIKE '%$value%'";
								}
							}else{
								$sFilter = "$field LIKE '%$value%'";
							}
							
							break;

						case "tinytext":

							if(!empty($this->SearchType[$field])){
								if($this->SearchType[$field]=='firstFixed'){
									$sFilter = "$field LIKE '$value%'";
								}elseif($this->SearchType[$field]=='lastFixed'){
									$sFilter = "$field LIKE '%$value'";
								}else{
									$sFilter = "$field LIKE '%$value%'";
								}
							}else{
								$sFilter = "$field LIKE '%$value%'";
							}

							break;

						case "mediumtext":

							if(!empty($this->SearchType[$field])){
								if($this->SearchType[$field]=='firstFixed'){
									$sFilter = "$field LIKE '$value%'";
								}elseif($this->SearchType[$field]=='lastFixed'){
									$sFilter = "$field LIKE '%$value'";
								}else{
									$sFilter = "$field LIKE '%$value%'";
								}
							}else{
								$sFilter = "$field LIKE '%$value%'";
							}

							break;

						case "longtext":

							if(!empty($this->SearchType[$field])){
								if($this->SearchType[$field]=='firstFixed'){
									$sFilter = "$field LIKE '$value%'";
								}elseif($this->SearchType[$field]=='lastFixed'){
									$sFilter = "$field LIKE '%$value'";
								}else{
									$sFilter = "$field LIKE '%$value%'";
								}
							}else{
								$sFilter = "$field LIKE '%$value%'";
							}

							break;

						case "int":

							if(strlen($value) && ctype_digit($value)){
								$sFilter = "$field = $value";
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

		function getNavBarParams($all_items_count,$exclude_params='',$pagesize=10,$exclude_values="") {
			global $_GET,$_POST;
			$mm_vars = array_merge($_GET,$_POST);
			//global $aVars;
			//$mm_vars=$aVars;
			
			$mm_pagenum=0;	
			$mm_totalrows=0;
			$mm_excludes = array();
			$mm_excludes_values = array();
			$mm_maxrows=$pagesize;
			
			if (is_array($exclude_params)) $mm_excludes = $exclude_params;
			if (is_array($exclude_values)) $mm_excludes_values = $exclude_values;
			if ($pagesize) $mm_maxrows = $pagesize;
			//print_r($mm_excludes_values);
			//echo("<br /><br />");
			//print_r($mm_vars);
			
			if (isset($mm_vars['pagenum'])) $mm_pagenum = $mm_vars['pagenum'];
			$mm_startrow = $mm_pagenum * $mm_maxrows;
			
			if (isset($mm_vars['totalrows']) && $mm_vars['totalrows']>0) {
				$mm_totalrows = $_GET['totalrows'];
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
			$newParams4=array();

			foreach($mm_vars AS $key=>$val) {
				!get_magic_quotes_gpc() ? $v=htmlentities($val,ENT_QUOTES):$v=htmlentities(stripslashes($v),ENT_QUOTES);
				if(!in_array($key,$mm_excludes) && !in_array($v,$mm_excludes_values) && stristr($key,"pagenum")== false && stristr($key,"totalrows")==false && stristr($key,"removelastlink")==false) {
					//echo($val);
					$val2=htmlentities(strip_tags($val));
					//echo("-".$val."<br>");
					array_push($newParams,"$key=$val2");
				}
				/*if(!in_array($key,$mm_excludes) && !in_array($v,$mm_excludes_values) && stristr($key,"totalrows")==false && stristr($key,"delid")==false && stristr($key,"restoreid")==false && stristr($key,"showengine")==false && stristr($key,"removelastlink")==false) {
					$val2=htmlentities(strip_tags($val));
					array_push($newParams2,"$key=$val2");
				}*/
				if(!in_array($key,$mm_excludes) && !in_array($v,$mm_excludes_values) && stristr($key,"totalrows")==false && stristr($key,"delid")==false && stristr($key,"restoreid")==false && stristr($key,"removelastlink")==false) {
					$val2=htmlentities(strip_tags($val));
					array_push($newParams2,"$key=$val2");
				}
				if(!in_array($key,$mm_excludes) && !in_array($v,$mm_excludes_values) && stristr($key,"totalrows")==false && stristr($key,"viewby")==false && stristr($key,"removelastlink")==false) {
					$val2=htmlentities(strip_tags($val));
					array_push($newParams3,"$key=$val2");
				}
				if(!in_array($key,$mm_excludes) && !in_array($v,$mm_excludes_values) && stristr($key,"pagenum")== false && stristr($key,"totalrows")==false && stristr($key,"viewby")==false && stristr($key,"removelastlink")==false) {
					$val2=htmlentities(strip_tags($val));
					array_push($newParams4,"$key=$val2");
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

			if ($newParams4) {
				$viewby_link=implode('&',$newParams4);
			}else{
				$viewby_link="";
			}

			$mm_querystring = sprintf("&totalrows=%d%s",$mm_totalrows, ($mm_querystring?'&':'') . $mm_querystring);
			//$print_query = sprintf("&totalrows=%d%s",$mm_totalrows, ($print_query?'&':'') . $print_query);
			$_SESSION['last_datalist_query']=$_SERVER['PHP_SELF'].'?'.$print_query;
			$_SESSION['navLink']=$_SERVER['PHP_SELF'].'?'.$print_query;
		
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
					"cur_link"=>sprintf("pagenum=%d%s", $mm_pagenum, $mm_querystring),
					"viewby_link"=>sprintf("pagenum=%d%s", $mm_pagenum, $viewby_link),
					"next_link"=>sprintf("pagenum=%d%s", min($mm_totalpages, $mm_pagenum + 1), $mm_querystring),
					"last_link"=>sprintf("pagenum=%d%s", $mm_totalpages - 1 , $mm_querystring),
					"totalpages"=>$mm_totalpages);		
		}
	}
		
?>