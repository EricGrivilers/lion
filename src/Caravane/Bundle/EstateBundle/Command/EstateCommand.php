<?php


namespace Caravane\Bundle\EstateBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


use Caravane\Bundle\EstateBundle\Entity\Estate;
use Caravane\Bundle\EstateBundle\Entity\Location;
use Caravane\Bundle\EstateBundle\Entity\Photo;

class EstateCommand extends ContainerAwareCommand {

    private $actives;

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
        "027"=>array("Cinquantenaire/Montgomery","50.838258, 4.402959"),

        "0330"=>array("Lasne","50.687517, 4.483315"),
        "0331"=>array("Lasne","50.687517, 4.483315"),
        "0332"=>array("Lasne - Ohain","50.699941, 4.467007"),
        "0333"=>array("Lasne - Plancenoit","50.662851, 4.429671"),
        "0334"=>array("Lasne - Maransart","50.658743, 4.466964"),
        "0335"=>array("Lasne - Couture","50.674492, 4.472758"),
        "0340"=>array("Rixensart","50.712609, 4.533019"),
        "0341"=>array("Rixensart","50.712609, 4.533019"),
        "0342"=>array("Rixensart - Bourgeois","50.706712, 4.510832"),
        "0343"=>array("Rixensart - Genval","50.721497, 4.492950"),

        "0301"=>array("Rhode-Saint-Genèse","50.746684, 4.361737"),
        "0302"=>array("Rhode-St-Genèse - Espinette Centrale","50.748391, 4.390887"),
        "0303"=>array("Rhode-St-Genèse - Ancien Golf","50.740908, 4.400689"),
        "0320"=>array("Waterloo","50.710127, 4.401829"),
        "0321"=>array("Waterloo - Faubourg","50.729211, 4.403640")
    );


    private $oldCodes=array(
        "01"=>"010",
        //	"00"=>"01A",
        "01B"=>"0301",
        "01B1"=>"0302",
        "01B2"=>"0303",
        "01H"=>"0320",
        "01H1"=>"0321",

        "000"=>"01 A1",
        "0000"=>"01 A2",
        "0001"=>"01 A3",
        "001"=>"01 A4",

        "01F"=>"0311",
        "01D"=>"0310",

        "03"=>"04",
        "031"=>"041",
        "033"=>"043",
        "034"=>"044",
        "035"=>"045",
        "036"=>"046",
        "037"=>"047",
        "038"=>"048",
        "039"=>"049",
        "9"=>"07",

        "103"=>"0330",
        "131"=>"0391",
        "133"=>"0393",
        "1033"=>"0332",
        "1035"=>"0333",
        "1037"=>"0334",
        "1039"=>"0335",
        "111"=>"0340",
        "112"=>"0350",
        "113"=>"0370",
        "114"=>"0343",
        "115"=>"0380",
        "1114"=>"0343",
        "1141"=>"0361",
        "1149"=>"0364",
        "1145"=>"0362",
        "1159"=>"0385",
        "1123"=>"0352",
        "13"=>"0390",

        "1155"=>"0383",
        "139"=>"0394",
        "1325"=>"0392",
        "1113"=>"0342",
        "113G"=>"0379",
        "113H"=>"0379A",

        "1031"=>"0331",
        "1037"=>"0334",
        "1121"=>"0351",
        "1131"=>"0371",
        "1135"=>"0373",
        "1139"=>"0375",
        "1147"=>"0363",
        "1153"=>"0382",
        "1118"=>"0345",
        "1137"=>"0374",
        "113D"=>"0377",
        "113B"=>"0376",
        "113F"=>"0378",
        "1157"=>"0384",
        "1133"=>"0372",
    );




    protected function configure() {
        $this
        ->setName('caravane:estate:import')
        ->setDescription('Import from Evosys')
        //->addArgument('type', InputArgument::REQUIRED, 'iType')
        //->addOption('type', null, InputOption::VALUE_NONE, 'Type')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $estateManager=$this->getContainer()->get('caravane_estate.manager');
        $estateManager->setUp();
        $estateManager->import();
        /*
        $em = $this->getContainer()->get('doctrine')->getManager();
        $iType = $input->getArgument('type');
        $this->setup();
        $actives = $this->getActives($iType);
        if(count($actives['toProcess']>0)) {
            echo "-----> ".count($actives['toProcess'])." new\n";
            foreach($actives['toProcess'] as $estate) {
                $estate=$this->import($estate,$iType);
                $em->persist($estate);
            }
        }

        $em->flush();
        $notImportedEstates=$em->getRepository('CaravaneEstateBundle:Estate')->imported($actives['existing']);


        echo "-----> ".count($actives['toProcess'])." existing\n";

        foreach($notImportedEstates as $id) {
            $e= $em->getRepository('CaravaneEstateBundle:Estate')->find($id);
            $estate=$this->import($estate,$iType);
            $em->persist($estate);
        }
        */
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
        $em = $this->getContainer()->get('doctrine')->getManager();
        //echo $listEstate->CODE;
        $clas=$listEstate->CLAS;
        $clas=str_replace("030/","",$clas);
        if(!$estate= $em->getRepository('CaravaneEstateBundle:Estate')->findOneByReference('030/'.$clas)) {
            $date=new \Datetime();
            $estate=new Estate;
            $estate->setReference('030/'.$clas);
            $estate->setCreatedOn($date);
            $estate->setUpdatedOn($date);
            echo "new\n";
        }
        else {
            echo "exists\n";
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
        echo "\np:".$listEstate->PRIX_WEB;
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
                echo "is vente";
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

        if($photos=$estate->getPhotos()) {
            foreach($photos as $photo) {
                echo "-+-";
                unlink(__DIR__.'/../../../../../web/photos/big/'.$photo->getFilename());
                $estate->removePhoto($photo);
            }
        }



        for($i=1;$i<=40;$i++) {
            $id=($i<10?"0".$i:$i);
            //$id="0".$i;
            $xmlPhoto="PHOTO_".$id;

            if($xmlUrl=$xmlEstate->$xmlPhoto) {
                if(preg_match("/\//",$xmlUrl)) {
                    $t=explode("/",$xmlUrl);
                    $filename=end($t);
                    //$t[count($t)-1];
                    if(!file_exists(__DIR__.'/../../../../../web/photos/big/'.$filename) ) {
                        if($ch = curl_init($xmlUrl)) {
                            $fp = fopen(__DIR__.'/../../../../../web/photos/big/'.$filename, 'wb');
                            curl_setopt($ch, CURLOPT_FILE, $fp);
                            curl_setopt($ch, CURLOPT_HEADER, 0);
                            curl_exec($ch);
                            curl_close($ch);
                            fclose($fp);
                        }
                    }
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
                    if(!file_exists(__DIR__.'/../../../../../web/pdfs/'.$filename) ) {
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
            $geocoder = $this->getContainer()->get('ivory_google_map.geocoder');
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

        /*
        if($parentClas) {
            $parentClas=str_replace("030/","",$parentClas);
            if($parentEstate= $em->getRepository('CaravaneEstateBundle:Estate')->findOneByReference('030/'.$parentClas)) {
                $estate->setParent($parentEstate);
                $parentEstate->addChild($estate);
                $em->persist($parentEstate);
            }
        }
        */

        return $estate;
    }



    protected function setup() {
        //$em = $this->getContainer()->get('doctrine');
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

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