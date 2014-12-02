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
use Caravane\Bundle\CrmBundle\Entity\Contact;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;




class ApiController extends RestController
{


    public function loginAction(Request $request)
    {

       // var_dump($request->headers);
        $username = $request->request->get('_username');
        $password = $request->request->get('_password');

        $user_manager = $this->get('fos_user.user_manager');
        $factory = $this->get('security.encoder_factory');
        $user = $user_manager->loadUserByUsername($username);
        $encoder = $factory->getEncoder($user);
        $bool = ($encoder->isPasswordValid($user->getPassword(),$password,$user->getSalt())) ? "true" : "false";

        return new JsonResponse();
    }


    public function recoverAction(Request $request)
    {



        $username = $request->request->get('username');





        /** @var $user UserInterface */
        $user = $this->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);

        if (null === $user) {
            return $this->render('FOSUserBundle:Resetting:request.html.twig', array(
                'invalid_username' => $username
            ));
        }

        if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            return array('error'=>"already requested");
        }

        if (null === $user->getConfirmationToken()) {
            /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
            $tokenGenerator = $this->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $this->get('fos_user.mailer')->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->get('fos_user.user_manager')->updateUser($user);

         $email = $user->getEmail();
        if (false !== $pos = strpos($email, '@')) {
            $email = '...' . substr($email, $pos);
        }
        return array('email' => $email);
    }

     public function registerAction(Request $request)
    {


        $data=$_POST;

        $user_manager = $this->get('fos_user.user_manager');
        if($user = $user_manager->findUserByEmail($data['email'])) {
            return array('error'=>"user exists");
        }
        if($data['first']!=$data['second'] || $data['first']=='' || $data['second']=='') {
            return array('error'=>"wrong password");
        }
        $username=$data['email'];
        $password=$data['first'];

        $user = $user_manager->createUser();
        $user->setUsername($username);
        $user->setEmail($data['email']);
        $user->setPlainPassword($password);
        $user->addRole("ROLE_USER");

        $em = $this->getDoctrine()->getManager();
        $contact = new Contact();
        $contact->setLanguage($data['language']);
        $contact->setSalutation($data['salutation']);
        $contact->setFirstname($data['firstname']);
        $contact->setLastname($data['lastname']);
        $contact->setNumber($data['number']);
        $contact->setStreet($data['street']);
        $contact->setCity($data['city']);
        $contact->setZip($data['zip']);
        $contact->setTel($data['tel']);
        $contact->setFax($data['fax']);
        $contact->setCountry($data['country']);
        $em->persist($contact);
        $em->flush();

        $user->setContact($contact);
        $user_manager->updateUser($user);


        $jwtManager=$this->get('lexik_jwt_authentication.jwt_manager');
        //$user = $token->getUser();
        $jwt  = $jwtManager->create($user);


//        $client = static::createClient();
//        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return array('token'=>$jwt);


    }

    public function getUserAction() {
        //$user_manager = $this->get('fos_user.user_manager');
        //$factory = $this->get('security.encoder_factory');
        $user = $this->getUser();
        return array('user' => $user);
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

        $datas=$_POST;



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
/*
        if(!isset($datas['sortby'])) {
            $datas['sortby']="updatedOn desc";
        }
*/
        if($datas['sort']=='') {
            unset($datas['sort']);
        }

        if($datas['category']!='') {
            $d=explode(",",$datas['category']);
            $datas['category']=array_filter($d, function($k) {
                return $k>0;
            });
        }else {
            unset($datas['category']);
        }

        if($datas['zone']!='') {
            $d=explode(",",$datas['zone']);
            $datas['zone']=array_filter($d, function($k) {
                return $k>0;
            });

        }else {
            unset($datas['zone']);
        }

        if($datas['area']=='') {
            unset($datas['area']);

        }

        if($datas['prix']!='') {
            $d=explode(",",$datas['prix']);
            $datas['prix']=array_filter($d, function($k) {
                return ($k!='' && $k!=false);
            });
        }else {
            unset($datas['prix']);
        }





        $em = $this->getDoctrine()->getManager();
       /* if(isset($datas['reference'])) {
            if($datas['reference']!="") {
                return $this->redirect($this->generateUrl('caravane_estate_frontend_estate_'.$type.'_show',array('reference'=>$datas['reference'])));
            }
        }
        */

        if($user=$this->getUser()) {
            if($contact=$user->getContact()) {
                $contact->setLastSearch(json_encode($datas));
                $em->persist($contact);
                $em->flush();
            }
        }

        $estates=$em->getRepository('CaravaneEstateBundle:Estate')->getSearchResult($datas);

        //if(isset($datas['search_form'])) {
            $search_form=$this->getForm($type);
            return array(
                'estates' => $estates,
                'search_form' => $search_form
            );
        //}
        return array('estates' => $estates);

    }



    public function getPictAction(Request $request, $filter="thumbnail_medium", $relativePath) {

        $absolutePath = __DIR__."/../../../../../web/".$relativePath;

        if(file_exists($absolutePath) && !is_dir($absolutePath) ) {
            $url = $this->get('liip_imagine.cache.manager')->getBrowserPath("/".$relativePath, $filter);
        }
        else {
            $url = $this->get('liip_imagine.cache.manager')->getBrowserPath("/photos/big/dummy.png", $filter);
        }
        return $this->redirect($url);


    }

    public function getHomeAction(Request $request) {
        $max=6;
         $em = $this->getDoctrine()->getManager();

           if(!$user=$this->getUser()) {
                 $user=null;
           }
            $estates=$em->getRepository('CaravaneEstateBundle:Estate')->findLastUpdated($max, $user);
             return array(
                'estates'      => $estates
            );


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
            case "rent":
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
