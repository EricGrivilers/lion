<?php
require_once __root__.'/lib/Twig/lib/Twig/Autoloader.php';

class user extends element {

	function display() {
		$this->content.=$this->loginForm();

		return $this->content;

	}




	function loginForm() {

		Twig_Autoloader::register();
		$loader = new Twig_Loader_Filesystem(__root__.'lib/templates');
		$twig = new Twig_Environment($loader, array('debug' => true,'autoescape'=>false));
		$twig->addExtension(new Twig_Extension_Debug());


		if(!empty($_GET['logout'])) {
			unset($_SESSION['user']);
		}
		if(!empty($_POST['datas'])) {
			$d=$_POST['datas'];
			$db=new DB;
			$db->query="SELECT id as userId,email,login,firstname,lastname,admin FROM users WHERE (email=\"".$d['email']."\" OR login=\"".$d['email']."\") AND password=\"".$d['password']."\" AND status='' AND email!='' AND password!='' ";
			$db->setQuery();
			if($db->output) {
				$_SESSION['user']=$db->output[0];

				$out="<script>loaction.reload()</script>";
			}
			else {
				$out="<div class='alert'>".l::t("Utilisateur inconnu")."</div>";
			}
		}
		$out.="<table cellpadding='0' cellspacing='0' id='loginForm'>";
		if($_SESSION['user']) {
			$db=new DB;
			$db->query="UPDATE users SET lastVisit=NOW() WHERE id='".$_SESSION['user']['userId']."'";
			$db->setQuery();

			$out.="<tr><td class='title' colspan='3'><b>".trim($_SESSION['user']['firstname']." ".$_SESSION['user']['lastname'])."</b></td></tr>";
			$out.="<tr><td class='newAccount'><a href=\"/utilisateur\">".l::t("mon profil")."</a></td><td colspan='2' class='newAccount'><a href='/logout'>".l::t("Se deconnecter")."</a></td></tr>";

		}
		else {

			$out.="<tr><td class='title'>".l::t("MON COMPTE")."</td><td colspan='2' class='newAccount'><a href='/register'>".l::t("Créer un nouveau compte")."</a></td></tr>";
			$out.="<tr><td><input type='text' name='email' value='email' onclick=\"resetField(this)\" rel='email'/></td><td><input type='text' name='password' value='".l::t("mot de passe")."' onclick=\"resetField(this)\" onfocus=\"resetField(this)\" rel='".l::t("mot de passe")."' /></td><td style='padding:0'><a href='#' onclick='login()'><img src='/medias/login_go.gif' /></a></td></tr>";
			$out.="<tr><td> <label><input type='checkbox' name='remindme' class='checkbox' />".l::t("se souvenir de moi")."</label></td><td colspan='2'> <a href='#' onclick=\"getPassword()\">".l::t("Mot de passe oublié")." ?</a></td></tr>";

		}
		$out.="</table>";

		$out=$twig->render("user/login-form.tpl",array(
			'language'=>$_SESSION['language'],
			'get'=>$_GET,
			'user'=>$_SESSION['user']
		));
		return $out;
		return $out;


	}





	function register() {

		$errors='';
		if($_GET['confirm']) {
			$db=new DB;
			$db->query="SELECT * FROM users WHERE status='".$_GET['confirm']."'";
			$db->setQuery();

			$u=$db->output[0];

			if($u['email']!='') {
				$db=new DB;
				$db->query="UPDATE  users SET status='' WHERE status='".$_GET['confirm']."'";
				$db->resultType='none';
				$db->setQuery();
				$_SESSION['user']=$db->output[0];
				return "<div class='alert'>Vous avez bien été enregistré.</div>";

			}
			else {
				return "<div class='alert'>Utilisateur inconnu ou déja confirmé.</div>";
			}
		}
		if($_POST['datas']) {
			$d=$_POST['datas'];
//print_r($_POST['datas']);
			if($d['updating']) {
				$requiredFields=array();
			}
			else {
			$requiredFields=array('email','password','ppassword');
			}
			foreach($requiredFields as $f) {
				if(empty($d[$f])) {
					$errors=l::t("Vous n'avez pas remplis tous les champs obligatoires");
				}
			}
			if($d['email']!=$d['pemail']) {
				$errors.="<br/>".l::t("L'adresse email de confirmation de correspond pas").".";
			}
			if($d['password']!=$d['ppassword']) {
				$errors.="<br/>".l::t("Le mot de passe de confirmation de correspond pas").".";
			}
			if($errors!='') {
				$out.="<div class='alert'>".$errors."</div>";
			}
			else if($d['updating']){
				$sqlfields=array();
				$sqlvalues=array();
				foreach($d as $k=>$v) {
					if($k!='updating') {
					$sqlvalues[]=" ".trim($k)."=\"".trim(addslashes($v))."\" ";
					}

				}
				$db=new DB;
				$db->query="UPDATE users SET ".implode(",",$sqlvalues)." WHERE id='".$_SESSION['user']['userId']."'";
				$db->setQuery();
//$out.=$db->query;

			}
			else {
				$db=new DB;
				$db->query="SELECT * FROM users WHERE email='".$d['email']."'";
				$db->setQuery();
//$out.=$db->query;
				if($db->output[0]) {
					$errors="Cet utilisateur est déja enregistré";
					$out.="<div class='alert'>".$errors."</div>";
				}
				else {
					$sqlfields=array();
					$sqlvalues=array();
					foreach($d as $k=>$v) {
						if($k!='ppassword' && $k!='pemail') {
						$sqlfields[]="`".trim($k)."`";
						$sqlvalues[]='"'.trim(addslashes($v)).'"';
						}
					}
					$sqlfields[]="`date`";
					$sqlvalues[]="'".date('Ymd')."'";
					$r=rand(0,9999999);
					$sqlfields[]="`status`";
					$sqlvalues[]='"'.$r.'"';
					$db=new db;
					$db->query="INSERT INTO users (".implode(",",$sqlfields).") VALUES (".implode(",",$sqlvalues).")";
					$db->resultType='none';

					$db->setQuery();
$out.=$db->query;
					$m=l::t("Pour confirmer votre inscription, veuillez cliquer sur ce lien")."\n".__web__."register?confirm=".$r;

					mail(__postmaster__,"Inscription au site Immo-Lelion.be",$m,"From:".$d['email'] );
					$out="<p>".l::t("Votre demande a bien été enregistrée.<br/> Vous allez recevoir un email de confirmation à l'adresse")." ".$d['email'].".</p>";
					return $out;
				}
			}
		}
		if($d['updating']) {
			return "<script>window.location='/utilisateur';</script>";
		}
		$out.="<p>".l::t("Pour vous enregistrer veuillez utiliser le formulaire ci-après").". <br/>".l::t("Merci").".</p>";
		$template=new template('.');
		$template->set_file("template",__lib__."elements/user/registerForm".$_SESSION['language'].".tpl");

		if($_POST['datas']) {
			foreach($d as $k=>$v) {
				$template->set_var($k, $v);
			}
			($d['salutation']=='Mme') ? $isMme="selected" :$isM="selected";
			($d['language']=='fr') ? $isFr="checked" :$isEn="checked";
			$template->set_var('isMme', $isMme);
			$template->set_var('isM', $isM);
			$template->set_var('isFr', $isFr);
			$template->set_var('isEn', $isEn);
		}



		$template->parse("parse", "template");
		$out.=$template->p("parse",false);
		$out.="<p>* ".l::t("champs obligatoires")."</p>";
		return $out;
	}


