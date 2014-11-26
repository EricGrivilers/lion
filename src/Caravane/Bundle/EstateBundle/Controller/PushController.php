<?php

namespace Caravane\Bundle\EstateBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use RMS\PushNotificationsBundle\Message\iOSMessage;

class PushController extends Controller
{
    public function pushAction()
    {
        $message = new iOSMessage();
        $message->setMessage('Oh my! A push notification!');
        $message->setDeviceIdentifier('d8ab431cd0d12cc2e4f7fe39437bdabbd51824b0');

        if($s=$this->container->get('rms_push_notifications')->send($message)) {


	        $feedbackService = $this->container->get("rms_push_notifications.ios.feedback");
			$uuids = $feedbackService->getDeviceUUIDs();



	        return new Response('Push notification send!');
	    }
	    else {
	    	var_dump($s);
	    	return new Response('error!');
	    }
    }
}

