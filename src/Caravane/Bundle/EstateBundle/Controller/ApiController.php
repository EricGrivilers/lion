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
use Symfony\Component\HttpFoundation\JsonResponse;

use Caravane\Bundle\EstateBundle\Entity\Estate;
use Caravane\Bundle\EstateBundle\Form\SearchType;




class ApiController extends RestController
{


    public function loginAction(Request $request)
    {

        var_dump($request->headers);
        $username = $request->request->get('_username');
        $password = $request->request->get('_password');

        $user_manager = $this->get('fos_user.user_manager');
        $factory = $this->get('security.encoder_factory');
        $user = $user_manager->loadUserByUsername($username);
        $encoder = $factory->getEncoder($user);
        $bool = ($encoder->isPasswordValid($user->getPassword(),$password,$user->getSalt())) ? "true" : "false";

        return new JsonResponse();
    }



    /**
     * Get the list of articles
     *
     * @Rest(
    *   serializerGroups={"list"}
    * )
     */
    public function searchAction(Request $request, $type)
    {
        if (!$this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $datas=$_GET;
        if(!isset($datas['location'])) {
            $datas['location']=0;
        }
        //$datas==$request->request;

        //$type=($datas['location']==1?'rent':'sale');
        if($type=="rent") {
            $datas['location']=1;
        }
        if($type=="new") {
            $datas['location']=0;
            $datas['isNewBuilding']=1;
        }

        if(!isset($datas['sortby'])) {
            $datas['sortby']="updatedOn desc";
        }

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

        if(isset($datas['search_form'])) {
            $search_form=$this->getForm($type);
            return array(
                'estates' => $estates,
                'search_form' => $search_form
            );
        }
        return array('estates' => $estates);

    }



    public function getPictAction(Request $request, $filter="thumbnail_medium", $relativePath) {
        $url = $this->get('liip_imagine.cache.manager')->getBrowserPath("/".$relativePath, $filter);
        echo $url;
       //die();
        return $this->redirect($url);


    }

    public function getForm($type) {
        $em = $this->getDoctrine()->getManager();
        $search_form=array();
        switch($type) {
            default:
            case "vente":
                $ntype="sale";
            break;
            case "location":
                $ntype="rent";
            break;
        }

        $prices=$em->getRepository('CaravaneEstateBundle:Price')->getPrices($ntype);
        $search_form['prix']=$prices;

        $categories=$em->getRepository('CaravaneEstateBundle:Category')->findAll(array(),array('name'=>"asc"));
        $search_form['category']=array();
        foreach($categories as $cat) {
            $search_form['category'][]=array("id"=>$cat->getId(),"name"=>$cat->getName());
        }
       

        $zones=$em->getRepository('CaravaneEstateBundle:Zone')->findAll(array(),array('name'=>"asc"));
        $search_form['zone']=array();
        foreach($zones as $zone) {
            $search_form['zone'][]=array("id"=>$zone->getId(),"name"=>$zone->getName());
        }

        $areas=$em->getRepository('CaravaneEstateBundle:Area')->findAll(array(),array('name'=>"asc"));
        $search_form['area']=array();
        foreach($areas as $area) {
            $search_form['area'][]=array("id"=>$area->getId(),"name"=>$area->getName());
        }

        $search_form['rayon']=array(
            "1"=>"1 km",
            "5"=>"5 km",
            "10"=>"10 km",
            "20"=>"20 km",
            "50"=>"50 km"
        );

        $search_form['sort']=array(
            "prix asc"=>"Prix croissants",
            "prix desc"=>"Prix decroissants",
            "locfr asc"=>"Communes",
            "updatedOn desc"=>"Nouveautés",
        );

        return $search_form;
    }
}
