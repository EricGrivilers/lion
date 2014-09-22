<?php

class contact extends element {


	function display() {

		return $this->content;
	}


	function contactForm() {
		$errors='';
		if($_SESSION['user']['email']!='') {
			$d=array();
		}
		if($_POST['datas']) {
			$d=$_POST['datas'];
			//print_r($_POST['datas']);

			if($this->estimation==true) {
				$requiredFields=array('lastname','firstname','email','tel','itemCity');
				$subject="Demande d'estimation";
			}
			else {
				$requiredFields=array('lastname','firstname','email','tel');
				$subject="Demande d'infos";
			}
			foreach($requiredFields as $f) {
				if(empty($d[$f])) {
					$errors=l::t("Vous n'avez pas remplis tous les champs obligatoires");
				}
			}
			if($errors!='') {
				$out.="<div class='alert'>".$errors."</div>";
			}
			else {
				$m="<table>";
				foreach($d as $k=>$f) {
					$m.="<tr><td>".l::t($k)."</td><td>".$f."</td></tr>";

				}

				$m.="</table>";
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

				// Additional headers

				$headers .= 'From: '.$d['email'] . "\r\n";
//				$headers .= 'Bcc: eric@caravanemedia.com' . "\r\n";

				//mail(__cc__,$subject,$m,"From:".$d['email'] );
				if(mail(__postmaster__,$subject,$m,$headers )) {
					$out="<p>".l::t("Votre demande a bien été enregistrée.<br/> Nous reviendrons vers vous dans les plus brefs délais").".</p>";

				}
				else {
					$out="<div class='alert'><p>".l::t("Une erreur est survenue lors de l'envoi. Veuillez réessayer. Merci.").".</p></div>";
				}

				return $out;
			}
		}


		$template=new template('.');
		if($this->estimation==true) {
			$template->set_file("template",__lib__."elements/contact/estimation_".$_SESSION['language'].".tpl");
			$out.="<p>".l::t("Pour toute demande d'estimation de votre bien, veuillez utiliser le formulaire ci-après. <br/>Merci").".</p>";
		}
		else {
			$template->set_file("template",__lib__."elements/contact/mainForm_".$_SESSION['language'].".tpl");
			$out.="<p>".l::t("Pour tout contact, demande d'informations ou de rendez-vous veuillez utiliser le formulaire ci-après. <br/>Merci").".</p>";
		}


		if($_SESSION['user']['email']!='') {
			$db=new DB;
			$db->query="SELECT * FROM users WHERE id='".$_SESSION['user']['userId']."'";
			$db->setQuery();
			$u=$db->output[0];
			foreach($u as $k=>$v) {
				$d[$k]=$v;
			}
		}

		if($d) {
			foreach($d as $k=>$v) {
				$template->set_var($k, $v);
			}
			($d['salutation']=='Mme') ? $isMme="selected" :$isM="selected";
			($d['language']=='fr' || $_SESSION['language']=='fr') ? $isFr="checked" :$isEn="checked";
			$template->set_var('isMme', $isMme);
			$template->set_var('isM', $isM);
			$template->set_var('isFr', $isFr);
			$template->set_var('isEn', $isEn);
		}


		if($_GET['ref']) {
			($_GET['searchType']=='sale') ? $f="vente" :$f="location";
			$template->set_var('refLink', __web__.$f."/".$_GET['ref']."\n");

		}

		$template->parse("parse", "template");
		$out.=$template->p("parse",false);
		$out.="<p>* champs obligatoires</p>";
		return $out;
	}

}//end class

?>
