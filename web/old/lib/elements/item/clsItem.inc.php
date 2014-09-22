<?php
require_once __root__.'/lib/Twig/lib/Twig/Autoloader.php';
include_once __root__.'/lib/GoogleMap/GoogleMap.php';
include_once __root__.'/lib/GoogleMap/JSMin.php';




class item extends element {


	function display() {

		if($_GET['ref']) {
			$this->reference=$_GET['ref'];
			$this->content.=$this->detail();
		}
		else {
		$this->content.=$this->listAll();
		}

		return $this->content;
	}

	function lastViews() {
		if(empty($_SESSION['items'])) {
			$_SESSION['items']=array();
		}
		$_SESSION['items'] = array_unique($_SESSION['items']);


		/*
		$db->query="SELECT items.num as itemId,items.reference,photo2item.photo FROM users2items LEFT JOIN items ON items.num=users2items.itemId LEFT JOIN photo2item ON photo2item.item_id=users2items.itemId WHERE users2items.userId='".$_SESSION['user']['userId']."' AND items.num IS NOT NULL AND (photo2item.ranking=0 OR photo2item.ranking=1)  GROUP BY photo2item.item_id";
		$db->setQuery();
		//echo $db->query;
		$this->items=$db->output;
		*/
		//print_r($_SESSION['items']);
		$db=new DB;
	//	$db->query="SELECT items.num as itemId,items.reference,items.tphoto as photo,items.location FROM items  WHERE items.num IN (".implode(',',$_SESSION['items']).")  GROUP BY items.num";
	//	$db->query="SELECT items.num as itemId,items.reference,items.location,photo2item.photo   FROM items  LEFT JOIN photo2item ON photo2item.item_id=items.num WHERE items.num IN (".implode(',',$_SESSION['items']).")  GROUP BY items.num ORDER BY ranking";
		$db->query="SELECT items.num as itemId,items.reference,items.location,items.photo   FROM items  WHERE items.num IN (".implode(',',$_SESSION['items']).")  ";
		$db->setQuery();
		//echo $db->query;
		$this->items=$db->output;


		//$this->items=$_SESSION['items'];
		if($this->items) {
			$out="<h4>".l::t("Derniers biens vus")."</h4>";
			foreach($this->items as $item) {
			//print_r($item);
				if(!file_exists(__root__."photos/thumbs/48/".$item['photo'].".jpg")) {
					if(file_exists(__root__."photos/thumbs/".$item['photo'].".jpg")) {
						$img=new imageManip;
						$img->load(__root__."photos/thumbs/".$item['photo'].".jpg");
						$img->resize(48,48);
						$img->save(__root__."photos/thumbs/48/".$item['photo'].".jpg");
					}
					else {
						//$item['photo']='dummy.jpg';
					}
				}
				//echo $item['reference'];
				$t=explode("/",$item['reference']);
				$ref=$t[1];
				if($item['location']=='Y') {
					$type='location';
				}
				else {
					$type='vente';
				}


				//$out.="<li><a href='/".$type."/".$ref."'><img src=\"/photos/thumbs/48/".$item['photo']."\"  /></a></li>";

				$out.="<li><a href='/".$type."/".$ref."'><img src=\"/photos/thumbs/48/".$item['photo'].".jpg\"  height='48' /></a></li>";
			}
		}

		return $out;
	}

