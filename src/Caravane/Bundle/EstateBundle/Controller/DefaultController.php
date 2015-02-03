<?php

namespace Caravane\Bundle\EstateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Caravane\Bundle\CrmBundle\Entity\Contact;
use Symfony\Component\Security\Core\Util\SecureRandom;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CaravaneEstateBundle:Default:index.html.twig', array('name' => $name));
    }



    public function installAction() {
		$userManager = $this->get('fos_user.user_manager');
		$em = $this->getDoctrine()->getManager();

    		$conn = $this->get('database_connection');
		$sql = 'SELECT * FROM User';
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
			}
		}
		$em->flush();
    }
}
