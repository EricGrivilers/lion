<?php

namespace Caravane\Bundle\EstateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CaravaneEstateBundle:Default:index.html.twig', array('name' => $name));
    }
}
