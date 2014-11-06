<?php

namespace Caravane\Bundle\EstateBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\Exception\RuntimeException;
use JMS\Serializer\Annotation\ExclusionPolicy;  //Ver 0.11+ the namespace has changed from JMS\SerializerBundle\* to JMS\Serializer\*
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\HttpKernel\Exception\HttpException;
//use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\View as Rest;
use FOS\RestBundle\View\View;


use Caravane\Bundle\EstateBundle\Entity\Estate;




class ApiController extends RestController
{
    /**
     * Get the list of articles
     *
     * @Rest(
    *   serializerGroups={"list"}
    * )
     */
    public function searchAction(Request $request)
    {
        $datas=$_GET;
        if(!isset($datas['location'])) {
            $datas['location']=0;
        }
        //$datas==$request->request;

        $type=($datas['location']==1?'rent':'sale');

        $em = $this->getDoctrine()->getManager();
        if(isset($datas['reference'])) {
            if($datas['reference']!="") {
                return $this->redirect($this->generateUrl('caravane_estate_frontend_estate_'.$type.'_show',array('reference'=>$datas['reference'])));
            }
        }

        if($user=$this->getUser()) {
            if($contact=$user->getContact()) {
                $contact->setLastSearch(json_encode($datas));
                $em->persist($contact);
                $em->flush();
            }
        }

        $estates=$em->getRepository('CaravaneEstateBundle:Estate')->getSearchResult($datas);

        return array('estates' => $estates);

    }
}
