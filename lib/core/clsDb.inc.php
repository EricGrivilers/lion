<?php

require_once('config.inc.php');
$db=new db;
	
	
$Connect = mysql_connect($db->DBhost, $db->DBuser, $db->DBpass) or die("Can not connect");
@mysql_select_db("$db->DBName") or die ("Can not access the database");

mysql_query("SET NAMES 'utf8'");

class db {
	
	var $webRoot=__webRoot__;
	var $datasFolder=__datasFolder__;
	var $absoluteRoot=__absoluteRoot__;
	var $DBuser=__DBuser__;
    var $DBpass=__DBpass__;
    var $DBhost=__DBhost__;
    var $DBName=__DBName__;
    var $DBprefix=__DBprefix__;
	
	var $query;
	var $result;
	var $output;
    var $connexion;
	var $resulType;
	
	
	//old version
	var $q;
	
	function parseQuery() {
		if(preg_match("/#__/",$this->query)) {
			$this->query=preg_replace('/#__/',$this->DBprefix.'_',$this->query);
		}
	}
	
	function setQuery() {
		$this->parseQuery();
		//echo $this->query;
		if($this->result=mysql_query($this->query)) {
		//echo 'true';
			$this->sendResult();
		}
	}
	
	
	function sendResult() {
		switch($this->resultType) {
			
			case 'xml':
				$out="<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
				$out.="<datas>";
				//if(mysql_num_rows($this->result)>0) {
					while($row=mysql_fetch_assoc($this->result)) {
						$out.="<data>";
							foreach($row as $field=>$value) {
								$out.="<".$field.">".$value."</".$field.">";
							}
						$out.="</data>";
					}
				//}
				//$out.="<data><debug>".$this->query."</debug></data>";
				$out.="</datas>";
			break;
			
			default:
			case 'array':
			//$_SESSION['isarray']='1';
				$out=array();
				//if(mysql_num_rows($this->result)>0) {
					while($row=mysql_fetch_assoc($this->result)) {
						$out[]=$row;
					}
				//}
			break;
			
			case 'json':
				$json=array();
				while($row=mysql_fetch_assoc($this->result)) {
						$json[]=$row;
				}
				$out=json_encode($json);
			break;
			
			case 'none':
				$out='';
			break;
		
		}
		$this->output=$out;
	}
	
	
	
	function autoFill($table,$postValues,$escapeFields=array()) {
		$db=new db;
		$db->query="SHOW COLUMNS FROM ".$table;
		$db->resultType='array';
		$db->setQuery();
		$tColumns=$db->output;
		$columns=array();
		foreach($tColumns as $k=>$fields) {
			$columns[]=$fields['Field'];
		}
		
		$inserts=array();
		$values=array();
		$updates=array();
		foreach($postValues as $k=>$field) {
			if(in_array($k,$columns)) {
				$inserts[]=$k;
				if($field=="NOW()") {
					$values[]=$field;
				}
				else {
					$values[]="\"".$field."\"";
				}
				if(!in_array($k,$escapeFields)) {
					$updates[]=$k."=\"".$field."\" ";
				}
			
			}
		}
		$q="insert into ".$table." (".implode(",",$inserts).") VALUES (".implode(",",$values).") ON DUPLICATE KEY UPDATE ".implode(",",$updates);
		return $q;
		
	}
	
	
//needed to old version

	function sqlquery() {
		if($this->q!='') {
			$this->query=$this->q;	
		}
		$this->setQuery();
		return $this->output;
	}

}



?>