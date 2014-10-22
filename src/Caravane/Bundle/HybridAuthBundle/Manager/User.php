<?php
namespace Caravane\Bundle\HybridAuthBundle\Manager;

use \Hybrid_Auth;
use \Hybrid_Endpoint;


use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\Security\Core\Util\SecureRandom;
use FOS\UserBundle\Model\UserInterface;
use Caravane\Bundle\HybridAuthBundle\Event\LoginEvent;


class User {

	private $config;
	private $adapter;
	private $userManager;


	public function __construct($userManager, $config) {
		$this->config=$config;
		$this->userManager=$userManager;
	}

	public function authenticate($provider) {

		$hybridAuth = new Hybrid_Auth($this->config);
		$adapter = $hybridAuth->authenticate($provider);

		if($userProfile = $adapter->getUserProfile()) {
			if($email=$userProfile->email) {
				if(!$user=$this->userManager->findUserByEmail($email)) {

					isset($userProfile->firstname)?$firstname=$userProfile->firstname:$firstname="";
					isset($userProfile->lastname)?$lastname=$userProfile->lastname:$lastname="";
					$generator = new SecureRandom();
					$user = $this->userManager->createUser();
					$user->setEnabled(true);
					call_user_func_array(array($user, "set".ucfirst($provider)."Id"), array($userProfile->identifier));
					$user->setUsername($provider."_".$userProfile->displayName);
					$user->setNickname($userProfile->displayName);
					$user->setEmail($email);
					$user->setPassword( $random = bin2hex($generator->nextBytes(32)) );
					$user->setFirstname($firstname);
					$user->setLastname($lastname);	
					$this->userManager->updateUser($user);

				}
				else {
					call_user_func_array(array($user, "set".ucfirst($provider)."Id"), array($userProfile->identifier));
				}
			}



		}


		$url = $this->config['success_url'];

		return array(
			'url'=>$url,
			'user'=>$user
		);
	}


	public function getUserContacts($provider) {
		$hybridAuth = new Hybrid_Auth($this->config);
		$adapter = $hybridAuth->authenticate($provider);
       	$user_contacts = $adapter->getUserContacts();
       	return $user_contacts;
	}


	public function onLoginEvent($event) {

		$user=$event->getUser();

		$provider=$event->getProvider();
		$providerFriends=$this->getUserContacts($provider);
		foreach($providerFriends as $friend) {
			$providerId=strtolower($provider)."Id";
			if($socialFriend=$this->userManager->findUserBy(array($providerId=>$friend->identifier))) {
			//	if(!$user->hasFriend($socialFriend)) {
					$user->addFriend($socialFriend);
					$socialFriend->addFriend($user);
					$this->userManager->updateUser($socialFriend);
					$this->userManager->updateUser($user);
			//	}
			//	else {
			//		echo $socialFriend->getEmail(). "exists";
			//	}
			}
		}

	}



}