	function detail() {


		Twig_Autoloader::register();

		$loader = new Twig_Loader_Filesystem(__root__.'lib/templates');
		$twig = new Twig_Environment($loader, array('debug' => true,'autoescape'=>false));
		$twig->addExtension(new Twig_Extension_Debug());

		$this->reference=preg_replace('/030\//','',$this->reference);
		$db=new DB;
		$db->query="SELECT I.*,Q.googlecode FROM items I LEFT JOIN quartiers Q ON Q.id=I.quartier_id WHERE I.reference LIKE '%/".$this->reference."' AND I.actif='Y' LIMIT 0,1";
		$db->setQuery();


		$this->item=$db->output[0];

		//print_r($this->item);
		if($this->item['num']<=0) {
			$out="<div class='alert error'>";
			$out.= l::t("Aucun bien ne correspond à cette recherche.");
			$out.="</div>";
			return $out;
		}
		$this->saveItem(0);

		$_SESSION['items'][]=$this->item['num'];

		$db=new DB;
		$db->query="SELECT * FROM item_statsv2  WHERE itemId='".$this->item['num']."'";
		$db->setQuery();
		$s=$db->output[0];

		if($s=='') {
			$db=new DB;
			$db->query="INSERT INTO `item_statsv2` (`itemId`, `days`, `months`, `weeks`, `years`, `wdays`)
			VALUES
('".$this->item['num']."',
	'0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '0,0,0,0,0,0,0,0') ";

			$db->setQuery();
			//echo $db->query;
			$db=new DB;
		$db->query="SELECT * FROM item_statsv2  WHERE itemId='".$this->item['num']."'";
		$db->setQuery();
		$s=$db->output[0];
		}

		$currentMonth=date('n');
		$currentWeek=date('W');
		$currentDay=date('z');
		$currentWDay=date('N');
		$currentYear=date('Y')-2000;


		$days=explode(",",$s['days']);
		$months=explode(",",$s['months']);
		$weeks=explode(",",$s['weeks']);
		$wdays=explode(",",$s['wdays']);
		$years=explode(",",$s['days']);

		//echo $currentDay;
		$days[$currentDay]=$days[$currentDay]+1;
		$months[$currentMonth]=$months[$currentMonth]+1;
		$weeks[$currentWeek]=$weeks[$currentWeek]+1;
		$wdays[$currentWDay]=$wdays[$currentWDay]+1;
		$years[$currentYear]=$years[$currentYear]+1;


		$db=new DB;
		$db->query="UPDATE item_statsv2 SET  days='".implode(",",$days)."',months='".implode(",",$months)."', weeks='".implode(",",$weeks)."', years='".implode(",",$years)."', wdays='".implode(",",$wdays)."' WHERE itemId='".$this->item['num']."'";
		$db->setQuery();
		//echo $db->query;






		$out.="<input type='hidden' id='num' name='num' value='".$this->item['num']."' />";
		$out.="<div id='item'><a name='item'></a>";
		$out.="<div  class='listHeader row-fluid'>";
		$out.="<div class='resultsIntro'>";
		$out.="<span style='font-size:15px;font-weight:bold;color:#5a5a5a'>Bien à ";
		($this->item['location']=='Y')? $out.="louer":$out.="vendre";
		$out.=" / </span> ".l::t("Référence du bien")." : <b>".$this->item['reference']."</b>";
		$out.="</div>";
		$out.="<div class='nextBack'>";
		//$out.="<li><a onclick=\"$('#ref').val(0);$('#searchForm').submit();\"><img src='/medias/print.gif' alt='print' /></a></li>";
		//$out.="<li><a onclick=\"$('#ref').val('');$('#searchForm').submit();\">&lt;&lt; ".l::t("Retour à la liste")."</a></li>";

		$out.="</div>";
		$out.="</div>";


		$out.='<ul class="nav nav-tabs itemDetail">
		<li class="pull-left"><a onclick="$(\'#ref\').val(\'\');$(\'#searchForm\').submit()"><i class="icon icon-th"></i> '.l::t("Retour à la liste").'</a></li>
			  <li class=" active pull-right"><a href="#picts_tab" data-toggle="tab" ><i class="icon  icon-camera"></i> '.l::t("Photos").'</a></li>
			  <li class="pull-right"><a href="#map_tab"  data-toggle="tab"><i class="icon  icon-map-marker"></i> '.l::t("Plan").'</a></li>

			</ul>';
		$out.="<div id='tabs' >";
		/*$out.="<ul>";
		$out.="<li class='current'><a href='#picts_tab'><span>".l::t("Photos")."</span></a></li>";
		$out.="<li><a href='#map_tab'><span>".l::t("Plan")."</span></a></li>";
		$out.="</ul>";
		*/
		$out.="<div class='tab-content'>";
		$out.="<div id='picts_tab' class='tabDiv tab-pane active'>";
		//$out.=$this->flash();
		$out.=$this->slideShow();
		$out.="</div>";
		$out.="<div id='map_tab' class='tabDiv tab-pane'>";
		$out.=$this->map();
		$out.="</div>";
		$out.="</div>";
		$out.="</div>";

		$out.="<div id='infos'>";

		$out.="<div class='span6'>";
		$out.="<div style='margin-left:20px'>";
		$out.="<div class='leftBlock'>";
		$out.="<li class='yellow'>";
		($this->item['surdemande']=='Y')? $p=l::t("Prix sur demande"):$p=l::t("Prix").": ".$this->fPrice($this->item['prix']);
		($this->item['enoption']=='Y')? $p="OPTION - ".l::t("Prix").": ".$this->fPrice($this->item['prix']):$p=$p;
		($this->item['vendu']=='Y')? $p="VENDU":$p=$p;
		$out.=$p;
		$out.="</li>";
		$out.="<li class='yellow'>".l::t("Référence du bien")." : ".$this->item['reference']."</li>";


		$out.="<p>";


		($this->item['area'])? $out.=l::t("Superficie").": ".$this->item['area']." m²<br/>":$out.='';
		($this->item['rooms'])? $out.=l::t("Chambre(s)").": ".$this->item['rooms']."<br/>":$out.='';
		($this->item['bathrooms'])? $out.=l::t("Salle(s) d'eau").": ".$this->item['bathrooms']."<br/>":$out.='';
		($this->item['garages'])? $out.=l::t("Garage(s)").": ".$this->item['garages']."<br/>":$out.='';
		($this->item['garden'])? $out.=l::t("Jardin").": ".l::t($this->item['garden']):$out.='';

		$out.="</p>";


		$out.="</div>";
		$out.="<ul style='margin-top:20px'>";
		$out.="<li>&gt; <a href='#' onclick=\"contactMe('".$_GET['ref']."')\">".l::t("Me contacter à propos de ce bien")."</a></li>";
		$out.="<li>&gt; <a href='#' onclick='saveMe()'>".l::t("Sauvegarder ce bien dans mon profil")."</a></li>";
		$out.="<li>&gt; <a href='/print.php?itemId=".$this->item['num']."' target='_blank'>".l::t("Imprimer")."</a></li>";
		$out.="</ul>";

		$out.="</div>";
		$out.="</div>";
		$out.="<div class='rightBlock span6'>";
		$out.="<h3>".$this->item['locfr']."</h3>";

		$out.="<div id='text'>";
		$out.=$this->item['descrfr'];
		$out.="</div>";

$out.="<textarea id='toTranslate' style='display:none'>".$this->item['descrfr']."</textarea>";
		$out.="<div id='translation'>Translate in <select name='translateLanguageIso' id='translateSelect' ><option value=''>select language</option><option value='fr'>FRENCH</option><option value='en'>ENGLISH</option><option value='nl'>DUTCH</option><option value='es'>SPANISH</option><option value='de'>GERMAN</option><option value='it'>ITALIAN</option><option value='ru'>RUSSIAN</option><option value=''>---</option><option value='af'>AFRIKAANS</option><option value='sq'>ALBANIAN</option><option value='am'>AMHARIC</option><option value='ar'>ARABIC</option><option value='hy'>ARMENIAN</option><option value='az'>AZERBAIJANI</option><option value='eu'>BASQUE</option><option value='be'>BELARUSIAN</option><option value='bn'>BENGALI</option><option value='bh'>BIHARI</option><option value='bg'>BULGARIAN</option><option value='my'>BURMESE</option><option value='ca'>CATALAN</option><option value='chr'>CHEROKEE</option><option value='zh'>CHINESE</option><option value='zh-CN'>CHINESE_SIMPLIFIED</option><option value='zh-TW'>CHINESE_TRADITIONAL</option><option value='hr'>CROATIAN</option><option value='cs'>CZECH</option><option value='da'>DANISH</option><option value='dv'>DHIVEHI</option><option value='eo'>ESPERANTO</option><option value='et'>ESTONIAN</option><option value='tl'>FILIPINO</option><option value='fi'>FINNISH</option><option value='fr'>FRENCH</option><option value='gl'>GALICIAN</option><option value='ka'>GEORGIAN</option><option value='el'>GREEK</option><option value='gn'>GUARANI</option><option value='gu'>GUJARATI</option><option value='iw'>HEBREW</option><option value='hi'>HINDI</option><option value='hu'>HUNGARIAN</option><option value='is'>ICELANDIC</option><option value='id'>INDONESIAN</option><option value='iu'>INUKTITUT</option><option value='ga'>IRISH</option><option value='ja'>JAPANESE</option><option value='kn'>KANNADA</option><option value='kk'>KAZAKH</option><option value='km'>KHMER</option><option value='ko'>KOREAN</option><option value='ku'>KURDISH</option><option value='ky'>KYRGYZ</option><option value='lo'>LAOTHIAN</option><option value='lv'>LATVIAN</option><option value='lt'>LITHUANIAN</option><option value='mk'>MACEDONIAN</option><option value='ms'>MALAY</option><option value='ml'>MALAYALAM</option><option value='mt'>MALTESE</option><option value='mr'>MARATHI</option><option value='mn'>MONGOLIAN</option><option value='ne'>NEPALI</option><option value='no'>NORWEGIAN</option><option value='or'>ORIYA</option><option value='ps'>PASHTO</option><option value='fa'>PERSIAN</option><option value='pl'>POLISH</option><option value='pt-PT'>PORTUGUESE</option><option value='pa'>PUNJABI</option><option value='ro'>ROMANIAN</option><option value='sa'>SANSKRIT</option><option value='sr'>SERBIAN</option><option value='sd'>SINDHI</option><option value='si'>SINHALESE</option><option value='sk'>SLOVAK</option><option value='sl'>SLOVENIAN</option><option value='sw'>SWAHILI</option><option value='sv'>SWEDISH</option><option value='tg'>TAJIK</option><option value='ta'>TAMIL</option><option value='tl'>TAGALOG</option><option value='te'>TELUGU</option><option value='th'>THAI</option><option value='bo'>TIBETAN</option><option value='tr'>TURKISH</option><option value='uk'>UKRAINIAN</option><option value='ur'>URDU</option><option value='uz'>UZBEK</option><option value='ug'>UIGHUR</option><option value='vi'>VIETNAMESE</option><option value='cy'>WELSH</option><option value='yi'>YIDDISH</option><option value=''>UNKNOWN</option></select></div>";

		$out.="<br /><textarea id='origLang' style='display:none'>".$this->item['descrfr']."</textarea>";






		$out.="</div>";


		$out.="</div>";



		if($_SESSION['language']=='en') {
			$out.='<script type="text/javascript">
				$(document).ready(function() {
					$("#translateSelect").val("en");
					$("#translation").change();
				});
			</script>';
		}
		//$out.="<br class='clearfloat' />";

		$trA=explode('/',$this->item['reference']);
		($this->item['location']=='Y')? $flink.="location/".$trA[1]:$flink.="vente/".$trA[1];

		$out.="</div>";
		/*$out.="<div id='itemLinks'>";

		$out.="<li><a href='#' onclick=\"contactMe('".$_GET['ref']."')\">".l::t("Me contacter à propos de ce bien")."</a></li>";
		$out.="<li><a href='#' onclick='saveMe()'>".l::t("Sauvegarder ce bien dans mon profil")."</a></li>";
		$out.="<li><a href='/print.php?itemId=".$this->item['num']."' target='_blank'>".l::t("Imprimer")."</a></li>";
		$out.="</div>";

*/
		$out.="<div id='fblink'>";

		$out.='<div class="row-fluid">
		<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style pull-right">

<a class="addthis_button_tweet"></a>
<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>

</div>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-50fd480167721f19"></script>
<!-- AddThis Button END -->
</div>';
	//	$out.="<li class='fb'><a href=\"http://www.facebook.com/share.php?u=http://www.immo-lelion.be/".$flink."\" target='_blank'><img src='/medias/facebook.png' height='30'/> Partager sur Facebook</a>";

		$out.="</div>";

/*

		$db=new DB;
		$db->query="SELECT items.photo as phot,photo2item.photo as photos FROM items LEFT JOIN photo2item ON photo2item.item_id=items.num WHERE num='".$this->item['num']."' ORDER BY ranking";
		$db->setQuery();
		//echo $db->query;
		$picts=$db->output;

*/

		//$out.=$twig->render("item/detail.tpl",array('language'=>$_SESSION['language'],'get'=>$_GET,'item'=>$this->item,'picts'=>$picts));
		return $out;
	}

	function listAll() {

		Twig_Autoloader::register();

		$loader = new Twig_Loader_Filesystem(__root__.'lib/templates');
		$twig = new Twig_Environment($loader, array('debug' => true,'autoescape'=>false));
		$twig->addExtension(new Twig_Extension_Debug());

		($_GET['searchType']=='sale') ? $where="WHERE location!='Y' " :$where="WHERE location='Y' ";
		($_GET['searchType']=='sale') ? $page="Vente" :$page="Location";

		if(empty($this->listStart)) $this->listStart=0;
		if($_GET['searchStart']>0) {$this->listStart=$_GET['searchStart'];}
		if(empty($this->orderBy)) $this->orderBy=$_GET['orderBy'];
		if($_GET['orderBy']=='') {$this->orderBy='price_asc'; $_GET['orderBy']='price_asc'; }



		switch($this->orderBy) {
			default:
				$ob=" prix ASC ";
			break;

			case 'price_desc':
				$ob=" prix DESC ";
			break;

			case 'city':
				$ob=" locfr ASC ";
			break;

			case 'new':
				$ob=" `update` DESC,`datein` DESC ";
			break;
		}

		if($_GET['limitBy']) {
			$this->limitBy=$_GET['limitBy'];
		}
		else {
			$this->limitBy=12;
		}


		if($_GET['keywords']) {
			$where.=" AND (locfr LIKE \"%".$_GET['keywords']."%\" OR descrfr LIKE \"%".$_GET['keywords']."%\" OR reference LIKE \"%".$_GET['keywords']."%\" ) ";
		}

		if($_GET['address']) {
			$MAP_OBJECT = new GoogleMapAPI(); $MAP_OBJECT->_minify_js = isset($_REQUEST["min"])?FALSE:TRUE;
			if($geocodes = $MAP_OBJECT->getGeoCode($_GET['address']."+belgium")) {
			//	print_r($geocodes);
				if($_GET['rayon']<=1) {$_GET['rayon']=0.5;}
				$sql="SELECT * FROM items WHERE (
POW( ( 69.1 * ( Longitude - -74.008680 ) * cos( 40.711676 / 57.3 ) ) , 2 ) + POW( ( 69.1 * ( Latitude - 40.711676 ) ) , 2 )
) < ( 1 *1 );";

$where.=" AND (POW( ( 69.1 * ( Lng - ".$geocodes['lon']." ) * cos( ".$geocodes['lat']." / 57.3 ) ) , 2 ) + POW( ( 69.1 * ( Lat - ".$geocodes['lat']." ) ) , 2 )
) < ( 1 *".$_GET['rayon']." )  ";


			}

		}

