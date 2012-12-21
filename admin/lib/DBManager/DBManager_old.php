<?Php
require_once("adodb/adodb.inc.php");
require_once("DBManager/SQLTable.inc.php");

class DBManager {
	var $models_dir="models";

	function DBManager($dbhost,$dbuser,$dbpassword,$dbname,$dbdriver="mysql") {
		$this->_Con = ADONewConnection($dbdriver);
		$this->_Con->SetFetchMode(ADODB_FETCH_ASSOC);

		$this->_Con->Connect($dbhost,$dbuser,$dbpassword,$dbname);

		$this->NewsLetters =& new SQLTable($this->_Con,"dm_newsletters","","id");
		$this->Mailings =& new SQLTable($this->_Con,"dm_mailings","","id");
		$this->Subscribers =& new SQLTable($this->_Con,"dm_subscribers","","id");
		$this->Groups =& new SQLTable($this->_Con,"dm_groups","","id");
		$this->Mailings =& new SQLTable($this->_Con,"dm_mailings","","id");
		$this->Languages =& new SQLTable($this->_Con,"dm_languages");
		$this->Countries =& new SQLTable($this->_Con,"dm_countries");
		$this->Subs2Groups = &new SQLTable($this->_Con,"dm_subscribers2groups");
		$this->Task = &new SQLTable($this->_Con,"dm_task","","id");
		$this->Links = &new SQLTable($this->_Con,"dm_links","","id");
		$this->Moduleconfig = &new SQLTable($this->_Con,"dm_moduleconfig","","id");
		$this->Users = &new SQLTable($this->_Con,"dm_users","","id");
	}
	
	function init_models() {
		if (empty($this->models_dir)) return false;
		if($hdle = opendir($this->models_dir)) {
			while($file = readdir($hdle)) {
				$filepath = "$this->models_dir/$file";
				if (is_file($filepath)) {
					$tab = pathinfo($filepath);
					if ($tab["extension"]=="php") {
						//echo($filepath.'<br>');
						include_once($filepath);
						$model = substr($tab["basename"],0 ,strlen($tab["basename"]) - (strlen($tab["extension"]) + 1));
												
						$oModel = &new $model($this->_Con);
	
						$this->$model = &new $model($this->_Con);
					}
					
				}
			}
			closedir($hdle);
		}
		else
			die("can't find any model");
		
	}
	
	function Query($sql) {
		return $this->_Con->Execute(sql);
	}
	
	function ErrorMsg() {
		return $this->_Con->ErrorMsg();
	}
}
?>