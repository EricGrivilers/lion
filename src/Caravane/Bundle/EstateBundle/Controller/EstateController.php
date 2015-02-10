<?php

namespace Caravane\Bundle\EstateBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Caravane\Bundle\EstateBundle\Entity\Estate;
use Caravane\Bundle\EstateBundle\Entity\Photo;
use Caravane\Bundle\EstateBundle\Entity\Document;
use Caravane\Bundle\EstateBundle\Entity\Location;
use Caravane\Bundle\EstateBundle\Entity\Zone;
use Caravane\Bundle\EstateBundle\Entity\Area;
use Caravane\Bundle\EstateBundle\Entity\Category;
use Caravane\Bundle\EstateBundle\Entity\Price;
use Caravane\Bundle\EstateBundle\Entity\UserEstate;
use Caravane\Bundle\EstateBundle\Form\EstateType;
use Caravane\Bundle\EstateBundle\Form\SearchType;

use Ob\HighchartsBundle\Highcharts\Highchart;
/**
 * Estate controller.
 *
 */
class EstateController extends Controller
{
	private $categoryMaison;
	private $categoryAppartement;
	private $categoryAutre;
	private $zone1;
	private $zone2;
	private $zone3;
	private $zone4;

	private $communes = array(
		"0"=>array("Bxl 19 Communes",""),
		"00"=>array("BXl Centre",""),
		"000"=>array("Quartier Sablon","50.840467, 4.355691"),
		"0001"=>array("Quartier Dansaert","50.849559, 4.347024"),
		"001"=>array("Quartier Grand Place","50.846788, 4.352351"),
		"01"=>array("Bxl Sud",""),
		"011"=>array("Uccle",""),
		"0111"=>array("Molière","50.815486, 4.358386"),
		"0111B"=>array("Uccle -Floride","50.806253, 4.366341"),
		"0112"=>array("Uccle -Vert Chasseur","50.802235, 4.374309"),
		"0112B"=>array("Uccle - Vivier d'Oie","50.795758, 4.372973"),
		"0113"=>array("Uccle -Observatoire","50.798023, 4.358440"),
		"0114"=>array("Uccle -Fort Jaco","50.790311, 4.374226"),
		"0115"=>array("Uccle -Prince d'Orange","50.778783, 4.374577"),
		"0116"=>array("Uccle -Wolvendael","50.800156, 4.345926"),
		"0117"=>array("Uccle -Saint-Job","50.794227, 4.364771"),
		"0019"=>array("Uccle -Parc Brugmann","50.810040, 4.350664"),
		"0119B"=>array("Uccle -Lycée Français","50.788479, 4.345563"),
		"0119C"=>array("Uccle -Maison Communale","50.803591, 4.333964"),
		"012"=>array("Ixelles",""),
		"0121"=>array("Ixelles - Jardin du Roy -Abbaye","50.820217, 4.370587"),
		"0121A"=>array("Roosevelt","50.805934, 4.385855"),
		"0121B"=>array("Place Brugmann","50.817282, 4.354584"),
		"0122"=>array("Louise Stéphanie","50.828203, 4.362819"),
		"0123"=>array("Ixelles - Etangs","50.823727, 4.373223"),
		"0124"=>array("Ixelles - Quartier ULB","50.812051, 4.385271"),
		"0126"=>array("Place du Chatelain","50.824415, 4.360192"),
		"013"=>array("Forest","50.819480, 4.334367"),
		"0131"=>array("Forest - Molière","50.815947, 4.344097"),
		"014"=>array("Saint-Gilles","50.824765, 4.345661"),
		"01B"=>array("Rhode-Saint-Genèse","50.746684, 4.361737"),
		"01B1"=>array("Rhode-St-Genèse - Espinette Centrale","50.748391, 4.390887"),
		"01B2"=>array("Rhode-St-Genèse - Ancien Golf","50.740908, 4.400689"),
		"01H"=>array("Waterloo","50.710127, 4.401829"),
		"01H1"=>array("Waterloo - Faubourg","50.729211, 4.403640"),
		"020"=>array("Auderghem","50.815678, 4.428411"),
		"020A"=>array("Quartier Institutions Européennes","50.843700, 4.382306"),
		"021"=>array("Etterbeek","50.832914, 4.387832"),
		"022"=>array("Woluwé-Saint-Lambert","50.841859, 4.430483"),
		"023"=>array("Woluwé-Saint-Pierre","50.826376, 4.459541"),
		"0231"=>array("WSP - Chant d'Oiseau","50.827414, 4.418471"),
		"0232"=>array("WSP - Val Duchesse","50.823202, 4.436467"),
		"0233"=>array("Stockel-Place Dumon","50.840570, 4.465425"),
		"024"=>array("Schaerbeek","50.856389, 4.392840"),
		"026"=>array("Watermael-Boisfort","50.797989, 4.417686"),
		"027"=>array("Cinqauntenaire/Montgomery","50.838258, 4.402959"),
		"103"=>array("Lasne","50.687517, 4.483315"),
		"1033"=>array("Lasne - Ohain","50.699941, 4.467007"),
		"1035"=>array("Lasne - Plancenoit","50.662851, 4.429671"),
		"1037"=>array("Lasne - Maransart","50.658743, 4.466964"),
		"1039"=>array("Lasne - Couture","50.674492, 4.472758"),
		"111"=>array("Rixensart","50.712609, 4.533019"),
		"113"=>array("Rixensart - Bourgeois","50.706712, 4.510832"),
		"114"=>array("Rixensart - Genval","50.721497, 4.492950")
	);