		if($_GET['type']) {
			$db=new DB;
			$db->query="SELECT id,type_".$_SESSION['language']." as label FROM  `type`";
			$db->setQuery();
			//$this->types=$db->output;
			//print_r($db->output);
			$this->types=array();
			foreach($db->output as $type) {
				$this->types[$type['label']]=$type['id'];
			}


			$tType=array();
			foreach($_GET['type'] as $t) {
				$tType[]=" type='".$this->types[$t]."'";
			}

			$where.=" AND (".implode(' OR ',$tType).") ";
		}
		if(!empty($_GET['area'])) {
			$tA=array();
			foreach($_GET['area'] as $k=>$v) {
				$tA[]=$v;
			}
			$where.=" AND ( zone IN (".implode(',',$tA).") ) ";
		}


		if($_GET['sale']) {
			$tPs=array();
			foreach($_GET['sale'] as $p) {
				$tPs[]=" prix ".$p;
			}
			$where.=" AND (".implode(' OR ',$tPs).") ";
		}
		/*print_r($tP);

		if(!empty($_GET['sale'])) {
			switch($_GET['sale']) {
				case '750000':
					$where.=" AND prix<='750000' ";
				break;
				case '1500000':
					$where.=" AND prix>='1500000' ";
				break;

				default:
					$where.=" AND prix BETWEEN 750000 AND 1500000 ";
				break;
			}
		}
		*/
		if($_GET['rent']) {
			$tPr=array();
			foreach($_GET['rent'] as $p) {
				$tPr[]=" prix ".$p;
			}
			$where.=" AND (".implode(' OR ',$tPr).") ";
		}

