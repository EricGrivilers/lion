<?php

namespace Caravane\Bundle\CrmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CaravaneCrmBundle:Default:index.html.twig', array('name' => $name));
    }




    public function migrateAction() {
    	die();
    		$em = $this->getDoctrine()->getManager();

    		$userManager = $this->container->get('fos_user.user_manager');

    		$contacts=$em->getRepository("CaravaneCrmBundle:Contact")->findAll();


    		foreach($contacts as $contact) {

    			if(!$user=$userManager->findUserByEmail($contact->getEmail())) {

    				if($contact->getEmail()!='' && $contact->getPassword()!='') {

	    				echo $contact->getEmail()." / ".$contact->getPassword()."<br/>";
	    				$user=$userManager->createUser();
	    				$user->setUsername($contact->getEmail());
		    			$user->setEmail($contact->getEmail());
		    			$user->setPlainPassword($contact->getPassword());

		    			$user->setEnabled(true);
		    			$userManager->updateUser($user);



		    			$contact->setUser($user);
		    			$em->persist($contact);
	    			}
    			}




    		}
    		$em->flush();
    }
}