	public function importAction(Request $request) {

		$force=$request->query->get('force');
		$p=$request->query->get('p');
		$t=$request->query->get('t');
		//$t=p,V,L
		$em = $this->getDoctrine()->getManager();
		$this->setup();

		$rs = curl_init();
		curl_setopt($rs,CURLOPT_HEADER,0);
		curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($rs,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($rs,CURLOPT_FRESH_CONNECT,true);




		curl_setopt($rs,CURLOPT_URL,'http://www.esimmo.com/Virtual/lelion/resultats.php?OxySeleOffr='.$t.'&OxySeleBiensParPage=10&OxyPage='.$p );
		$xml = curl_exec($rs);
		$estates = new \SimpleXMLElement($xml);
		$n=$this->import($estates,$t,$force,null,$p);

		if($t=='p') {
			$estatesGroup=$estates;
			foreach($estatesGroup as $es) {
				foreach($es->COMPS as $estates) {
					$n=$this->import($estates,$t,$force, $es->CLAS,$p);
				}
			}
		}


/*

		curl_setopt($rs,CURLOPT_URL,'http://www.esimmo.com/Virtual/lelion/carte.php?OxySeleOffr='.$t.'&OxySeleBiensParPage=10&OxyPage='.$p );
		$xml = curl_exec($rs);
		$estates = new \SimpleXMLElement($xml);
		$this->setGeo($estates,$t,$force);

		if($t=='p') {
			$estatesGroup=$estates;
			foreach($estatesGroup as $es) {
				foreach($es->COMPS as $estates) {
					$this->setGeo($estates,"new",$force);
				}
			}
		}
*/


		if($n>0) {
			$response= "<script>document.location='import?t=".$t."&force=".$force."&p=".($p+1)."'</script>";
			return new response($response);

		}


		return $this->redirect($this->generateUrl('caravane_estate_backend_estate'));
	}


	private function setup() {
		$em = $this->getDoctrine()->getManager();

		if(!file_exists(__DIR__.'/../../../../../web/pdfs')) {
			mkdir(__DIR__.'/../../../../../web/pdfs', 0755);
		}

		if(!$price=$em->getRepository('CaravaneEstateBundle:Price')->find(1)) {
			$price=new Price();
			$price->setPrice(750000);
			$price->setType('sale');
			$em->persist($price);
			$em->flush();
		}

		if(!$price=$em->getRepository('CaravaneEstateBundle:Price')->find(2)) {
			$price=new Price();
			$price->setPrice(1500000);
			$price->setType('sale');
			$em->persist($price);
			$em->flush();
		}

		if(!$price=$em->getRepository('CaravaneEstateBundle:Price')->find(3)) {
			$price=new Price();
			$price->setPrice(2000);
			$price->setType('rent');
			$em->persist($price);
			$em->flush();
		}
		if(!$price=$em->getRepository('CaravaneEstateBundle:Price')->find(4)) {
			$price=new Price();
			$price->setPrice(4000);
			$price->setType('rent');
			$em->persist($price);
			$em->flush();
		}
		if(!$price=$em->getRepository('CaravaneEstateBundle:Price')->find(5)) {
			$price=new Price();
			$price->setPrice(6000);
			$price->setType('rent');
			$em->persist($price);
			$em->flush();
		}

		if(!$categoryMaison=$em->getRepository('CaravaneEstateBundle:Category')->findOneByName('Maison')) {
			$categoryMaison = new category();
			$categoryMaison->setName("Maison");
			$em->persist($categoryMaison);
			$em->flush();
		}

		$this->categoryMaison=$categoryMaison;

		if(!$categoryAppartement=$em->getRepository('CaravaneEstateBundle:Category')->findOneByName('Appartement')) {
			$categoryAppartement = new category();
			$categoryAppartement->setName("Appartement");
			$em->persist($categoryAppartement);
			$em->flush();
		}
		$this->categoryAppartement = $categoryAppartement;

		if(!$categoryAutre=$em->getRepository('CaravaneEstateBundle:Category')->findOneByName('Autre')) {
			$categoryAutre = new category();
			$categoryAutre->setName("Autre");
			$em->persist($categoryAutre);
			$em->flush();
		}
		$this->categoryAutre = $categoryAutre;

		if(!$zone1=$em->getRepository('CaravaneEstateBundle:Zone')->find(1)) {
			$zone1=new Zone();
			$zone1->setName("Bruxelles Sud et Centre");
			$em->persist($zone1);
			$em->flush();
		}
		$this->zone1 = $zone1;

		if(!$zone2=$em->getRepository('CaravaneEstateBundle:Zone')->find(2)) {
			$zone2=new Zone();
			$zone2->setName("Bruxelles Est");
			$em->persist($zone2);
			$em->flush();
		}
		$this->zone2 = $zone2;

		if(!$zone3=$em->getRepository('CaravaneEstateBundle:Zone')->find(3)) {
			$zone3=new Zone();
			$zone3->setName("Périphérie bruxelloise");
			$em->persist($zone3);
			$em->flush();
		}
		$this->zone3 = $zone3;

		if(!$zone4=$em->getRepository('CaravaneEstateBundle:Zone')->find(4)) {
			$zone4=new Zone();
			$zone4->setName("Province");
			$em->persist($zone4);
			$em->flush();
		}
		$this->zone4 = $zone4;


		$rs = curl_init();
		curl_setopt($rs,CURLOPT_URL,'http://www.esimmo.com/Virtual/lelion/secteurs.php' );
		curl_setopt($rs,CURLOPT_HEADER,0);
		curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($rs,CURLOPT_FOLLOWLOCATION,1);
		$xml = curl_exec($rs);
		$areasXml = new \SimpleXMLElement($xml);

		foreach($areasXml as $k=>$areaXml) {
			if(!$area=$em->getRepository("CaravaneEstateBundle:Area")->findOneByCode($areaXml->CODE)) {
				$area = new Area();
				$libe=ltrim ($areaXml->LIBE_FR, '-');
				$libe=trim($libe);
				$area->setNomQuartier($libe);
				$area->setCode($areaXml->CODE);

				if(isset($this->communes[(string)$areaXml->CODE])) {
					$commune=$this->communes[(string)$areaXml->CODE];
					if(isset($commune[1])) {
						$latlng=$commune[1];
						$area->setLatlng($latlng);
						$a=explode(",",$latlng);
						if(count($a)==2) {
							$area->setLat(trim($a[0]));
							$area->setLng(trim($a[1]));
						}
						$em->persist($area);
					}
				}
				$em->persist($area);
			}
		}
		$em->flush();
	}

	private function import($estates, $iType, $force=false, $parentClas=null,$p=1) {
		$em = $this->getDoctrine()->getManager();

	echo "page".$p."<br/>";
		if($p==1) {
			$query='update CaravaneEstateBundle:Estate E set E.status = 0 ';
			if($iType=="L") {
				$query.=" WHERE E.location=1";
			}
			else {
				$query.=" WHERE E.location=0";
				if($iType=="p") {
					$query.=" AND E.isNewBuilding=1 " ; 
				}
				else {
					$query.=" AND E.isNewBuilding=0 " ; 
				}
			}

			$q = $em->createQuery($query);
			$numUpdated = $q->execute();
		}
		


		
		$n=0;
		foreach($estates as $k=>$listEstate) {
			$n++;
			if($listEstate->MODI_DATE!='') {
				$date=date_create_from_format('d/m/Y', $listEstate->MODI_DATE);
			}
			else {
				if($parentClas) {
					$parentClas=str_replace("030/","",$parentClas);
					if($parentEstate= $em->getRepository('CaravaneEstateBundle:Estate')->findOneByReference('030/'.$parentClas)) {
						if(!$date=$parentEstate->getUpdatedOn()) {
							$date=new \Datetime();
						}

					}
				}
				else {
					$date=new \Datetime();
				}
				
			}
			echo "<br/>p:".$listEstate->CODE;
			$clas=$listEstate->CLAS;
			$clas=str_replace("030/","",$clas);
			if(!$estate= $em->getRepository('CaravaneEstateBundle:Estate')->findOneByReference('030/'.$clas)) {
				$estate=new Estate;
				$estate->setReference('030/'.$clas);
				$estate->setCreatedOn($date);
				$estate->setUpdatedOn($date);
			}
			else {
				echo "exists";
			}
			$estate->setUpdatedOn($date);
			echo $estate->getId();
			if(!$edate=$estate->getUpdatedOn()) {
				$edate=new \Datetime("now");
			}
			//if($edate->format('Ymd')!=$date->format('Ymd') || $force==true) {
			if($estate) {
				$estate->setBathrooms(intval($listEstate->BAIN_NBR));
/*
				$geocoder = $this->get('ivory_google_map.geocoder');
				$response = $geocoder->geocode($listEstate->ADRN." ".$listEstate->ADR1.", ".$listEstate->LOCA);

				foreach($response->getResults() as $result)
				{
					if($location=$result->getGeometry()->getLocation()) {
						$lat=$location->getLatitude();
						$lng=$location->getLongitude();
						$estate->setLat($lat);
						$estate->setLng($lng);
					}

				}
*/
				$rs = curl_init();
				curl_setopt($rs,CURLOPT_URL, 'http://www.esimmo.com/Virtual/lelion/offre.php?OxySeleCode='.$listEstate->CODE);
				curl_setopt($rs,CURLOPT_HEADER,0);
				curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
				curl_setopt($rs,CURLOPT_FOLLOWLOCATION,1);
				$xml = curl_exec($rs);
				$xmlEstates = new \SimpleXMLElement($xml);
				$xmlEstate=$xmlEstates->OFFRE[0];

				$estate->setPrix($xmlEstate->PRIX);
				echo "<br/>p:".$listEstate->PRIX_WEB;
				if($listEstate->PRIX_WEB==1) {
					$estate->setOnDemand(false);
				}
				else {
					$estate->setOnDemand(true);
				}

				//$estate->setSummary(utf8_encode(htmlentities($xmlEstate->FLASH_FR)));
				$summary=strip_tags((string)$xmlEstate->FLASH_FR,"<p><br><a><i><ul><li>");
				$summary=str_replace("<p></p>","",$summary);
				$description="<p>".$summary."</p>";
				//echo "<textarea>".$description."</textarea>";

				$estate->setSummary($summary);

				//$estate->setDescription(strip_tags("<p>".(string)$xmlEstate->FLASH_FR."</p>".nl2br((string)$xmlEstate->DESCR_FR),"<p><br><a><i><ul><li>"));
				$estate->setDescription($description);

				$estate->setName($xmlEstate->REFE);
				if($iType=="L") {
					$estate->setLocation(true);
					$estate->setIsNewBuilding(false);
				}
				else {
					echo "vente";
					$estate->setLocation(false);
					if($iType=="p") {
						echo "new";
						$estate->setIsNewBuilding(true);
					}
					else {
						echo "old";
						$estate->setIsNewBuilding(false);
					}
				}

				if($area=$em->getRepository('CaravaneEstateBundle:Area')->findOneByCode($xmlEstate->TABLGEOG)) {
					$estate->setArea($area);
				}
				if(!$loc=$em->getRepository('CaravaneEstateBundle:Location')->findOneByFr($xmlEstate->COMM)) {
					$loc=new Location();
					$loc->setFr(ucfirst($xmlEstate->COMM));
					$em->persist($loc);
				}
				$estate->setLocFr($loc->getZip()." ".$loc->getFr());
				$estate->setZip(intval($loc->getZip()));

				if(substr($xmlEstate->TABLIMME,0,2)=='01') {
					$category=$this->categoryAppartement;
				}
				else if(substr($xmlEstate->TABLIMME,0,2)=='02') {
					$category=$this->categoryMaison;
				}
				else {
					$category=$this->categoryAutre;
				}
				//echo  $xmlEstate->TABLIMME."-".$category->getName()."<br/>";
				$estate->setCategory($category);

				$zone=null;
				if(substr($xmlEstate->TABLGEOG,0,2)=='00' || substr($xmlEstate->TABLGEOG,0,2)=='01' || $xmlEstate->TABLGEOG=="0") {
					$zone=$this->zone1;
				}
				else if(substr($xmlEstate->TABLGEOG,0,2)=='02') {
					$zone=$this->zone2;
				}
				else if(substr($xmlEstate->TABLGEOG,0,2)=='10') {
					$zone=$this->zone3;
				}
				else if(substr($xmlEstate->TABLGEOG,0,2)=='11') {
					$zone=$this->zone4;
				}
				$estate->setZone($zone);

				$estate->setRooms(intval($xmlEstate->CHBR_NBR));
				$estate->setGarages(intval($xmlEstate->VOIT_NBR));
				$estate->setSurface(intval($xmlEstate->SURF_HAB));
				if($xmlEstate->JARD_ON!=0) {
					$garden="Jardin";
				}
				else if(intval($xmlEstate->SUPE_TER)>0) {
					$garden="Terrasse";
				}
				else {
					$garden="";
				}
				$estate->setGarden($garden);
				$estate->setRefe($listEstate->REFE);


				/*$xmlUrl=$listEstate->PHOTO_01;
				if(preg_match("/\//",$xmlUrl)) {
					$t=explode("/",$xmlUrl);
					$filename=$t[count($t)-1];
					if(!file_exists(__DIR__.'/../../../../../web/photos/big/'.$filename)) {
						if($ch = curl_init($xmlUrl)) {
							$fp = fopen(__DIR__.'/../../../../../web/photos/big/'.$filename, 'wb');
							curl_setopt($ch, CURLOPT_FILE, $fp);
							curl_setopt($ch, CURLOPT_HEADER, 0);
							curl_exec($ch);
							curl_close($ch);
							fclose($fp);
							if(!$photo=$em->getRepository('CaravaneEstateBundle:Photo')->findOneByFilename($filename)) {
								$photo= new Photo();
								$photo->setFilename($filename);
								$photo->setRanking(intval(substr($filename,0,2)));
								$photo->setIsDefault(true);
								$photo->setEstate($estate);
								$em->persist($photo);
							}

						}
					}
					if($photo=$em->getRepository('CaravaneEstateBundle:Photo')->findOneByFilename($filename)) {
						$photo->setIsDefault(true);
						$em->persist($photo);
					}
				}
				*/



				foreach($estate->getPhoto() as $photo) {
					//$estate->removePhoto($photo);
				}
				for($i=1;$i<=20;$i++) {
					$id=($i<10?"0".$i:$i);
					$xmlPhoto="PHOTO_".$id;

					if($xmlUrl=$xmlEstate->$xmlPhoto) {
						if(preg_match("/\//",$xmlUrl)) {
							$t=explode("/",$xmlUrl);
							$filename=$t[count($t)-1];
							if(!file_exists(__DIR__.'/../../../../../web/photos/big/'.$filename)) {
								if($ch = curl_init($xmlUrl)) {
									$fp = fopen(__DIR__.'/../../../../../web/photos/big/'.$filename, 'wb');
									curl_setopt($ch, CURLOPT_FILE, $fp);
									curl_setopt($ch, CURLOPT_HEADER, 0);
									curl_exec($ch);
									curl_close($ch);
									fclose($fp);
									if(!$photo=$em->getRepository('CaravaneEstateBundle:Photo')->findOneByFilename($filename)) {
										$photo= new Photo();
										$photo->setFilename($filename);
										$photo->setRanking(intval(substr($filename,0,2)));
										$photo->setEstate($estate);
										if($i==1) {
											$photo->setIsDefault(true);
										}
										$em->persist($photo);
									}
								}
							}

						}
					}
				}
				for($i=1;$i<=20;$i++) {
					$id=($i<10?"0".$i:$i);
					$xmlPhoto="PDF_".$id;
					//echo $xmlPhoto;
					//echo $listEstate->$xmlPhoto;
					if($xmlUrl=$listEstate->$xmlPhoto) {
						if(preg_match("/\//",$xmlUrl)) {
							$t=explode("/",$xmlUrl);
							$filename=$t[count($t)-1];
							//echo $filename;
							if(!file_exists(__DIR__.'/../../../../../web/pdfs/'.$filename)) {
								if($ch = curl_init($xmlUrl)) {
									$fp = fopen(__DIR__.'/../../../../../web/pdfs/'.$filename, 'wb');
									curl_setopt($ch, CURLOPT_FILE, $fp);
									curl_setopt($ch, CURLOPT_HEADER, 0);
									curl_exec($ch);
									curl_close($ch);
									fclose($fp);
									if(!$document=$em->getRepository('CaravaneEstateBundle:Document')->findOneByFilename($filename)) {
										$document= new Document();
										$document->setFilename($filename);
										$document->setRanking(intval(substr($filename,0,2)));
										$document->setEstate($estate);
										$em->persist($document);
									}
								}
							}
						}
					}
				}

				if($estate->getLat()=='' || $estate->getLng()=='') {
			//if($estate->getUpdatedOn()->format('Ymd')!=$date->format('Ymd') || $force==true) {
					$geocoder = $this->get('ivory_google_map.geocoder');
					$response = $geocoder->geocode($listEstate->ADRN." ".$listEstate->ADR1.", ".$listEstate->LOCA);

					foreach($response->getResults() as $result)
					{
						if($location=$result->getGeometry()->getLocation()) {
							$lat=$location->getLatitude();
							$lng=$location->getLongitude();
							$estate->setLat($lat);
							$estate->setLng($lng);
						}

					}
					$em->persist($estate);
					$em->flush();

				}

				$estate->setUpdatedOn($date);
				$estate->setStatus(1);

				if($parentClas) {
					$parentClas=str_replace("030/","",$parentClas);
					if($parentEstate= $em->getRepository('CaravaneEstateBundle:Estate')->findOneByReference('030/'.$parentClas)) {
						$estate->setParent($parentEstate);
						$parentEstate->addChild($estate);
						$em->persist($parentEstate);
					}
				}

			}
			$em->persist($estate);
			$em->flush();
		}
		return $n;
	}

/*
	public function setGeo($estates, $force=false) {
		$em = $this->getDoctrine()->getManager();
		foreach($estates as $k=>$listEstate) {
			$date=date_create_from_format('d/m/Y', $listEstate->MODI_DATE);;
			echo "<br/>p:".$listEstate->PRIX_WEB;
			if($estate= $em->getRepository('CaravaneEstateBundle:Estate')->findOneByReference('030/'.$listEstate->CLAS)) {
				if($estate->getLat()=='' || $estate->getLng()=='') {
			//if($estate->getUpdatedOn()->format('Ymd')!=$date->format('Ymd') || $force==true) {
					$geocoder = $this->get('ivory_google_map.geocoder');
					$response = $geocoder->geocode($listEstate->ADRN." ".$listEstate->ADR1.", ".$listEstate->LOCA);

					foreach($response->getResults() as $result)
					{
						if($location=$result->getGeometry()->getLocation()) {
							$lat=$location->getLatitude();
							$lng=$location->getLongitude();
							$estate->setLat($lat);
							$estate->setLng($lng);
						}

					}
					$em->persist($estate);
					$em->flush();

				}

			}

		}
	}
*/
		/**
		 * Lists all Estate entities.
		 *
		 */
		public function indexAction(Request $request)
		{
				$em = $this->getDoctrine()->getManager();

			/*  $paginator  = $this->get('knp_paginator');

				$entities = $em->getRepository('CaravaneEstateBundle:Estate')->findAll();

				return $this->render('CaravaneEstateBundle:Estate:index.html.twig', array(
						'entities' => $entities,
				));


*/





		$dql   = "SELECT E FROM CaravaneEstateBundle:Estate E";
		$query = $em->createQuery($dql);

		$paginator  = $this->get('knp_paginator');
		$entities = $paginator->paginate(
				$query,
				$request->query->get('page', 1)/*page number*/,
				25,/*limit per page*/
				array('defaultSortFieldName' => 'E.updatedOn', 'defaultSortDirection' => 'desc')

		);

		// parameters to template
		return $this->render('CaravaneEstateBundle:Estate:index.html.twig', array('entities' => $entities));

		}
		/**
		 * Creates a new Estate entity.
		 *
		 */
		public function createAction(Request $request)
		{
				$entity = new Estate();
				$form = $this->createCreateForm($entity);
				$form->handleRequest($request);

				if ($form->isValid()) {
						$em = $this->getDoctrine()->getManager();
						$em->persist($entity);
						$em->flush();

						return $this->redirect($this->generateUrl('caravane_estate_backend_estate_show', array('id' => $entity->getId())));
				}

				return $this->render('CaravaneEstateBundle:Estate:new.html.twig', array(
						'entity' => $entity,
						'form'   => $form->createView(),
				));
		}

		/**
		 * Creates a form to create a Estate entity.
		 *
		 * @param Estate $entity The entity
		 *
		 * @return \Symfony\Component\Form\Form The form
		 */
		private function createCreateForm(Estate $entity)
		{
				$form = $this->createForm(new EstateType(), $entity, array(
						'action' => $this->generateUrl('caravane_estate_backend_estate_create'),
						'method' => 'POST',
				));

				$form->add('submit', 'submit', array('label' => 'Sauver'));

				return $form;
		}

		/**
		 * Displays a form to create a new Estate entity.
		 *
		 */
		public function newAction()
		{
				$entity = new Estate();
				$form   = $this->createCreateForm($entity);

				return $this->render('CaravaneEstateBundle:Estate:new.html.twig', array(
						'entity' => $entity,
						'form'   => $form->createView(),
				));
		}

		/**
		 * Finds and displays a Estate entity.
		 *
		 */
		public function showAction($id)
		{
				$em = $this->getDoctrine()->getManager();

				$entity = $em->getRepository('CaravaneEstateBundle:Estate')->find($id);

				if (!$entity) {
						throw $this->createNotFoundException('Unable to find Estate entity.');
				}

				$deleteForm = $this->createDeleteForm($id);

				return $this->render('CaravaneEstateBundle:Estate:show.html.twig', array(
						'entity'      => $entity,
						'delete_form' => $deleteForm->createView(),
				));
		}

		/**
		 * Displays a form to edit an existing Estate entity.
		 *
		 */
		public function editAction($id)
		{
				$em = $this->getDoctrine()->getManager();

				$entity = $em->getRepository('CaravaneEstateBundle:Estate')->find($id);

				if (!$entity) {
						throw $this->createNotFoundException('Unable to find Estate entity.');
				}

				$editForm = $this->createEditForm($entity);
				$deleteForm = $this->createDeleteForm($id);

				if(!json_decode($entity->getStats(), true)) { 
		            $visits=array();
		        }
		        else {
		            $visits=json_decode($entity->getStats(), true);
		        ksort($visits);
		        }

		        $tA=array();
		        $i=0;
		        foreach($visits as $year=>$months) {
		        	//echo $year;
		        	foreach($months as $month=>$days) {
		        		foreach($days as $day=>$value) {
		        			$date=date_create_from_format('Y-m-d', $year."-".$month."-".$day);
		        			//$tA[]="[Date.UTC(".$year.", ".$month.", ".$day."),".(int)$value."]";
		        			//$tA[]=array($date->format('Y-m-d'),(int)$value);
		        			//$tA[]=array(strtotime($year."-".$month."-".$day." 00:00:00"),(int)$value);
		        			$tA[]=array($year."-".$month."-".$day,(int)$value);
		        			//$tA[]=array("Date.UTC(".$year.",  ".$month.", ".$day.")",(int)$value);
		        			$to=$day."/".$month."/".$year;
		        			if($i==0) {
		        				$from=$to;
		        			}
		        			$i++;
		        		}
		        	}
		        }
		        
		        //$tA=array(5,6,7,8,7,1,2);
		         $series = array(
			        array("type"=>"spline","name" => "Visites",    "data" => $tA)
			    );


			    $ob = new Highchart();
			    $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
			    $ob->title->text('Stats');
			    $ob->xAxis->title(array('text'  => "Du ".$from." au ".$to));
			    //$ob->xAxis->type("datetime");
			    //$ob->chart->dateFormat('YYYY-mm-dd');
			   	//$ob->xAxis->dateTimeLabelFormats(array('month'=>'%b %e, %Y','year'=>'%b'));
			    $ob->yAxis->title(array('text'  => "Visites"));
			    $ob->series($series);

			   
				return $this->render('CaravaneEstateBundle:Estate:edit.html.twig', array(
						'entity'      => $entity,
						'edit_form'   => $editForm->createView(),
						'delete_form' => $deleteForm->createView(),
						'chart' => $ob
				));
		}

		/**
		* Creates a form to edit a Estate entity.
		*
		* @param Estate $entity The entity
		*
		* @return \Symfony\Component\Form\Form The form
		*/
		private function createEditForm(Estate $entity)
		{
				$form = $this->createForm(new EstateType(), $entity, array(
						'action' => $this->generateUrl('caravane_estate_backend_estate_update', array('id' => $entity->getId())),
						'method' => 'PUT',
				));

				$form->add('submit', 'submit', array('label' => 'Sauver'));

				return $form;
		}
		/**
		 * Edits an existing Estate entity.
		 *
		 */
		public function updateAction(Request $request, $id)
		{
				$em = $this->getDoctrine()->getManager();

				$entity = $em->getRepository('CaravaneEstateBundle:Estate')->find($id);

				if (!$entity) {
						throw $this->createNotFoundException('Unable to find Estate entity.');
				}

				$deleteForm = $this->createDeleteForm($id);
				$editForm = $this->createEditForm($entity);
				$editForm->handleRequest($request);

				if ($editForm->isValid()) {
						$em->flush();

						return $this->redirect($this->generateUrl('caravane_estate_backend_estate_edit', array('id' => $id)));
				}

				return $this->render('CaravaneEstateBundle:Estate:edit.html.twig', array(
						'entity'      => $entity,
						'edit_form'   => $editForm->createView(),
						'delete_form' => $deleteForm->createView(),
				));
		}
		/**
		 * Deletes a Estate entity.
		 *
		 */
		public function deleteAction(Request $request, $id)
		{
				$form = $this->createDeleteForm($id);
				$form->handleRequest($request);

				if ($form->isValid()) {
						$em = $this->getDoctrine()->getManager();
						$entity = $em->getRepository('CaravaneEstateBundle:Estate')->find($id);

						if (!$entity) {
								throw $this->createNotFoundException('Unable to find Estate entity.');
						}

						$em->remove($entity);
						$em->flush();
				}

				return $this->redirect($this->generateUrl('caravane_estate_backend_estate'));
		}

		/**
		 * Creates a form to delete a Estate entity by id.
		 *
		 * @param mixed $id The entity id
		 *
		 * @return \Symfony\Component\Form\Form The form
		 */
		private function createDeleteForm($id)
		{
				return $this->createFormBuilder()
						->setAction($this->generateUrl('caravane_estate_backend_estate_delete', array('id' => $id)))
						->setMethod('DELETE')
						->add('submit', 'submit', array('label' => 'Delete','attr'=>array('class'=>'btn btn-danger')))
						->getForm()
				;
		}





	public function lastUpdatedAction($max=3) {
		$em = $this->getDoctrine()->getManager();

		 if(!$user=$this->getUser()) {
			 $user=null;
		 }
		$estates=$em->getRepository('CaravaneEstateBundle:Estate')->findLastUpdated($max, $user);
		return $this->render('CaravaneEstateBundle:Frontend:Homepage/last_updated.html.twig', array(
			'estates'      => $estates
		));
	}


	public function searchFormAction(Request $request, $type='sale') {
			$search_form=$this->searchForm($request, $type);
			return $this->render('CaravaneEstateBundle:Estate:search.html.twig', array(
					'search_form'   => $search_form->createView(),
					'type'=>$type
			));
	}

	public function searchAction(Request $request) {



			if(!$datas=$request->request->get('search_form')) {
				$datas=array('location'=>0);
			}


			$type=($datas['location']==1?'rent':'sale');

			$search_form=$this->searchForm($request, $type);
			$em = $this->getDoctrine()->getManager();
			if(isset($datas['reference'])) {
				if($datas['reference']!="") {
					return $this->redirect($this->generateUrl('caravane_estate_frontend_estate_'.$type.'_show',array('reference'=>$datas['reference'])));
				}
			}

			if($user=$this->getUser()) {
				if($contact=$user->getContact()) {
					if(isset($datas['save'])) {
						if($datas['save']!=false) {
							$contact->setLastSearch(json_encode($datas));
							$em->persist($contact);
							$em->flush();
						}
					}
				}
			}

			if(isset($datas['address'])) {
				$geocoder = $this->get('ivory_google_map.geocoder');
				$response = $geocoder->geocode($datas['address'].", Belgique");

				foreach($response->getResults() as $result)
				{
					if($location=$result->getGeometry()->getLocation()) {
						$lat=$location->getLatitude();
						$lng=$location->getLongitude();
						$datas['latlng']=$lat.",".$lng;
					}

				}
			}


			$estates=$em->getRepository('CaravaneEstateBundle:Estate')->getSearchResult($datas);
			if(count($estates)<=0 && $request->isXmlHttpRequest()) {
				return new Response('end');
			}
			return $this->render('CaravaneEstateBundle:Frontend:list.html.twig', array(
				'estates'      => $estates,
				 'search_form'   => $search_form->createView(),
					'type'=>$type
			));
	}
/*
		 public function searchByAreaAction(Request $request, $type,$id) {
				$em=$this->getDoctrine()->getmanager();
				$estates=$em->getRepository('CaravaneEstateBundle:Estate')->findByArea($id);
				 $search_form=$this->searchForm($request, $type);

				 return $this->render('CaravaneEstateBundle:Frontend:list.html.twig', array(
						'estates'      => $estates,
					 'search_form'   => $search_form->createView(),
						'type'=>$type
				));
		}
*/
	public function searchByAreasAction(Request $request, $type="sale") {
			$areas=array();
			$em=$this->getDoctrine()->getmanager();
			$estates=$em->getRepository('CaravaneEstateBundle:Estate')->findByAreaGrouped($type);


			foreach($estates as $k=>$area) {
					if($area['latlng']) {
							$tA=explode(',',$area['latlng']);
							$areas[]=array('id'=>$area['id'],'num'=>$area[1],"lat"=>$tA[0],"lng"=>$tA[1]);
					}

			}
			$response = new Response();
			$response->setContent(json_encode($areas));
			$response->headers->set('Content-Type', 'application/json');

			return $response;
	}


	public function saleViewAction($reference) {
			$em = $this->getDoctrine()->getManager();
			$estate=$em->getRepository('CaravaneEstateBundle:Estate')->findOneBy(array('reference'=>"030/".$reference,'status'=>true));
			$estate=$this->detailEstate($estate);
			return $this->render('CaravaneEstateBundle:Frontend:one.html.twig', array(
					'estate'      => $estate,
					'type'=>'sale'
			));
	}

	public function rentViewAction($reference) {
			$em = $this->getDoctrine()->getManager();
			$estate=$em->getRepository('CaravaneEstateBundle:Estate')->findOneBy(array('reference'=>"030/".$reference,'status'=>true));
			$estate=$this->detailEstate($estate);
			return $this->render('CaravaneEstateBundle:Frontend:one.html.twig', array(
					'estate'      => $estate,
					'type'=>'rent'
			));
	}

	public function saleListAction(Request $request) {
		if(!$datas=$request->query->get('c_s')) {
				$datas=array();
		}
		$em = $this->getDoctrine()->getManager();
		$estates=$em->getRepository('CaravaneEstateBundle:Estate')->getSearchResult($datas, array('location'=>0));

		if(count($estates)<=0 && $request->isXmlHttpRequest()) {
				return new Response('end');
		}

		$search_form=$this->searchForm($request, "sale");
		return $this->render('CaravaneEstateBundle:Frontend:list.html.twig', array(
			'estates'      => $estates,
			'type'=>'sale',
			'search_form'=>$search_form->createView(),
		));
	}

	public function rentListAction(Request $request) {
		if(!$datas=$request->request->get('form')) {
				$datas=array();
		}
		$em = $this->getDoctrine()->getManager();
		//$estates=$em->getRepository('CaravaneEstateBundle:Estate')->findBy(array("location"=>true,"status"=>true));
		$estates=$em->getRepository('CaravaneEstateBundle:Estate')->getSearchResult($datas, array('location'=>1));
		if(count($estates)<=0 && $request->isXmlHttpRequest()) {
				return new Response('end');
		}
		$search_form=$this->searchForm($request, "rent");
		return $this->render('CaravaneEstateBundle:Frontend:list.html.twig', array(
			'estates'      => $estates,
			'type'=>'rent',
			'search_form'=>$search_form->createView(),
		));
	}


	public function newListAction(Request $request) {

			if(!$datas=$request->query->get('c_s')) {
					$datas=array();
			}
			$em = $this->getDoctrine()->getManager();
			//$estates=$em->getRepository('CaravaneEstateBundle:Estate')->findBy(array("location"=>0,"status"=>true));

			$estates=$em->getRepository('CaravaneEstateBundle:Estate')->getSearchResult($datas, array('isNewBuilding'=>1, 'location'=>0));
			if(count($estates)<=0 && $request->isXmlHttpRequest()) {
					return new Response('end');
			}

			$search_form=$this->searchForm($request, "new");
			return $this->render('CaravaneEstateBundle:Frontend:list.html.twig', array(
					'estates'      => $estates,
					'type'=>'new',
					'search_form'=>$search_form->createView(),
			));
	}



	public function detailAction($id) {
		$em = $this->getDoctrine()->getManager();
		if(!$estate=$em->getRepository('CaravaneEstateBundle:Estate')->find($id)) {
			throw $this->createNotFoundException('Unable to find Estate entity.');
		}
		$estate=$this->detailEstate($estate);

		return $this->render('CaravaneEstateBundle:Frontend:estate_detail.html.twig', array(
				'estate'      => $estate
		));
	}

	public function detailEstate($estate) {
		$em = $this->getDoctrine()->getManager();
		if($user=$this->getUser()) {
			if(!$ue=$em->getRepository('CaravaneEstateBundle:UserEstate')->findOneBy(
					array(
							'estate'=>$estate->getId(),
							'user'=>$user->getId()
					)
			)) {
					$ue= new UserEstate();
					$ue->setUser($user);
					$ue->setEstate($estate);
					$ue->setCounter(1);
			}

			$ue->setCounter($ue->getCounter()+1);
			$ue->setDate(new \Datetime("now"));
			$em->persist($ue);
			$em->flush();
		}
		$estate->addVisit();
		$visits=$estate->getVisits();
		$estate->setDayview($visits['day']);
		$estate->setWeekview($visits['week']);
		$estate->setMonthview($visits['month']);
		$estate->setTotalview($visits['total']);
		$em->persist($estate);
		$em->flush();

		return $estate;
	}


		public function addToFavoriteAction($id) {
				$em = $this->getDoctrine()->getManager();
				if($user=$this->getUser()) {
					if($estate=$em->getRepository('CaravaneEstateBundle:Estate')->find($id)) {
						if(!$userEstate=$em->getRepository('CaravaneEstateBundle:UserEstate')->findOneBy(array('user'=>$user,'estate'=>$estate))) {
								$userEstate=new UserEstate();
								$userEstate->setUser($user);
								$userEstate->setEstate($estate);
						}
						if($userEstate->getSaved()==true) {
								$userEstate->setSaved(false);
						}
						else {
							$userEstate->setSaved(true);

							$message = \Swift_Message::newInstance()
						        ->setSubject('Website: Bien ajouté en favori')
						        ->setFrom('contact@immo-lelion.be')
						        ->setTo('contact@immo-lelion.be')
						        ->setBody($this->renderView('CaravaneCmsBundle:Frontend:Email/email_favorite.txt.twig', array('user' => $user, 'estate'=>$estate)))
						    ;
						    if(!$this->get('mailer')->send($message)) {
						    	echo "error";
						    }
						    else {
						    	echo "ok";
						    }
						}
						$em->persist($userEstate);
						$em->flush();
						
						return new Response('success');

					}
					else {
						return new Response('no estate');
					}
				}
				return new Response('no user');
		}


		private function searchForm($request, $type='sale') {
				$em = $this->getDoctrine()->getManager();
				$prices=$em->getRepository('CaravaneEstateBundle:Price')->getPrices($type);

				$options=array('prices'=>$prices,'type'=>$type);
				if($type=='rent') {
					$options['location']=1;

				}
				else {
					$options['location']=0;
				}
				$options['isNewBuilding']=0;
				if($type=='new') {
					$options['isNewBuilding']=1;
					$options['location']=0;
				}


				$search_form = $this->createForm( 'search_form', null, $options);
				$search_form->get('location')->setData($options['location']);
				$search_form->add('submit', 'submit', array('label' => 'Rechercher','attr'=>array('class'=>'form-control btn-red')));
				$search_form->handleRequest($request);
				return $search_form;
/*
				$datas=array('location'=>($type=='sale'?0:1),"sort"=>"updatedOn desc");
				if($type=='new') {
						$datas['isNewBuilding']=true;
						$datas['location']=0;
				}
				$em = $this->getDoctrine()->getManager();
				$prices=$em->getRepository('CaravaneEstateBundle:Price')->getPrices($type);



				$form = $this->createFormBuilder($datas)
				->add('prix','choice', array(
								"label"=>false,
								"expanded"=>true,
								"multiple"=>true,
								'choices' => $prices,
								"attr"=>array(
										"class"=>"btn-group btn-group-vertical",
										"data-toggle"=>"buttons"
								)
						))
						->add('area',"entity",array(
								"label"=>false,
								"empty_value" => 'Quartier',
								"class"=>"Caravane\Bundle\EstateBundle\Entity\Area"
						))
						->add('zone','entity', array(
								"label"=>false,
								"expanded"=>true,
								"multiple"=>true,
								"class"=>"Caravane\Bundle\EstateBundle\Entity\Zone",
								"attr"=>array(
										"class"=>"btn-group btn-group-vertical",
										"data-toggle"=>"buttons"
								)
						))
						->add("rayon","choice",array(
								"label"=>false,
								"empty_value" => 'Rayon',
								"choices"=>array(
										"1"=>"1 km",
										"5"=>"5 km",
										"10"=>"10 km",
										"20"=>"20 km",
										"50"=>"50 km"
								)
						))
						->add('reference',"text",array(
								"attr"=>array(
										"placeholder"=>"Reference"
								)
						))
						 ->add('address',"text",array(
								"attr"=>array(
										"placeholder"=>"Adresse"
								)
						))
						->add('category','entity', array(
								"label"=>false,
								"expanded"=>true,
								"multiple"=>true,
								"class"=>"Caravane\Bundle\EstateBundle\Entity\Category",
								"attr"=>array(
										"class"=>"btn-group btn-group-vertical",
										"data-toggle"=>"buttons"
								)
						))
						->add('location','hidden')

						->add('isNewBuilding',($type!='rent'?'checkbox':'hidden'),array(
								"label"=>"Biens neufs uniquement",
								"attr"=>array(
										"class"=>"btn "
								)
						))
						->add('keyword','text',array(
								"attr"=>array(
										"placeholder"=>"Mot clef (ex.: piscine, brugmann)"
								)
						))
						->add('offset','hidden',array(
								"data"=>0))
						->add('limit','hidden',array(
								"data"=>24,
						))
						->add('sort','choice',array(
								"label"=>false,
								"empty_value" => 'Ordonner les résultats par',
								"choices"=>array(
										"prix asc"=>"Prix croissants",
										"prix desc"=>"Prix decroissants",
										"locfr asc"=>"Communes",
										"updatedOn desc"=>"Nouveautés",
								)
						))

						->getForm();
						$form->add('submit', 'submit', array('label' => 'Rechercher','attr'=>array('class'=>'form-control btn-red')));
				 //   $form->setMethod('GET');
						$form->handleRequest($request);

						return $form;
						*/
		}


		function printAction(Request $request, $reference) {
			$em = $this->getDoctrine()->getManager();

			$entity = $em->getRepository('CaravaneEstateBundle:Estate')->findOneByReference('030/'.$reference);

			if (!$entity) {
					throw $this->createNotFoundException('Unable to find Estate entity.');
			}

			$html = $this->renderView('CaravaneCmsBundle:Frontend:Template/print.html.twig', array(
					'estate'      => $entity,
					'dir'=>__DIR__."/../../../../../web"
			));


			$html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'fr', true, 'UTF-8', array(10, 15, 10, 15));
		    $html2pdf->pdf->SetDisplayMode('fullpage');
		    $html2pdf->writeHTML($html);
		    return $html2pdf->Output($entity->getShortReference().'Facture.pdf');
die();

		    $response = new Response();
	        $response->clearHttpHeaders();
	        $response->setContent(file_get_contents($fichier));
	        $response->headers->set('Content-Type', 'application/pdf');
	        $response->headers->set('Content-disposition', 'attachment; filename="'.$entity->getShortReference().'.pdf"');

	        return $response;
		}




}