		/*
		if(!empty($_GET['rent'])) {
			switch($_GET['rent']) {
				case '750000':
					$where.=" AND prix<='750000' ";
				break;
				case '1500000':
					$where.=" AND prix>='1500000' ";
				break;

				default:
					$where.=" AND prix BETWEEN 750000 AND 1500000 ";
				break;
			}
		}
		*/

		if($_GET['quartier']) {
			$where.=" AND quartier_id='".$_GET['quartier']."' ";
		}

		$db=new DB;
		$q="SELECT count(num) as m FROM items  ";
		$q.=$where;
		$q.=" AND actif='Y' ";
		$db->query=$q;
		$db->setQuery();
		//echo $db->query;
		$this->maxItems=$db->output[0]['m'];

		//SELECT * FROM items LEFT JOIN photo2item ON photo2item.itemId=items.num WHERE location!='Y' AND actif='Y' GROUP BY items.num ORDER BY prix ASC LIMIT 0,9

		$db=new DB;
		$q="SELECT items.*,photo2item.photo AS mPhoto FROM items  LEFT JOIN photo2item ON photo2item.item_id=items.num ";
		//$q="SELECT * FROM items  ";
		$q.=$where;
		$q.=" AND actif='Y'  GROUP BY items.num ";
		$q.=" ORDER BY  ".$ob.",ranking ASC ";
		$q.=" LIMIT ".$this->listStart.",".$this->limitBy;

