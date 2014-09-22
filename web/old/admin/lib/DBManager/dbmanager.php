<?Php
require_once("adodb/adodb.inc.php");
require("sqltable.inc.php");

if(!class_exists('DBManager')){
	class DBManager {
		var $models_dir="models";

		function DBManager($dbhost,$dbuser,$dbpassword,$dbname,$dbdriver="mysql") {
			$this->_Con = ADONewConnection($dbdriver);
			$this->_Con->SetFetchMode(ADODB_FETCH_ASSOC);

			$this->_Con->Connect($dbhost,$dbuser,$dbpassword,$dbname);

			//TABLES SIMPLES
			$this->TblCarts = & new SQLTable($this->_Con,"easycommerce_carts","","id");
			$this->TblCartsArchive = & new SQLTable($this->_Con,"easycommerce_carts_archive","","id");
			$this->TblProduits = & new SQLTable($this->_Con,"easycommerce_produits","","id");
			$this->TblProduits2Langues = & new SQLTable($this->_Con,"easycommerce_produits_2_langues","","id");
			$this->TblUsers = & new SQLTable($this->_Con,"modx_web_users","","id");
			$this->TblUserAttributes = & new SQLTable($this->_Con,"modx_web_user_attributes","","id");

			//VUES COMPLEXES
			$this->produits = & new SQLTable($this->_Con,"easycommerce_produits","easycommerce_produits LEFT JOIN easycommerce_produits_2_langues ON easycommerce_produits.id=easycommerce_produits_2_langues.produit_id","easycommerce_produits.id");

			$this->users = & new SQLTable($this->_Con,"modx_web_users","modx_web_users LEFT JOIN modx_web_user_attributes ON modx_web_users.id=modx_web_user_attributes.internalKey","modx_web_users.id");

			$this->carts = & new SQLTable($this->_Con,"easycommerce_carts","easycommerce_carts LEFT JOIN modx_web_users ON easycommerce_carts.user_id=modx_web_users.id LEFT JOIN modx_web_user_attributes ON easycommerce_carts.user_id=modx_web_user_attributes.internalKey LEFT JOIN easycommerce_produits ON easycommerce_carts.produit_id=easycommerce_produits.id LEFT JOIN easycommerce_produits_2_langues ON easycommerce_produits.id=easycommerce_produits_2_langues.produit_id","easycommerce_carts.id");
		}

		
		function Query($sql) {
			return $this->_Con->Execute(sql);
		}
		
		function ErrorMsg() {
			return $this->_Con->ErrorMsg();
		}
	}
}
?>