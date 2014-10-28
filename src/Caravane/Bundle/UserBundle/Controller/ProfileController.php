<?php

namespace Caravane\Bundle\UserBundle\Controller;


use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Caravane\Bundle\EstateBundle\Form\SearchType;

class ProfileController extends BaseController
{

	/**
	 * Show the user
	 */
	public function showAction()
	{
		$user = $this->getUser();
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}


		if($lastSearch=json_decode($user->getContact()->getLastSearch(), true)) {

			$type= "sale";
			if($lastSearch['location']=='1') {
				$type="rent";
			}

			$em = $this->getDoctrine()->getManager();
			$prices=$em->getRepository('CaravaneEstateBundle:Price')->getPrices($type);

			$last_search_form = $this->createForm( 'search_form', null, array('prices'=>$prices));
			$last_search_form->add('submit', 'submit', array('label' => 'Rechercher','attr'=>array('class'=>'btn-red pull-right')));
			$lastSearch['offset']=0;
			$last_search_form->setData($lastSearch);
			 return $this->render('FOSUserBundle:Profile:show.html.twig', array(
				'user' => $user,
				'last_search_form'=>$last_search_form->createView(),
				'type'=>$type
			));

		}



		return $this->render('FOSUserBundle:Profile:show.html.twig', array(
			'user' => $user
		));
	}

}