		$db->query=$q;
		$db->setQuery();
	//	echo $db->query;

		if($db->output) {
	$this->items=$db->output;
}
else {
	$this->items=array();
}

		$out.="<input type='hidden' id='listStart' value='".$this->listStart."' />";
		$out.="<input type='hidden' id='maxItems' value='".$this->maxItems."' />";

		$out.="<div class='listHeader row-fluid'>";
		$out.="<div class='resultsIntro'>";
		$out.="<span style='font-size:15px;font-weight:bold;color:#5a5a5a'>".l::t("Résultats")." /</span> ".$this->maxItems." ".l::t("bien(s) correspond(ent) à votre recherche");
		$out.="</div>";
	/*	$out.="<div class='nextBack'>";
		$out.=$this->nextBack();
		$out.="</div>";
		*/
		$out.="</div>";


		$out.="<div class='row-fluid'>";
		$out.="<div id='listFilter' >";
		$out.="<table style='width:100%'><tr><td>";
			//$out.="<div class='span8'>";
			$out.="<b>".l::t("Filtrer par")."</b> ";
			$out.="<select name='orderBy'>";
			$out.="<option value='price_asc' ".$this->isSelected('orderBy','price_asc').">".l::t("Prix croissant")."</option>";
			$out.="<option value='price_desc' ".$this->isSelected('orderBy','price_desc').">".l::t("Prix décroissant")."</option>";
			$out.="<option value='city' ".$this->isSelected('orderBy','city').">".l::t("Communes")."</option>";
			$out.="<option value='new' ".$this->isSelected('orderBy','new').">".l::t("Nouveautés")."</option>";
			$out.="</select>";
			//$out.="</div>";
			//$out.="<div class='span4' style='text-align:right'>";
			$out.="</td><td style='text-align:right'>";
			$out.="<b>".l::t("Biens par page")."</b>";
			$out.="<select name='limitBy' id='limitBy' style='width:70px'>";
			$out.="<option value='12' ".$this->isSelected('limitBy','12').">12</option>";
			$out.="<option value='24' ".$this->isSelected('limitBy','24').">24</option>";
			$out.="<option value='48' ".$this->isSelected('limitBy','48').">48</option>";
			//$out.="<option value='72' ".$this->isSelected('limitBy','72').">72</option>";
			$out.="</select>";
			$out.="</td></tr></table>";
			//$out.="</div>";
/*
		$out.="<input type='radio' name='orderBy' value='price_asc' ".$this->isChecked('orderBy','price_asc')." />".l::t("Prix croissant");
		$out.="<input type='radio' name='orderBy' value='price_desc' ".$this->isChecked('orderBy','price_desc')."/>".l::t("Prix décroissant");
		$out.="<input type='radio' name='orderBy' value='city' ".$this->isChecked('orderBy','city')."/>".l::t("Communes");
		$out.="<input type='radio' name='orderBy' value='new' ".$this->isChecked('orderBy','new')."/>".l::t("Nouveautés");
		*/
		$out.="</div>";
		$out.="</div>";


