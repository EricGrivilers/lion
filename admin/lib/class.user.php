<?php
require_once("DBManager/SQLTable2.inc.php");


class User extends SQLTable {
	var $db = null; // PEAR::DB pointer
	var $failed = false; // failed login attempt
	var $date; // current date GMT
	var $id = 0; // the current user's id
	var $userTable;

	function User(&$Con,$userTable) {
		parent::SQLTable($Con,$userTable);
		$this->date = $GLOBALS['date'];

		if ($_SESSION['logged']) {
			$this->_checkSession();
		} elseif ( isset($_COOKIE['mtwebLogin']) ) {
			$this->_checkRemembered($_COOKIE['mtwebLogin']);
		}
	}

	function _checkLogin($login, $passe, $remember) {
		global $newsDB;

		$dte=time()-3600;
		$dte=date("Y-m-d H:i:s",$dte);

		$rs=$this->Find("","login='".$login."' AND password='".$passe."'");

		if($rowItem=$rs->FetchRow()){
			$this->_setSession($rowItem, $remember);
			return true;
		} else {
			$this->failed = true;
			$this->_logout();
			return false;
		}
	}


	function _setSession(&$values, $remember, $init = true) {

		if ($remember) {
			$this->updateCookie($values['cookie'], true);
		}

		if ($init) {
			$session = session_id();
			$ip = $_SERVER['REMOTE_ADDR'];
			unset($params);
			$params['session']=$session;
			$params['ip']=$ip;
			$rs=$this->UpdateItem($params,"id=".$values['id']);
		}

		$_SESSION['userData'] = $values;
		$_SESSION['userData']['password']='';
		$_SESSION['userId'] = $values['id'];
		$_SESSION['login'] = htmlspecialchars($values['login']);
		$_SESSION['cookie'] = $values['cookie'];
		$_SESSION['logged'] = true;
		define( "USER_OK" , true );
	}

	function updateCookie($cookie, $save) {
	   $_SESSION['cookie'] = $cookie;
	   if ($save) {
		  $cookie = serialize(array($_SESSION['login'], $cookie) );
		  setcookie('mtwebLogin', $cookie, time() + 31104000,'/');
	   }
	}

	function _checkRemembered($cookie) {
		list($login, $cookie) = @unserialize($cookie);
		if (!$login || !$cookie) return;

		$rs=$this->Find("","login='".$login."' AND cookie='".$cookie."'");
		if($rowItem=$rs->FetchRow()){
			$result=$rowItem;
			$this->_setSession($result, true,false);
		}
	}

	function _checkSession() {
		$login = $_SESSION['login'];
		$cookie = $_SESSION['cookie'];
		$session = session_id();
		$ip = $_SERVER['REMOTE_ADDR'];
		$rs=$this->Find("","login='".mysql_real_escape_string($login)."' AND cookie='".mysql_real_escape_string($cookie)."' AND session='".mysql_real_escape_string($session)."' AND ip='".mysql_real_escape_string($ip)."'");
		if($rowItem=$rs->FetchRow()){
			$result=$rowItem;
			$this->_setSession($result, false, false);
			return true;
		} else {
			$this->_logout();
			return false;
		}
	}
	function _logout() {
		$_SESSION['logged'] = false;
		$_SESSION['userId'] = 0;
		$_SESSION['login'] = '';
		$_SESSION['cookie'] = 0;
		$_SESSION['remember'] = false;
		define( "USER_OK" , true );
	}
}
?>