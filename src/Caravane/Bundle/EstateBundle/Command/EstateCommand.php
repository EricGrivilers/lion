<?php


namespace Caravane\Bundle\EstateBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


use Caravane\Bundle\EstateBundle\Entity\Estate;

class EstateCommand extends ContainerAwareCommand {

    private $actives;

    private $categoryMaison;
    private $categoryAppartement;
    private $categoryAutre;
    private $zone1;
    private $zone2;
    private $zone3;
    private $zone4;


    protected function configure() {
        $this
        ->setName('caravane:estate:import')
        ->setDescription('Import from Evosys')
        ->addArgument('type', InputArgument::REQUIRED, 'iType')
        //->addOption('type', null, InputOption::VALUE_NONE, 'Type')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $em = $this->getContainer()->get('doctrine');
        $iType = $input->getArgument('type');
        $this->setup();
        $actives = $this->getActives($iType);
        if(count($actives['toProcess']>0)) {
            foreach($actives['toProcess'] as $estate) {
                $this->import($estate,$iType);
            }
        }
        $notImportedEstates=$em->getRepository('CaravaneEstateBundle:Estate')->imported($actives['existing']);


        echo count($notImportedEstates);
        $text = "ok";
        $output->writeln($text);
    }



    protected function getActives($t='V') {
        $em = $this->getContainer()->get('doctrine');
        $rs = curl_init();
        curl_setopt($rs,CURLOPT_HEADER,0);
        curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($rs,CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($rs,CURLOPT_FRESH_CONNECT,true);

        $existing=array();
        $toProcess=array();
        curl_setopt($rs,CURLOPT_URL,'http://www.esimmo.com/Virtual/lelion/resultats.php?OxySeleOffr='.$t.'&OxySeleBiensParPage=500&OxyPage=1' );
        $xml = curl_exec($rs);
        $estates = new \SimpleXMLElement($xml);
        foreach($estates as $listEstate) {
            $clas=$listEstate->CLAS;
            //echo $clas;
            $clas=str_replace("030/","",$clas);
            if($estate= $em->getRepository('CaravaneEstateBundle:Estate')->findOneByReference('030/'.$clas)) {
                $existing[]=$estate->getId();
            }
            else {
                $toProcess[]=$listEstate;
            }
        }
        return array('existing'=>$existing, 'toProcess'=>$toProcess);
    }


    protected function import($listEstate,$iType) {
        $em = $this->getContainer()->get('doctrine');
        //echo $listEstate->CODE;
        $clas=$listEstate->CLAS;
        $clas=str_replace("030/","",$clas);
        if(!$estate= $em->getRepository('CaravaneEstateBundle:Estate')->findOneByReference('030/'.$clas)) {
            $date=new \Datetime();
            $estate=new Estate;
            $estate->setReference('030/'.$clas);
            $estate->setCreatedOn($date);
            $estate->setUpdatedOn($date);
        }
        else {
            echo "exists";
        }
        $estate->setUpdatedOn($date);
        $estate->setImportedOn($date);

        $estate->setBathrooms(intval($listEstate->BAIN_NBR));

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
            echo "vente :".$iType."<br/>";
            $estate->setLocation(false);
            if($iType=="p") {
                echo "is new";
                $estate->setIsNewBuilding(true);
            }
            else if($iType=="t") {
                echo "is terrain";
                $estate->setIsTerrain(true);
            }
            else {
                echo "is old";
                $estate->setIsNewBuilding(false);
                $estate->setIsTerrain(false);
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
        echo "-".$xmlEstate->TABLGEOG."-";
        if(array_key_exists((string)$xmlEstate->TABLGEOG, $this->oldCodes)) {
            $xmlEstate->TABLGEOG=$this->oldCodes[(string)$xmlEstate->TABLGEOG];
            echo "---old code---";
        }
        if(substr($xmlEstate->TABLGEOG,0,2)=='00' || substr($xmlEstate->TABLGEOG,0,2)=='01' || $xmlEstate->TABLGEOG=="0") {
            $zone=$this->zone1;
        }
        else if(substr($xmlEstate->TABLGEOG,0,2)=='02') {
            $zone=$this->zone2;
        }
        /*else if(substr($xmlEstate->TABLGEOG,0,2)=='10') {
            $zone=$this->zone3;
        }*/
        else if(substr($xmlEstate->TABLGEOG,0,2)=='03') {
            $zone=$this->zone3;
        }
        else {
            $zone=$this->zone4;
        }
        /*else if(substr($xmlEstate->TABLGEOG,0,2)=='11') {
            $zone=$this->zone4;
        }*/
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
    }



    protected function setup() {
        $em = $this->getContainer()->get('doctrine');

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
        /*
        $categoryMaison->setTranslatableLocale('en');
        $em->refresh($categoryMaison);

        if($categoryMaison->getName()=='') {
            $categoryMaison->setName('House');
            $categoryMaison->setTranslatableLocale('en');
            $em->persist($categoryMaison);
            $em->flush();
        }
        $categoryMaison->setTranslatableLocale('fr');
        $em->refresh($categoryMaison);
*/
        $this->categoryMaison=$categoryMaison;

        if(!$categoryAppartement=$em->getRepository('CaravaneEstateBundle:Category')->findOneByName('Appartement')) {
            $categoryAppartement = new category();
            $categoryAppartement->setName("Appartement");
            $em->persist($categoryAppartement);
            $em->flush();
        }
        /*
        $categoryAppartement->setTranslatableLocale('en');
        $em->refresh($categoryAppartement);

        if($categoryAppartement->getName()=='') {
            $categoryAppartement->setName('Apartment');
            $categoryAppartement->setTranslatableLocale('en');
            $em->persist($categoryAppartement);
            $em->flush();
        }
        $categoryAppartement->setTranslatableLocale('fr');
        $em->refresh($categoryAppartement);
*/
        $this->categoryAppartement = $categoryAppartement;

        if(!$categoryAutre=$em->getRepository('CaravaneEstateBundle:Category')->findOneByName('Autre')) {
            $categoryAutre = new category();
            $categoryAutre->setName("Autre");
            $em->persist($categoryAutre);
            $em->flush();
        }
        /*
                $categoryAutre->setTranslatableLocale('en');
                $em->refresh($categoryAutre);

                if($categoryAutre->getName()=='') {
                    $categoryAutre->setName('Other');
                    $categoryAutre->setTranslatableLocale('en');
                    $em->persist($categoryAutre);
                    $em->flush();
                }
                $categoryAutre->setTranslatableLocale('fr');
                $em->refresh($categoryAutre);
        */

        $this->categoryAutre = $categoryAutre;

        if(!$zone1=$em->getRepository('CaravaneEstateBundle:Zone')->find(1)) {
            $zone1=new Zone();
            $zone1->setName("Bruxelles Sud et Centre");
            $em->persist($zone1);
            $em->flush();
        }
        /*
                $zone1->setTranslatableLocale('en');
                $em->refresh($zone1);

                if($zone1->getName()=='') {
                    $zone1->setName('South and Center Brussels');
                    $zone1->setTranslatableLocale('en');
                    $em->persist($zone1);
                    $em->flush();
                }
                $zone1->setTranslatableLocale('fr');
                $em->refresh($zone1);

        */
        $this->zone1 = $zone1;

        if(!$zone2=$em->getRepository('CaravaneEstateBundle:Zone')->find(2)) {
            $zone2=new Zone();
            $zone2->setName("Bruxelles Est");
            $em->persist($zone2);
            $em->flush();
        }
        /*
                $zone2->setTranslatableLocale('en');
                $em->refresh($zone2);

                if($zone2->getName()=='') {
                    $zone2->setName('East Brussels');
                    $zone2->setTranslatableLocale('en');
                    $em->persist($zone2);
                    $em->flush();
                }
                $zone2->setTranslatableLocale('fr');
                $em->refresh($zone2);
        */
        $this->zone2 = $zone2;

        if(!$zone3=$em->getRepository('CaravaneEstateBundle:Zone')->find(3)) {
            $zone3=new Zone();
            $zone3->setName("Périphérie bruxelloise");
            $em->persist($zone3);
            $em->flush();
        }
        /*
                $zone3->setTranslatableLocale('en');
                $em->refresh($zone3);

                if($zone3->getName()=='') {
                    $zone3->setName('Around Brussels');
                    $zone3->setTranslatableLocale('en');
                    $em->persist($zone3);
                    $em->flush();
                }
                $zone3->setTranslatableLocale('fr');
                $em->refresh($zone3);
        */

        $this->zone3 = $zone3;

        if(!$zone4=$em->getRepository('CaravaneEstateBundle:Zone')->find(4)) {
            $zone4=new Zone();
            $zone4->setName("Country");
            $em->persist($zone4);
            $em->flush();
        }
        /*
                $zone4->setTranslatableLocale('en');
                $em->refresh($zone4);

                if($zone4->getName()=='') {
                    $zone4->setName('Province');
                    $zone4->setTranslatableLocale('en');
                    $em->persist($zone4);
                    $em->flush();
                }
                $zone4->setTranslatableLocale('fr');
                $em->refresh($zone4);
        */

        $this->zone4 = $zone4;


        $rs = curl_init();
        curl_setopt($rs,CURLOPT_URL,'http://www.esimmo.com/Virtual/lelion/secteurs.php' );
        curl_setopt($rs,CURLOPT_HEADER,0);
        curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($rs,CURLOPT_FOLLOWLOCATION,1);
        $xml = curl_exec($rs);
        $areasXml = new \SimpleXMLElement($xml);

        foreach($areasXml as $k=>$areaXml) {
            $libe=ltrim ($areaXml->LIBE_FR, '-');
            $libe=ltrim ($libe, '-');
            $libe=trim($libe);
            if(!$area=$em->getRepository("CaravaneEstateBundle:Area")->findOneByCode($areaXml->CODE)) {
                $area = new Area();

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


            }
            if($area->getLat()=='' || $area->getLng()=='' || $area->getLatLng()=='') {
                $geocoder = $this->getContainer()->get('ivory_google_map.geocoder');
                $response = $geocoder->geocode($libe.", Belgique");

                foreach($response->getResults() as $result)
                {
                    if($location=$result->getGeometry()->getLocation()) {
                        $lat=$location->getLatitude();
                        $lng=$location->getLongitude();
                        $area->setLat($lat);
                        $area->setLng($lng);
                        $area->setLatLng($lat.", ".$lng);
                        break;
                    }

                }
            }
            $em->persist($area);
        }
        $em->flush();
    }
}