<?php

namespace Caravane\Bundle\CrmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CaravaneCrmBundle:Default:index.html.twig', array('name' => $name));
    }

    public function contactAction(Request $request) {
        $contact_form=$this->contactForm($request);
        return $this->render('CaravaneCrmBundle:Frontend:contact.html.twig', array(
           'contact_form'   => $contact_form->createView(),
        ));
    }

    private function contactForm($request) {
        $datas=array();
        $form = $this->createFormBuilder($datas)
        ->add('lastname', 'text',array(
            "label"=>"Nom",
            "required"=>true
        ))
        ->add('firstname', 'text',array(
            "label"=>"Prénom",
            "required"=>true
        ))
        ->add('email','email',array(
            "label"=>"Email",
            "required"=>true
        ))
        ->add('phone','text',array(
            "label"=>"Téléphone",
            "required"=>true
        ))
        ->add('address','text',array("label"=>"Adresse"))
        ->add('zip','text',array("label"=>"Code postal"))
        ->add('city','text',array("label"=>"Ville"))
        ->add('country','country',array(
            "label"=>"Pays",
            "data"=>"BE",
            'preferred_choices' => array('BE','FR','NL','GB','IT','ES','DE','LU','PT','CH','MC','US','CA','IE','GR','AT'),
            ))
        ->add('location','text',array(
            "label"=>"Pour une demande d'estimation, veuillez préciser la localisation du bien :"
        ))
        ->add('comments','textarea',array("label"=>"Message"))
        ->getForm();
            $form->add('submit', 'submit', array('label' => 'Envoyer','attr'=>array('class'=>'form-control btn-red')));
         //   $form->setMethod('GET');
            $form->handleRequest($request);

            return $form;
    }


    public function migrateAction() {

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
