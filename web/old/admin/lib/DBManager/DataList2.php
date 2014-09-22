<?php
/*
DATALIST PAGING RESULTS
copyright Cedric Billiet 2007
*/



if(!class_exists('DataList')){


	class DataList {
		var $AllowPaging = true;
		var $PageSize = 10;
		var $NoLimit = false;
		var $StartRow = 0;
		var $NavBarParams=array();
		
		var $SortField="";
		var $SortDir="ASC";
		var $SearchType="";

		var $aListenFields=array();
		var $aSelectFields=array();
		var $aExtraCond=array();
		var $aHaving=array();
		var $groupBy='';
		var $countGroupBy='';
		var $aExcludeKeys=array();
		var $aExcludeValues=array();
		
		function DataList( &$oSqlTable , $formatFields=false , $bCleanupVars=true ) {

			$this->_oModel = $oSqlTable;
			$this->_aMetaData = $this->_oModel->getMetaData();
			$this->_aHavingFields = $this->_oModel->_havingFields;
			$this->_bFormatFields = $formatFields;
			$this->_bCleanupVars = $bCleanupVars;
			
			if(!empty($this->_oModel->_defaultFields)){
				$this->_aDefaultFields=$this->_oModel->_defaultFields;
			}

		}

		function getVars($aVars, $cleanup=true){
			if($cleanup){
				if(!get_magic_quotes_gpc()){
					foreach($aVars as $k=>$v){
						if(is_array($v)){
							$tp[strip_tags($k)]=getVars($v,$cleanup);
						}else{
							$tp[strip_tags($k)]=htmlentities(strip_tags($v),ENT_QUOTES);
						}
					}
				}else{
					foreach($aVars as $k=>$v){
						if(is_array($v)){
							$tp[strip_tags($k)]=getVars($v,$cleanup);
						}else{
							$tp[strip_tags($k)]=htmlentities(strip_tags(stripslashes($v)),ENT_QUOTES);
						}
					}
				}
				$aVars=$tp;
			}
			return $aVars;
		}

		
		function getRows( $aListenFields=array() , $aSelectFields='' , $aExtraCond='' , $aHaving='' , $groupBy='' , $countGroupBy="", $aExcludeKeys="" , $aExcludeValues="" ) {

			$aVars = $this->getVars(array_merge($_GET,$_POST),true);


			//WHERE SECTION BUILD
			$aWhere = array();

			if( !empty($aListenFields) && is_array($aListenFields) ){

				foreach($aListenFields AS $field) {

					if( is_array($aExcludeKeys) || is_array($aExcludeValues) ){

						if(is_array($aExcludeKeys)){
							if ( strlen($aVars[$field]) && !array_key_exists($field,$aExcludeKeys) ) {
								
								$t = $this->_getFieldFilter($field,$aVars[$field]);
								if (!empty($t)) $aWhere[]= $t;
							}
						}

						if(is_array($aExcludeValues)){
							if ( strlen($aVars[$field]) && !in_array($aVars[$field],$aExcludeValues) ) {
								$t = $this->_getFieldFilter($field,$aVars[$field]);
								if (!empty($t)) $aWhere[]= $t;
							}
						}

					}else{

						if (strlen($aVars[$field])) {
							
							$t = $this->_getFieldFilter($field,$aVars[$field]);
							if (!empty($t)) $aWhere[]= $t;
						}

					}
				}

			}


			if(!empty($aExtraCond) && is_array($aExtraCond)){
				$sExtraCond = '('.implode(" AND ", $aExtraCond).')';
				$aWhere[]=$sExtraCond;
			}

			$sWhere = implode(" AND ", $aWhere);



			//TOTAL ROWS
			if(!empty($aHaving) && is_array($aHaving)){

				$sHaving = implode(" , ", $aHaving);

				if(!empty($countGroupBy)){
					$rsTotal = $this->_oModel->Find( "COUNT(DISTINCT(".$countGroupBy.")) AS total, ".$this->sHavingFileds , $sWhere , '' , '' , $countGroupBy , $sHaving);
				}elseif(!empty($groupBy)){
					$rsTotal = $this->_oModel->Find( "COUNT(DISTINCT(".$groupBy.")) AS total, ".$this->sHavingFileds , $sWhere , '' , '' , $groupBy , $sHaving );
				}else{
					$rsTotal = $this->_oModel->Find( "COUNT(*) AS total, ".$this->sHavingFileds , $sWhere , '' , '' , $this->_oModel->_primaryKey , $sHaving );
				}
			}else{

				$sHaving='';

				if(!empty($countGroupBy)){
					$rsTotal = $this->_oModel->Find( "COUNT(DISTINCT(".$countGroupBy.")) AS total" , $sWhere , '' , '' , '' , $sHaving );
				}elseif(!empty($groupBy)){
					$rsTotal = $this->_oModel->Find( "COUNT(DISTINCT(".$groupBy.")) AS total" , $sWhere , '' , '' , '' , $sHaving );
				}else{
					$rsTotal = $this->_oModel->Find( "COUNT(*) AS total" , $sWhere , '' , '' , '' , $sHaving );
				}
			}

			$row = $rsTotal->FetchRow();
			$sTotal = $row["total"];
			$this->NavBarParams = $this->getNavBarParams( $sTotal , $this->PageSize , $aExcludeKeys , $aExcludeValues );


			if(!empty($this->SortField)){

				$sSortfields='';
				if(is_array($this->SortField)){
					foreach($this->SortField AS $k=>$v){
						$sSortfields.=' '.$v.' '.$this->SortDir[$v].', ';
					}
					$sSortfields=substr_replace($sortfields, '', -2, 2);
				}else{
					$sSortfields = $this->SortField.' '.$this->SortDir;
				}

			}else{
				$sSortfields="";
			}


			//LIMIT
			if(!$this->NoLimit){
				$sLimit = $this->NavBarParams["startrow"] . "," . $this->PageSize;
			}else{
				$sLimit="";
			}



			//FIELDS 2 SELECT
			if( !empty($aSelectFields) && is_array($aSelectFields) ){
				$sSelectFields=implode(', ',$aSelectFields);
			}elseif( !empty($this->_aDefaultFields) && is_array($this->_aDefaultFields) ){
				$sSelectFields=implode(', ',$this->_aDefaultFields);
			}else{
				$sSelectFields='';
			}


			//SQL REQUEST
			$rs = $this->_oModel->Find( $sSelectFields , $sWhere , $sSortfields , $sLimit , $groupBy , $sHaving );
			$aRows = $rs->fetchAll();
			
			if($this->_bFormatFields){
				return $this->_formatFields($aRows);
			}else{
				return $aRows;
			}
			
		}




		function getRows2() {

			$aVars = $this->getVars(array_merge($_GET,$_POST),true);


			//WHERE SECTION BUILD
			$aWhere = array();

			if( !empty($this->aListenFields) && is_array($this->aListenFields) ){

				foreach($this->aListenFields AS $field) {

					if( is_array($this->aExcludeKeys) || is_array($this->aExcludeValues) ){

						if(is_array($this->aExcludeKeys)){
							if ( strlen($aVars[$field]) && !array_key_exists($field,$this->aExcludeKeys) ) {
								
								$t = $this->_getFieldFilter($field,$aVars[$field]);
								if (!empty($t)) $aWhere[]= $t;
							}
						}

						if(is_array($this->aExcludeValues)){
							if ( strlen($aVars[$field]) && !in_array($aVars[$field],$this->aExcludeValues) ) {
								$t = $this->_getFieldFilter($field,$aVars[$field]);
								if (!empty($t)) $aWhere[]= $t;
							}
						}

					}else{

						if (strlen($aVars[$field])) {
							
							$t = $this->_getFieldFilter($field,$aVars[$field]);
							if (!empty($t)) $aWhere[]= $t;
						}

					}
				}

			}


			if(!empty($this->aExtraCond) && is_array($this->aExtraCond)){
				$sExtraCond = '('.implode(" AND ", $this->aExtraCond).')';
				$aWhere[]=$sExtraCond;
			}

			$sWhere = implode(" AND ", $aWhere);



			//TOTAL ROWS
			if(!empty($this->aHaving) && is_array($this->aHaving)){

				$sHaving = implode(" , ", $this->aHaving);

				if(!empty($this->_aHavingFields) && is_array($this->_aHavingFields)){
					$sHavingFields=implode(', ',$this->_oModel->_havingFields);
				}else{
					return PEAR::raiseError(__FILE__.' [ '.__LINE__.'] Erreur clause having sur aliases non definis');
				}

				if(!empty($this->countGroupBy)){
					$rsTotal = $this->_oModel->Find( "COUNT(DISTINCT(".$this->countGroupBy.")) AS total, ".$sHavingFields , $sWhere , '' , '' , $this->countGroupBy , $sHaving);
				}elseif(!empty($this->groupBy)){
					$rsTotal = $this->_oModel->Find( "COUNT(DISTINCT(".$this->groupBy.")) AS total, ".$sHavingFields , $sWhere , '' , '' , $this->groupBy , $sHaving );
				}else{
					$rsTotal = $this->_oModel->Find( "COUNT(*) AS total, ".$sHavingFields , $sWhere , '' , '' , $this->_oModel->_primaryKey , $sHaving );
				}
			}else{

				$sHaving='';

				if(!empty($this->countGroupBy)){
					$rsTotal = $this->_oModel->Find( "COUNT(DISTINCT(".$this->countGroupBy.")) AS total" , $sWhere , '' , '' , '' , $sHaving );
				}elseif(!empty($this->groupBy)){
					$rsTotal = $this->_oModel->Find( "COUNT(DISTINCT(".$this->groupBy.")) AS total" , $sWhere , '' , '' , '' , $sHaving );
				}else{
					$rsTotal = $this->_oModel->Find( "COUNT(*) AS total" , $sWhere , '' , '' , '' , $sHaving );
				}
			}

			$row = $rsTotal->FetchRow();
			$sTotal = $row["total"];
			$this->NavBarParams = $this->getNavBarParams( $sTotal , $this->PageSize , $this->aExcludeKeys , $this->aExcludeValues );


			if(!empty($this->SortField)){

				$sSortfields='';
				if(is_array($this->SortField)){
					foreach($this->SortField AS $k=>$v){
						$sSortfields.=' '.$v.' '.$this->SortDir[$v].', ';
					}
					$sSortfields=substr_replace($sSortfields, '', -2, 2);
				}else{
					$sSortfields = $this->SortField.' '.$this->SortDir;
				}

			}else{
				$sSortfields="";
			}


			//LIMIT
			if(!$this->NoLimit){
				$sLimit = $this->NavBarParams["startrow"] . "," . $this->PageSize;
			}else{
				$sLimit="";
			}



			//FIELDS 2 SELECT
			if( !empty($this->aSelectFields) && is_array($this->aSelectFields) ){
				$sSelectFields=implode(', ',$this->aSelectFields);
			}elseif( !empty($this->_aDefaultFields) && is_array($this->_aDefaultFields) ){
				$sSelectFields=implode(', ',$this->_aDefaultFields);
			}else{
				$sSelectFields='';
			}


			//SQL REQUEST
			$rs = $this->_oModel->Find( $sSelectFields , $sWhere , $sSortfields , $sLimit , $this->groupBy , $sHaving );
			$aRows = $rs->fetchAll();
			
			if($this->_bFormatFields){
				return $this->_formatFields($aRows);
			}else{
				return $aRows;
			}
			
		}





		function _formatFields($aRows){

			//TODO: format numbers, date, etc...

			//print_r($this->_oModel->metaData);
			foreach($aRows as $k=>$v){
				//print_r($v);
				//echo('<br>');
				//echo($k.'_'.$v.'<br>');
			}
			return $aRows;
		}
		
		function _getFieldFilter($field,$value) {

			$sFilter="";
			$meta = $this->_aMetaData;

			foreach($meta as $k=>$v) {
				//echo($k.'-'.$v.'<br>');

				if (strtolower($field)==$k) {

					switch(strtolower($v)) {
						case "nvarchar":

						case "varchar":

							if(!empty($this->SearchType[$field])){
								if($this->SearchType[$field]=='firstFixed'){
									$sFilter = "$field LIKE '$value%'";
								}elseif($this->SearchType[$field]=='lastFixed'){
									$sFilter = "$field LIKE '%$value'";
								}elseif($this->SearchType[$field]=='exact'){
									$sFilter = "$field = '$value'";
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
								}elseif($this->SearchType[$field]=='exact'){
									$sFilter = "$field = '$value'";
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
								}elseif($this->SearchType[$field]=='exact'){
									$sFilter = "$field = '$value'";
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
								}elseif($this->SearchType[$field]=='exact'){
									$sFilter = "$field = '$value'";
								}else{
									$sFilter = "$field LIKE '%$value%'";
								}
							}else{
								$sFilter = "$field LIKE '%$value%'";
							}

							break;

						case "int":

							if( strlen($value) && (ctype_digit($value) || is_int($value)) ){
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


		function getNavBarParams( $sTotal , $sPageSize=10 ,$aExcludeKeys='' , $aExcludeValues="" ) {

			global $aVars;

			//$aVars = $this->getVars(array_merge($_GET,$_POST),true);
			
			$mm_pagenum=0;	
			$mm_totalrows=0;
			$mm_excludes = array();
			$mm_excludes_values = array();
			$mm_maxrows=$sPageSize;

			
			is_array($aExcludeKeys) ? $mm_excludes_keys = $aExcludeKeys : $mm_excludes_keys=array();

			is_array($aExcludeValues) ? $mm_excludes_values = $aExcludeValues : $mm_excludes_values=array();

			if ( isset($aVars['pagenum']) ){$mm_pagenum = $aVars['pagenum'];}

			$mm_startrow = $mm_pagenum * $mm_maxrows;
			
			if ( isset($aVars['totalrows']) && $aVars['totalrows']>0 ) {
				$mm_totalrows = $aVars['totalrows'];
			}else{
				$mm_totalrows = $sTotal;
			}	
			
	
			$mm_totalpages = @ceil($mm_totalrows/$mm_maxrows);// + ($mm_totalrows%$mm_maxrows > 0 ?1:0);//temps; //($temp <= 1 ?1:$temp - 1);	
			$mm_querystring = '';
			$aNewParams = array();
			$aNewParams2=array();
			$aNewParams3=array();
			$aNewParams4=array();


			foreach($aVars as $k=>$v) {

				if( !in_array($k,$mm_excludes_keys) && !in_array($v,$mm_excludes_values) ){

					if( stristr($k,"totalrows") === false ){
						array_push($aNewParams,"$k=$v");
					}

					if( stristr($k,"totalrows") === false && stristr($k,"pagenum") === false ){
						array_push($aNewParams2,"$k=$v");
					}

					if( stristr($k,"totalrows") === false && stristr($k,"viewby") === false ){
						array_push($aNewParams3,"$k=$v");
					}

					if( stristr($k,"totalrows") === false && stristr($k,"pagenum") === false  && stristr($k,"viewby") === false ){
						array_push($aNewParams4,"$k=$v");
					}

				}

			}


			if ($aNewParams) {
				$sQueryUnlimited=implode('&',$aNewParams);
				$sQueryUnlimited='&'.$sQueryUnlimited;
			}else{
				$sQueryUnlimited='';
			}

			if ($aNewParams2) {
				$sQueryString=implode('&',$aNewParams2);
				$sQueryString='&'.$sQueryString;
			}else{
				$sQueryString="";
			}

			if ($aNewParams3) {
				$sQueryLimited=implode('&',$aNewParams3);
				$sQueryLimited='&'.$sQueryLimited;
			}else{
				$sQueryLimited="";
			}

			if ($aNewParams4) {
				$sViewByLink=implode('&',$aNewParams4);
				$sViewByLink='&'.$sViewByLink;
			}else{
				$sViewByLink="";
			}


			$_SESSION['navLink']=$_SERVER['PHP_SELF'].'?'.$print_query;
		
			return array("querystring"=>$sQueryString,
					"sQueryString"=>$sQueryString,
					"sQueryLimited"=>$sQueryLimited,
					"sQueryUnlimited"=>$sQueryUnlimited,
					"sViewByLink"=>$sViewByLink,
					"viewby_link"=>$sViewByLink,
					"totalrows"=>$mm_totalrows,
					"startrow"=>$mm_startrow,
					"pagenum"=>$mm_pagenum,
					"first_link"=>sprintf("pagenum=%d%s",0, $sQueryString),
					"previous_link"=>sprintf("pagenum=%d%s",max(0, $mm_pagenum - 1), $sQueryString),
					"first_row"=>$mm_totalrows?$mm_startrow + 1:0,
					"last_row"=>min($mm_startrow + $mm_maxrows, $mm_totalrows),
					"cur_link"=>sprintf("pagenum=%d%s", $mm_pagenum, $sQueryString),
					"next_link"=>sprintf("pagenum=%d%s", min($mm_totalpages, $mm_pagenum + 1), $sQueryString),
					"last_link"=>sprintf("pagenum=%d%s", $mm_totalpages - 1 , $sQueryString),
					"totalpages"=>$mm_totalpages);		
		}
	}
















/*
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
		
		function DataList(&$oSqlTable,$aFilterFields,$formatFields=false) {
			$this->_model = $oSqlTable;
			$this->_filterFields = $aFilterFields;
			$this->formatFields = $formatFields;
			$this->metaData = $this->_model->getMetaData();
			
			if(empty($aFilterFields) && !empty($this->_model->_defaultFields)){
				$this->_filterFields=$this->_model->_defaultFields;
			}

		}
		
		function getRows($extra_cond='' , $groupBy='' , $countGroupBy="",$excludeParams="",$excludeValues="",$having='') {
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
						//echo($field."<br>");
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

			$row = $rsTotal->fetchRow();
			$total = $row["total"];
			//echo($total.'-'.$excludeParams.'-'.$this->PageSize.'-'.$excludeValues);
			$this->NavBarParams = $this->getNavBarParams($total,$excludeParams,$this->PageSize,$excludeValues);
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

			if(!empty($this->_filterFields)){
				$sFieldList=implode(', ',$this->_filterFields);
			}else{
				$sFieldList='';
			}
			//echo($sFieldList);

			$rs = $this->_model->Find($sFieldList,$sWhere,$sortfields,$limit,$groupBy,$having);
			
			$aRows = $rs->fetchAll();
			
			if($this->formatFields){
				return $this->_formatFields($aRows);
			}else{
				return $aRows;
			}
			
		}

		function _formatFields($aRows){
			//print_r($this->_model->metaData);
			foreach($aRows as $k=>$v){
				//print_r($v);
				//echo('<br>');
				//echo($k.'_'.$v.'<br>');
			}
			return $aRows;
		}
		
		function _getFieldFilter($field,$value) {
			$sFilter="";
			$meta = $this->_model->metaData;
			foreach($meta as $k=>$v) {
				//echo($oField->name.' aa '.$field.'<br />');
				if (strtolower($field)==$k) {
					//echo("aaaa<br>");
					switch(strtolower($v)) {
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

			//print_r($exclude_values);
			
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
				//echo($key.'<br>');
				!get_magic_quotes_gpc() ? $v=htmlentities($val,ENT_QUOTES):$v=htmlentities(stripslashes($v),ENT_QUOTES);
				if(!in_array($key,$mm_excludes) && !in_array($v,$mm_excludes_values) && stristr($key,"pagenum")== false && stristr($key,"totalrows")==false && stristr($key,"removelastlink")==false) {
					//echo($val);
					$val2=htmlentities(strip_tags($val));
					//echo("-".$val."<br>");
					array_push($newParams,"$key=$val2");
				}
				//if(!in_array($key,$mm_excludes) && !in_array($v,$mm_excludes_values) && stristr($key,"totalrows")==false && stristr($key,"delid")==false && stristr($key,"restoreid")==false && stristr($key,"showengine")==false && stristr($key,"removelastlink")==false) {
				//	$val2=htmlentities(strip_tags($val));
				//	array_push($newParams2,"$key=$val2");
				//}
				if(!in_array($key,$mm_excludes) && !in_array($v,$mm_excludes_values) && stristr($key,"totalrows")==false && stristr($key,"delid")==false && stristr($key,"restoreid")==false && stristr($key,"removelastlink")==false) {
					$val2=htmlentities(strip_tags($val));
					//echo($key.'<br>');
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

			//$mm_querystring = sprintf("&totalrows=%d%s",$mm_totalrows, ($mm_querystring?'&':'') . $mm_querystring);
			//$print_query = sprintf("&totalrows=%d%s",$mm_totalrows, ($print_query?'&':'') . $print_query);
			$mm_querystring='&'.$mm_querystring;
			//$viewby_link='&'.$viewby_link;
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
					//"viewby_link"=>sprintf("pagenum=%d%s", $mm_pagenum, $viewby_link),
					"viewby_link"=>$viewby_link,
					"next_link"=>sprintf("pagenum=%d%s", min($mm_totalpages, $mm_pagenum + 1), $mm_querystring),
					"last_link"=>sprintf("pagenum=%d%s", $mm_totalpages - 1 , $mm_querystring),
					"totalpages"=>$mm_totalpages);		
		}
	}
*/
}
		
?>