<?php

namespace Caravane\Bundle\EstateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\Serializer\Exception\RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;

class RestController extends Controller
{
    protected function getManager()
    {
        return $this->getDoctrine()->getManager();
    }

    protected function deserialize($class, Request $request, $format = 'json')
    {

        $serializer = $this->get('serializer');
        $validator = $this->get('validator');

        $content=$request->getContent();


        try {
            $entity = $serializer->deserialize($content, $class, $format);
        } catch (RuntimeException $e) {
            throw new HttpException(400, $e->getMessage());
        }

        if (count($errors = $validator->validate($entity))) {
            return $errors;
        }

        return $entity;
    }
}