		$out.="<div id='items' >";
		$out.="<div class='row-fluid'>";
		require_once(__lib__."core/clsImageManip.inc.php");
		$m=0;
		foreach($this->items as $item) {
			$t=explode('/',$item['reference']);

			$out.="<div class='span3 itemThumb' rel='".$t[1]."'>";
			$delay = mktime(0, 0, 0, date("m"), date("d")-15, date("Y"));
			if($item['vendu']=='Y' && $item['location']=='Y') {
				$out.="<div class='options osold'>".l::t("LOUE")."</div>";
			}
			else if($item['vendu']=='Y') {
				$out.="<div class='options osold'>".l::t("VENDU")."</div>";
			}
			else if($item['enoption']=='Y') {
				$out.="<div class='options ooption'>OPTION</div>";
			}
			else if(str_replace('-','',$item['datein'])>=date("Ymd", $delay )) {
				$out.="<div class='options onew'>".l::t("NOUVEAUTE")."</div>";
			}
			else if(str_replace('-','',$item['update'])>=date("Ymd", $delay )) {
				$out.="<div class='options oupdated'>".l::t("MIS A JOUR")."</div>";
			}
			$out.="<div class='pict'>";
			if(!file_exists(__root__."photos/thumbs/".$item['photo'].".jpg")) {
				$i=new imageManip;
				//echo "http://www.immo-lelion.be/photos/big/".$item['photo'].".jpg";
				$i->load("http://www.immo-lelion.be/photos/big/".$item['photo'].".jpg");
					$i->resizeToFit(185,138);
					$i->save(__root__."photos/thumbs/".$item['photo'].".jpg");

				//	$item['photo']="_dummy";

			}
			//$out.="<img src='http://www.immo-lelion.be/photos/thumbs/".$item['photo'].".jpg' class='pictThumb' />";
			//echo $item['mPhoto']."<br/>";
			//$item['photo']=$item['mPhoto'];
			//echo $item['photo'];
			if($item['photo']=='') {
				$db=new DB;
				$db->query="SELECT photo FROM photo2item WHERE item_id='".$item['num']."' AND photo!='' ORDER BY ranking LIMIT 0,1";
				$db->setQuery();
				//echo $db->query;
				$item['photo']=$db->output[0];
				//print_r($photos);

				$tf=__root__."photos/big/".$db->output[0]['photo'];
				//	echo $tf;
					$path_parts = pathinfo($tf);

				if($db->output[0]['photo']!='') {
					$db=new DB;

					$db->query="UPDATE items SET photo='".$path_parts['filename']."' WHERE num='".$item['num']."' ";
					$db->setQuery();
				//	echo $db->query;
					$item['photo']=$path_parts['filename'];
				}
			}

			if(file_exists(__root__."/photos/big/".$item['mPhoto']) && !file_exists(__root__."/photos/thumbs/".$item['mPhoto']) && $item['mPhoto']!='') {
				$i=new imageManip;

				$i->load("http://www.immo-lelion.be/photos/big/".$item['mphoto'].".jpg");
					$i->resizeToFit(185,138);
					$i->save(__root__."photos/thumbs/".$item['mPhoto'].".jpg");

			}


			$l="/photos/thumbs/".$item['photo'].".jpg";
			if(file_exists(__root__."photos/thumbs/".$item['mPhoto']) && $item['mPhoto']!='') {
				$l="/photos/thumbs/".$item['mPhoto'];
			}
			//$l="/photos/thumbs/".$item['mPhoto'];
			$out.="<img src='".$l."' class='pictThumb'  />";
			/*
			if(file_exists(__root__."/photos/thumbs/".$item['photo'].".JPG")) {
				$out.="<img src='/photos/thumbs/".$item['photo'].".JPG' class='pictThumb' />";
			}
			else {
				$out.="<img src='/photos/thumbs/".$item['photo'].".jpg' class='pictThumb' />";
			}
			*/

			$out.="</div>";
			$out.="<div class='more'><div class='descro2'>".$item['descrfr']."</div><div class='moreLink' ><a href='#'>".l::t("plus d'infos")."</a></div></div>";
			$out.="<div class='descro'>";
			$l=explode(" ",$item['locfr']);
			$l[1]=$item['locfr'];
			$out.="<span class='title'>".$l[1]."</span><br/>";
			//($item['surdemande']=='Y')? $out.="":$out.=$this->fPrice($item['prix']);
			($item['surdemande']=='Y')? $p=l::t("Prix sur demande"):$p=l::t("Prix").": ".$this->fPrice($item['prix']);
			($item['enoption']=='Y')? $p=l::t("OPTION")." - ".l::t("Prix").": ".$this->fPrice($item['prix']):$p=$p;
			($item['vendu']=='Y')? $p=l::t("VENDU"):$p=$p;
			$out.=$p;
			$out.="<br/>";
			$out.=$item['rooms']." ".l::t("chambre(s)");
			$out.="</div>";

			$out.="</div>";
			//echo $m;

			if($m%4==3) {
				$out.="</div><div class='row-fluid'>";
				//$m=0;
			}
			$m++;
		}
		$out.="</div>";
		$out.="</div>";

