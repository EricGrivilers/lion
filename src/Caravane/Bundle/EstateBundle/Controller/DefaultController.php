<?php

namespace Caravane\Bundle\EstateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Caravane\Bundle\CrmBundle\Entity\Contact;
use Caravane\Bundle\EstateBundle\Entity\Estate;
use Caravane\Bundle\EstateBundle\Entity\Photo;
use Caravane\Bundle\EstateBundle\Entity\UserEstate;
use Symfony\Component\Security\Core\Util\SecureRandom;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CaravaneEstateBundle:Default:index.html.twig', array('name' => $name));
    }



    public function installAction() {
    	echo "U";
		$userManager = $this->get('fos_user.user_manager');
		$em = $this->getDoctrine()->getManager();

    	$conn = $this->get('database_connection');
		$sql = 'SELECT * FROM users WHERE processed!=1 LIMIT 0,50';
		$rows = $conn->query($sql);

		foreach($rows as $row) {
			$email=$row['email'];
			if(!$user=$userManager->findUserByEmail($email) ) {
				if($row['password']=="") {
					$generator = new SecureRandom();
					$row['password']=$generator->nextBytes(10);
				}
				echo ":".$row['password'].":<br/>";
				$user = $userManager->createUser();
				$user->setPlainPassword($row['password']);
				$user->setUsername($email);
				$user->setEmail($email);
				$user->setEnabled(true);
				if($row['lastVisit']!="0000-00-00 00:00:00" && $row['lastVisit']!='') {
					echo $row['lastVisit']."<br/>";
					$lastVisit=date_create_from_format('Y-m-d H:i:s', $row['lastVisit']);
					$user->setLastLogin($lastVisit);
				}
				$userManager->updateUser($user);

				$contact = new Contact();
				echo "--".$row['date']."--<br/>";
				if($row['date']!="0000-00-00 00:00:00" && $row['date']!='') {
					$date=date_create_from_format('Ymd', $row['date']);
					$contact->setCreatedOn($date);
				}


				$contact->setUser($user);
				$contact->setLastname($row['lastname']);
				$contact->setFirstname($row['firstname']);
				$contact->setLanguage($row['language']);
				$contact->setTel($row['tel']);
				$contact->setZip($row['zip']);
				$contact->setFax($row['fax']);
				$contact->setStreet($row['street']);
				$contact->setNumber($row['number']);
				$contact->setCity($row['city']);
				$contact->setCountry($row['country']);
				$contact->setCompany($row['company']);
				$contact->setSalutation($row['salutation']);







				$user->setContact($contact);
				$em->persist($contact);
				$userManager->updateUser($user);

				$sql2 = 'UPDATE users SET processed=1 WHERE email="'.$email.'"';
				$conn->query($sql2);
			}
			else {
				echo "exists ".$email."<br/>";
				$sql2 = 'UPDATE users SET processed=1 WHERE email="'.$email.'"';
				$conn->query($sql2);
			}
		}
		$em->flush();

		echo "E";

		$zone1=$em->getRepository('CaravaneEstateBundle:Zone')->find(1);
		$zone2=$em->getRepository('CaravaneEstateBundle:Zone')->find(2);
		$zone3=$em->getRepository('CaravaneEstateBundle:Zone')->find(3);
		$zone4=$em->getRepository('CaravaneEstateBundle:Zone')->find(4);

		$categoryMaison=$em->getRepository('CaravaneEstateBundle:Category')->findOneByName('Maison');
		$categoryAppartement=$em->getRepository('CaravaneEstateBundle:Category')->findOneByName('Appartement');
		$categoryAutre=$em->getRepository('CaravaneEstateBundle:Category')->findOneByName('Autre');


		$sql = 'SELECT * FROM items WHERE processed=0 LIMIT 0,5';
		$rows = $conn->query($sql);
		foreach($rows as $row) {
			$reference=$row['reference'];
			if(!$estate=$em->getRepository('CaravaneEstateBundle:Estate')->findOneByReference($reference)) {
				$estate=new Estate;
				$estate->setStatus(false);
				$estate->setReference($reference);
				if($row['datein']!="0000-00-00" && $row['datein']!='') {
					$datein=date_create_from_format('Y-m-d', $row['datein']);
					$estate->setCreatedOn($datein);
				}
				if($row['update']!="0000-00-00" && $row['update']!='') {
					$update=date_create_from_format('Y-m-d', $row['update']);
					$estate->setUpdatedOn($update);
				}
				$estate->setPrix($row['prix']);
				$t="zone".$row['zone'];
				$zone=$$t;
				$estate->setZone($zone);
				$estate->setLocFr($row['locfr']);
				$estate->setDescription($row['shortdescren']);
				$estate->setSummary($row['shortdescren']);
				$estate->setLocation($row['location']=='Y'?true:false);
				$estate->setOnDemand($row['surdemande']=='Y'?true:false);
				$estate->setName($row['name']);

				if($row['type']==1) {
					$category= $categoryMaison;
				}
				else if($row['type']==2) {
					$category= $categoryAppartement;
				}
				else {
					$category= $categoryAutre;
				}
				$estate->setCategory($category);
				$estate->setZip($row['zip']);
				$estate->setRooms($row['rooms']);
				$estate->setBathrooms($row['bathrooms']);
				$estate->setGarden($row['garden']);
				$estate->setGarages($row['garages']);
				$estate->setSurface($row['area']);
				$estate->setLat($row['Lat']);
				$estate->setLng($row['Lng']);

				$em->persist($estate);


				$sql2 = 'SELECT * FROM photo2item WHERE item_id="'.$row['num'].'" ORDER BY ranking';
				$photorows = $conn->query($sql);
				foreach($photorows as $p) {
					$filename=$p['photo'].".jpg";
					$filename=str_replace(".jpg.jpg",'.jpg',$filename);
					echo $filename."<br/>";
					echo __DIR__.'/../../../../../web/photos/big/'.$filename."<br/>";
					if(!file_exists(__DIR__.'/../../../../../web/photos/big/'.$filename)) {
						echo "http://www.immo-lelion.be/photos/big/".$filename."<br/>";
						if($ch = curl_init("http://www.immo-lelion.be/photos/big/".$filename)) {
							echo "curl<br/>";
							$fp = fopen(__DIR__.'/../../../../../web/photos/big/'.$filename, 'wb');
							curl_setopt($ch, CURLOPT_FILE, $fp);
							curl_setopt($ch, CURLOPT_HEADER, 0);
							curl_exec($ch);
							curl_close($ch);
							fclose($fp);
							$r=1;
							if(!$photo=$em->getRepository('CaravaneEstateBundle:Photo')->findOneByFilename($filename)) {
								$photo= new Photo();
								$photo->setFilename($filename);
								$photo->setRanking($r);
								$r++;
								$photo->setEstate($estate);
								if($r==1) {
									$photo->setIsDefault(true);
								}
								$em->persist($photo);
							}
						}
					}

				}
				$sql2 = 'UPDATE items SET processed=1 WHERE reference="'.$reference.'"';
				$conn->query($sql2);

				
			}
			else {
				$sql2 = 'UPDATE items SET processed=1 WHERE reference="'.$reference.'"';
				$conn->query($sql2);
			}

			$em->flush();

		}

		echo "UE";

		$sql = 'SELECT UI.*, U.email, I.reference  FROM users2items UI LEFT JOIN items I ON I.num=UI.itemId LEFT JOIN users U ON U.id=UI.userId WHERE UI.processed=0 AND I.reference IS NOT NULL LIMIT 0,100';
		$rows = $conn->query($sql);
		foreach($rows as $row) {
			$email=$row['email'];
			$reference=$row['reference'];
			echo $email."/".$reference."<br/>";
			if($user=$userManager->findUserByEmail($email)) {
				if($estate=$em->getRepository('CaravaneEstateBundle:Estate')->findOneByReference($reference)) {
					echo $user->getEmail()." <> ".$estate->getName()."<br/>";
					
					$ue=new UserEstate();
					$ue->setUser($user);
					$ue->setEstate($estate);
					$ue->SetCounter((int)$row['counter']<=1?1:(int)$row['counter']);
					if($row['date']!="0000-00-00 00:00:00:" && $row['date']!='') {
						$date=date_create_from_format('Y-m-d H:i:s', $row['date']);
						$ue->setDate($date);
					}
					if($row['saved']==1) {
						$ue->setSaved(true);
					}
					$em->persist($ue);
				}
			}
			$sql2 = 'UPDATE users2items SET processed=1 WHERE userId="'.$row['userId'].'" AND itemId="'.$row['itemId'].'"';
			$conn->query($sql2);
			$em->flush();
		}



		echo "stats";

		$sql = 'SELECT ISt.*, I.reference FROM item_statsv2 ISt LEFT JOIN items I ON I.num=ISt.itemId WHERE ISt.processed=0 AND I.reference IS NOT NULL LIMIT 0,50';
		$rows = $conn->query($sql);
		foreach($rows as $row) {
			$reference=$row['reference'];
			if($estate=$em->getRepository('CaravaneEstateBundle:Estate')->findOneByReference($reference)) {
				$days=explode(",",$row['days']);
				$td=(int)date('z');
				foreach($days as $k=>$v) {
					if($k>0 && $v>0) {

						if($k>$td) {//2014
							$date=date_create_from_format('z-Y', $k."-2014");
						}
						else {//2015
							$date=date_create_from_format('z-Y', $k."-2015");
							
						}
						echo $date->format("Y-m-d")."<br/>";
						$estate->addVisit($date->format("Y-m-d"), $v);
						$visits=$estate->getVisits();
						$estate->setDayview($visits['day']);
						$estate->setWeekview($visits['week']);
						$estate->setMonthview($visits['month']);
						$estate->setTotalview($visits['total']);
						$em->persist($estate);
						$em->persist($estate);
					}
				}
			}
			$sql2 = 'UPDATE item_statsv2 SET processed=1 WHERE itemId="'.$row['itemId'].'"';
			$conn->query($sql2);
		}
		
		$em->flush();

    }
}