	function myAccount() {
		if($_SESSION['user']['userId']) {
		$out.="<div id='tabs'>";
		$out.="<ul>";
		$out.="<li class='current'><a href='#items_tab'><span>".l::t("Biens sauvegardés")."</span></a></li>";
		$out.="<li><a href='#account_tab'><span>".l::t("Mon compte")."</span></a></li>";
		$out.="</ul>";
		$out.="<div id='items_tab' class='tabDiv tabAccountDiv'>";
		$out.=$this->savedItems();
		$out.="</div>";
		$out.="<div id='account_tab' class='tabDiv tabAccountDiv' style='display:none'>";
		$out.=$this->updateAccount();
		$out.="</div>";
		$out.="</div>";
		}
		else {

			$out="<p>Vous devez être enregistré pour consulter votre profil.<p>";
			$out.="<p>Veuillez remplir ce champ pour recevoir votre mot passe.</p>";
		}

		return $out;
	}


	function savedItems() {
		$db=new DB;
		$db->query="SELECT * FROM users2items LEFT JOIN items ON items.num=users2items.itemId WHERE users2items.userId='".$_SESSION['user']['userId']."' AND saved='1'";
		$db->setQuery();

		$items=$db->output;
		$out.="<table id='savedItemList' cellpadding='5'>";
		$oe=array('odd','even');
		$o=0;
		foreach($items as $i) {
			$type='vente';
			if($i['location']=='Y') {
				$type="location";
			}
			$ref=explode("/",$i['reference']);
			$out.="<tr class='".$oe[$o]."' id='i".$i['itemId']."'>";
			$out.="<td><a href=\"/".$type."/".$ref[1]."\"><img src=\"/photos/thumbs/".$i['photo'].".jpg\" width='100' /></a></td><td>".$i['descrfr']."</td><td><a onclick=\"removeFromUser('".$i['itemId']."')\"><img src=\"/medias/bin.png\" /></a></td>";
			$out.="</tr>";
			$o=!$o;
		}
		$out.="</table>";

		return $out;
	}



	function updateAccount() {



		$template=new template('.');
		$template->set_file("template",__lib__."elements/user/updateForm".$_SESSION['language'].".tpl");
		$db=new DB;
		$db->query="SELECT * FROM users WHERE id='".$_SESSION['user']['userId']."'";
		$db->setQuery();
		//echo $db->query;
		$d=$db->output[0];

		($d['salutation']=='Mme') ? $isMme="selected" :$isM="selected";
		($d['language']=='fr') ? $isFr="checked" :$isEn="checked";
		$template->set_var('isMme', $isMme);
		$template->set_var('isM', $isM);
		$template->set_var('isFr', $isFr);
		$template->set_var('isEn', $isEn);
		if($_POST['datas']) {
			$d=$_POST['datas'];
		}
		foreach($d as $k=>$v) {
			$template->set_var($k, $v);
		}
		($d['salutation']=='Mme') ? $isMme="selected" :$isM="selected";
		($d['language']=='fr') ? $isFr="checked" :$isEn="checked";
		$template->set_var('isMme', $isMme);
		$template->set_var('isM', $isM);
		$template->set_var('isFr', $isFr);
		$template->set_var('isEn', $isEn);




		$template->parse("parse", "template");
		$out.=$template->p("parse",false);



		return $out;
	}





}


?>
