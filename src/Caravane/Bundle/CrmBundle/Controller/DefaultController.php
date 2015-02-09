<?php

namespace Caravane\Bundle\CrmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Validator\Constraints\NotBlank;



class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CaravaneCrmBundle:Default:index.html.twig', array('name' => $name));
    }





    public function contactAction(Request $request) {
        $contact_form=$this->contactForm($request);
        if($contact_form->isValid()) {
            $data = $contact_form->getData();
            $message = \Swift_Message::newInstance()
                ->setSubject('Website: Contact')
                ->setFrom('contact@immo-lelion.be')
                ->setTo('contact@immo-lelion.be')
                ->setBody($this->renderView('CaravaneCmsBundle:Frontend:Email/email_contact.txt.twig', array('data' => $data)))
            ;
            if($data['location']!='') {
                $message->setSubject("Website: Demande d'estimation");
            }
            if(!$this->get('mailer')->send($message)) {
                echo "error";
            }
            return $this->render('CaravaneCrmBundle:Frontend:contact.html.twig', array(
               'contact_form'   => null,
            ));
        }
        return $this->render('CaravaneCrmBundle:Frontend:contact.html.twig', array(
           'contact_form'   => $contact_form->createView(),
        ));
    }

    private function contactForm($request) {
        $datas=array();
        $form = $this->createFormBuilder($datas)
        ->add('lastname', 'text',array(
            "label"=>"Nom",
            "required"=>true,
            'constraints' => array(
                new NotBlank()
            )
        ))
        ->add('firstname', 'text',array(
            "label"=>"Prénom",
            "required"=>true,
            'constraints' => array(
                new NotBlank()
            )
        ))
        ->add('email','email',array(
            "label"=>"Email",
            "required"=>true,
            'constraints' => array(
                new NotBlank()
            )
        ))
        ->add('phone','text',array(
            "label"=>"Téléphone",
            "required"=>true,
            'constraints' => array(
                new NotBlank()
            )
        ))
        ->add('address','text',array("label"=>"Adresse","required"=>false))
        ->add('zip','text',array("label"=>"Code postal","required"=>false))
        ->add('city','text',array("label"=>"Ville", "required"=>false))
        ->add('country','country',array(
            "required"=>false,
            "label"=>"Pays",
            "data"=>"BE",
            'preferred_choices' => array('BE','FR','NL','GB','IT','ES','DE','LU','PT','CH','MC','US','CA','IE','GR','AT'),
        ))
        ->add('location','text',array(
            "required"=>false,
            "label"=>"Pour une demande d'estimation, veuillez préciser la localisation du bien :"
        ))
        ->add('comments','textarea',array("label"=>"Message","required"=>false))
        ->getForm();
        $form->add('submit', 'submit', array('label' => 'Envoyer','attr'=>array('class'=>'form-control btn-red')));
         //   $form->setMethod('GET');
        $form->handleRequest($request);

        return $form;
    }

/*
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
*/
}
