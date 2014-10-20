<?php

namespace Caravane\Bundle\HybridAuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Hybrid_Auth;
use \Hybrid_Endpoint;


use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;


use Caravane\Bundle\HybridAuthBundle\Event\LoginEvent;

class AuthController extends Controller {

	private $config;

	public function endpointAction() {
		$endPoint = new Hybrid_Endpoint();
		$endPoint->process();
	}


	public function authAction(Request $request, $provider) {

		//$provider=ucfirst($provider);

		$hasUser = $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED');

		$this->config=$this->container->getParameter('caravane_hybrid_auth.config');

		if ($hasUser) {
			return new RedirectResponse($this->generateUrl($this->config['success_url']));
		}

		if(!array_key_exists($provider,$this->config['providers'])) {
			 throw new NotFoundHttpException(sprintf('The  provider "%s" does not exist', $provider));
		}

		$manager=$this->container->get('caravane_hybrid_auth.user');
		$result=$manager->authenticate($provider);

		
		if($user=$result['user']) {
			$token = new UsernamePasswordToken($user, $user->getPassword(), "main", $user->getRoles());
			$this->get("security.context")->setToken($token);
			$event = new InteractiveLoginEvent($request, $token);
			$this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

			$dispatcher = $this->container->get('event_dispatcher');
			$event = new GetResponseUserEvent($user, $request);
        	$dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRM, $event);
        	$loginEvent = new LoginEvent($user, $provider);
        	$dispatcher->dispatch('caravane_hybrid_auth.events.login', $loginEvent);
		}
		return $this->redirect($this->generateUrl($this->config['success_url']));
	}




}