		$out.="<div class='listHeader row-fluid'>";
		$out.="<div class='resultsIntro'>";
		//$out.="<span style='font-size:15px;font-weight:bold;color:#5a5a5a'>Résultats /</span> ".$this->maxItems." bien(s) correspon(ent) à votre recherche";
		$out.="</div>";
		$out.="<div class='nextBack'>";
		$out.=$this->nextBack();
		$out.="</div>";
		$out.="</div>";
		/*
echo "<pre>";
print_r($this->items);
echo "</pre>pre>";
*/
		//$out.=$twig->render("item/list.tpl",array('language'=>$_SESSION['language'],'get'=>$_GET,'items'=>$this->items));
		//return $out;
		return $out;

	}


	function nextBack() {
		if($this->listStart>0) {
			$out.="<li><a href='#' onclick=\"goTo('first')\"/><img src='/medias/go-first.gif'/></a></li>";
			$out.="<li><a href='#' onclick=\"goTo('prev')\"/><img src='/medias/go-previous.gif'/></a></li>";
		}
		$out.="<li>Page ";
		$out.=floor($this->listStart/$this->limitBy)+1;
		$out.="/";
		$out.=floor($this->maxItems/$this->limitBy)+1;
		$out.="</li>";

		if($this->listStart<=$this->maxItems-$this->limitBy) {
			$out.="<li><a href='#' onclick=\"goTo('next')\"/><img src='/medias/go-next.gif'/></a></li>";
			$out.="<li><a href='#' onclick=\"goTo('last')\"/><img src='/medias/go-last.gif'/></a></li>";
		}
			$nbPages=floor($this->maxItems/$this->limitBy+1);
			$currentPage=floor($this->listStart/$this->limitBy+1);

			/*echo $nbPages."---";
			echo $currentPage;
			*/

			if($nbPages<=1) {
				return "";
			}

			$out='<div class="pagination">
  			<ul><li ';
			if($currentPage<=1) {
				$out.=' class="disabled" ';
			}
			$out.='><a href="#" onclick="goTo(\'prev\')" >Prev</a></li>';

			if($nbPages>10) {
				if($currentPage<6) {
					for($i=1;$i<7;$i++) {
						$out.='<li ';
					  	if($i==$currentPage) {
					  		$out.=" class='active' ";
					  	}

					  	$out.='><a href="#" onclick="goTo('.(($i-1)*$_GET['limitBy']).')">'.$i.'</a></li>';
					}
					$out.="<li><a href='#'>...</a></li>";
				}
				else if($currentPage>$nbPages-7){
					$out.="<li><a href='#'>...</a></li>";
					for($i=$nbPages-7;$i<=$nbPages;$i++) {
						$out.='<li ';
					  	if($i==$currentPage) {
					  		$out.=" class='active' ";
					  	}

					  	$out.='><a href="#" onclick="goTo('.(($i-1)*$_GET['limitBy']).')">'.$i.'</a></li>';
					}
				}
				else {
					$out.="<li><a href='#'>...</a></li>";
					for($i=$currentPage-3;$i<=$currentPage+3;$i++) {
						$out.='<li ';
					  	if($i==$currentPage) {
					  		$out.=" class='active' ";
					  	}

					  	$out.='><a href="#" onclick="goTo('.(($i-1)*$_GET['limitBy']).')">'.$i.'</a></li>';
					}
					$out.="<li><a href='#'>...</a></li>";
				}
			}
			else {
				for($i=1;$i<=$nbPages;$i++) {
			  	$out.='<li ';
			  	if($i==$currentPage) {
			  		$out.=" class='active' ";
			  	}

			  	$out.='><a href="#" onclick="goTo('.(($i-1)*$_GET['limitBy']).')">'.$i.'</a></li>';
			  }
			}

			$out.='
			    <li ';
			if($currentPage>=$nbPages) {
				$out.=' class="disabled" ';
			}
			$out.='><a href="#" onclick="goTo(\'next\')">Next</a></li>
			  </ul>
			</div>';

		return $out;
	}

	function slideShow() {
		$out.="<div id='galleria' >";
		//$out.="<ul>";
		$db=new DB;
		$db->query="SELECT items.photo as phot,photo2item.photo as photos FROM items LEFT JOIN photo2item ON photo2item.item_id=items.num WHERE num='".$this->item['num']."' ORDER BY ranking";
		$db->setQuery();
		//echo $db->query;
		$picts=$db->output;
		$allPicts=array();
		if(file_exists(__root__."photos/big/".$picts[0]['phot'].".jpg")) {
				//$out.="<img src=\"/photos/big/".$picts[0]['phot'].".jpg\"  />";
				$allPicts[$picts[0]['phot'].".jpg"]=$picts[0]['phot'].".jpg";
		}

		foreach($picts as $p) {
			//$out.="<li><img src=\"http://www.immo-lelion.be/photos/big/".$p['photo']."\" alt=\"\" /></li>";
			if(file_exists(__root__."photos/big/".$p['photos']) && !is_dir(__root__."photos/big/".$p['photos'])) {

			$allPicts[$p['photos']]=$p['photos'];
			}
			/*else if(file_exists(__root__."photos/big/".$p['photo'].".jpg")) {
			$out.="<img src=\"/photos/big/".$p['photo'].".jpg\"  />";
			}
			*/

		}
		foreach($allPicts as $p) {
			$out.="<img src=\"/photos/big/".$p."\"  />";
		}
		//$out.="</ul>";
		$out.="</div>";

		$out.="<script type='text/javascript'>
		Galleria.loadTheme('/medias/classic/galleria.classic.js');
	$('#galleria').galleria({
		height:523

	});






	</script>";
		return $out;
	}
	/*
	function flash() {
		$w=680;
		$h=523;
		$out="<script language=\"javascript\">
					if (AC_FL_RunContent == 0) {
						alert(\"This page requires AC_RunActiveContent.js.\");
					} else {
						AC_FL_RunContent(
							'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0',
							'width', '".$w."',
							'height', '".$h."',
							'src', 'slideshow',
							'quality', 'high',
							'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
							'align', 'middle',
							'play', 'true',
							'loop', 'true',
							'scale', 'showall',
							'wmode', 'window',
							'devicefont', 'false',
							'id', 'slideshow',
							'bgcolor', '#ffffff',
							'name', 'slideshow',
							'menu', 'true',
							'allowFullScreen', 'false',
							'allowScriptAccess','always',
							'movie', '/slideshow',
							'salign', '',
							'FlashVars','xmlfile=/getImagesXML.php?itemId=".$this->item['num']."'
							);
					}
				</script>
				<noscript>
					<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0\" width=\"".$w."\" height=\"".$h."\" id=\"slideshow\" align=\"middle\">
						<param name=\"allowScriptAccess\" value=\"always\" />
						<param name=\"allowFullScreen\" value=\"false\" />
						<param name=\"movie\" value=\"/slideshow.swf\" />
						<param name=\"quality\" value=\"high\" />
						<param name=\"bgcolor\" value=\"#ffffff\" />
						<param name=\"FlashVars\" value=\"xmlfile=/getImagesXML.php?itemId=".$this->item['num']."\" />
						<embed FlashVars=\"xmlfile=/getImagesXML.php?itemId=".$this->item['num']."\" src=\"/slideshow.swf\" quality=\"high\" bgcolor=\"#ffffff\" width=\"".$w."\" height=\"".$h."\" name=\"slideshow\" align=\"middle\" allowScriptAccess=\"always\" allowFullScreen=\"false\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
					</object>
				</noscript>";

		return $out;

	}
	*/

	function map() {
		$w=680;
		$h=523;
		$out=$this->item['googlecode'];
		$out.="<script>$(document).ready(function() {
			$('#map_tab iframe').css('width','".$w."px');
			$('#map_tab iframe').css('height','".$h."px');
			$('#map_tab a').remove();
		});</script>";
		//$out="<iframe width=\"".$w."\" scrolling=\"no\" height=\"".$h."\" frameborder=\"0\" src=\"http://maps.google.be/?ie=UTF8&amp;s=AARTsJqzARj-Z8VnW5pkPMLMmZbqrJcYpw&amp;ll=50.79834,4.407406&amp;spn=0.03255,0.051498&amp;z=13&amp;output=embed\" marginwidth=\"0\" marginheight=\"0\"></iframe>";
		/*$out="<div id=\"map_canvas\" style=\"width:".$w."px; height:".$h."px\"></div>";
		$out.='<script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?sensor=false">
    </script>
    <script type="text/javascript">
		$(document).ready(function() {
			initialize();
		});
      function initialize() {
        var mapOptions = {
          center: new google.maps.LatLng(50.79834, 4.407406),
          zoom: 8,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map_canvas"),
            mapOptions);
      }
    </script>';
		 * */


		return $out;
	}



	function isChecked($var,$val) {
		if($_GET[$var]==$val ) {
			return ' checked' ;
		}
	}

	function isSelected($var,$val) {
		if($_GET[$var]==$val ) {
			return ' selected' ;
		}
	}



	function fPrice($p){
		$p=number_format($p, 0, '', '.');

		return $p." &euro;";
	}



	function saveItem($acc=1) {
		if($_POST['itemId']!='') {
			$itemId=$_POST['itemId'];
		}
		else {
			$itemId=$this->item['num'];
		}
		if($_SESSION['user']['userId']) {
		$db=new DB;
		$db->query="INSERT INTO users2items (userId,itemId,date,counter,view,saved) VALUES ('".$_SESSION['user']['userId']."','".$itemId."',NOW(),'1','1','".$acc."') ON DUPLICATE KEY UPDATE counter=counter+1,view=view+1 ";
		if($acc==1) {
			$db->query.=",saved='".$acc."' ";
		}
		$db->resultType='none';
		$db->setQuery();
		//echo $db->query;
		$out=l::t("Bien sauvegardé");
		}
		else {
		$out=l::t("Vous devez être enregistré pour sauvegarder un bien");
		}
		return $out;
	}

	function removeFromUser() {
		$db=new DB;
		$db->query="UPDATE users2items SET saved='0' WHERE itemId='".$_POST['itemId']."' AND userId='".$_SESSION['user']['userId']."' ";
		$db->setQuery();
		$db->resultType='none';
		echo $db->query;
		$out=l::t("Bien supprimé");
		return $out;
	}

	/* admin */




}



?>
