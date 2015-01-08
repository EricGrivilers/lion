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
use Caravane\Bundle\EstateBundle\Entity\UserEstate;
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
        $user->setEnabled(true);
        $user->setPlainPassword($password);
        $user->addRole("ROLE_USER");
        $user_manager->updateUser($user);
        $em->flush();


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

    public function updateUserAction(Request $request)
    {


        $data=$_POST;

        $user_manager = $this->get('fos_user.user_manager');
        //f(!$user = $user_manager->findUserByEmail($data['email'])) {
        if(!$user = $this->getUser()) {
            return array('error'=>"user doesn't exists");
        }

        if(!$contact = $user->getContact()) {
            $contact=new Contact();
        }

        $em = $this->getDoctrine()->getManager();

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

        return array('success'=>'ok');


    }


     public function facebookAction(Request $request)
    {


        $data=$_POST;

        $user_manager = $this->get('fos_user.user_manager');
        if(!$user = $user_manager->findUserByEmail($data['email'])) {
            $user = $user_manager->createUser();
            $user->setUsername($data['email']);
            $user->setEmail($data['email']);
            $user->setEnabled(true);
            $user->setPlainPassword(   substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8) );
            $user->addRole("ROLE_USER");
            $user_manager->updateUser($user);
        }
        if(!$contact = $user->getContact()) {
            $contact=new Contact();
            $l=explode('_',$data['language']);
            $contact->setLanguage($l[0]);
            $contact->setSalutation($data['gender']=='male'?"M":"Mme");
            $contact->setFirstname($data['firstname']);
            $contact->setLastname($data['lastname']);
            $em->persist($contact);
            $em->flush();
            $user->setContact($contact);
        }

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
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $last_search=array();
        $estates=array();
        if($contact=$user->getContact()) {
            $last_search=array("contact"=>true);
            if($search=$contact->getLastSearch()) {
                $last_search=array("last_search"=>true);
                if($searchObj=json_decode($search, true)) {
                    $last_search=$searchObj;
                    $datas=$last_search;
                    //$estates=$em->getRepository('CaravaneEstateBundle:Estate')->getSearchResult($datas);;
                    $datas['limit']=1000;
                    $estates=$em->getRepository('CaravaneEstateBundle:Estate')->getSearchResult($datas);

                }
            }
        }
        return array('user' => $user, 'last_search'=>$last_search, 'last_search_count'=>count($estates));
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

        $datas=$this->parseSearch($_POST, $type);

        $em = $this->getDoctrine()->getManager();
        if($user=$this->getUser()) {
            //unset($datas['save']);
            if($contact=$user->getContact() ) {
                if($datas['save']=='true') {
                    $contact->setLastSearch(json_encode($datas));
                    $em->persist($contact);
                    $em->flush();
                }
            }
        }
        $estates=$em->getRepository('CaravaneEstateBundle:Estate')->getSearchResult($datas);
        $search_form=$this->getForm($type);
        return array(
            'estates' => $estates,
            'search_form' => $search_form,
        );
        //}
        return array('estates' => $estates);

    }


    public function searchOwnAction(Request $request)
    {
        if (!$this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        
        $estates=array();
        $em = $this->getDoctrine()->getManager();
        $type="vente";
        if($user=$this->getUser()) {
            //unset($datas['save']);
            if($contact=$user->getContact()) {
                $last_search=array("contact"=>true);
                if($search=$contact->getLastSearch()) {
                    $last_search=array("last_search"=>true);
                    if($searchObj=json_decode($search, true)) {

                        $last_search=$searchObj;
                        $datas=$last_search;
                        $type=$datas['location']==1?'vente':'location';
                        //$estates=$em->getRepository('CaravaneEstateBundle:Estate')->getSearchResult($datas);;
                        $datas['limit']=24;
                        $datas['offset']=$_POST['offset'];
                        $estates=$em->getRepository('CaravaneEstateBundle:Estate')->getSearchResult($datas);

                    }
                }
            }
        }
        $search_form=$this->getForm($type);
        return array(
            'estates' => $estates,
            'search_form' => $search_form
        );
        //}
        return array('estates' => $estates);

    }


    public function searchAroundAction(Request $request) {
        if (!$this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }
        $search_form=$this->getForm($type);
        $datas=$this->parseSearch($_POST);
        if($_POST['pos']!='') {
            $datas['pos']=$_POST['pos'];
            $estates=$em->getRepository('CaravaneEstateBundle:Estate')->getSearchResult($datas);
            return array(
                'estates' => $estates,
                'search_form' => $search_form
            );
        }
        return array('estates'=>array(), 'search_form' => $search_form);
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
        $estates=$em->getRepository('CaravaneEstateBundle:Estate')->findLastUpdated($max);
            return array(
            'estates'      => $estates
        );
        


    }

     public function getUserHomeAction(Request $request) {
        $max=6;
        $em = $this->getDoctrine()->getManager();
        if (!$this->get('security.context')->isGranted('ROLE_USER')) {
            $estates=$em->getRepository('CaravaneEstateBundle:Estate')->findLastUpdated($max);
                 return array(
                'estates'      => $estates
            );
        }
        $user=null;
        $estates=array();
        if($user=$this->getUser()) {
                //unset($datas['save']);
                if($contact=$user->getContact()) {
                    if($search=$contact->getLastSearch()) {
                        if($searchObj=json_decode($search, true)) {
                            $last_search=$searchObj;
                            $datas=$last_search;
                            $type=$datas['location']==1?'vente':'location';
                            //$estates=$em->getRepository('CaravaneEstateBundle:Estate')->getSearchResult($datas);;
                            $datas['limit']=8;
                            $datas['offset']=0;
                            $estates=$em->getRepository('CaravaneEstateBundle:Estate')->getSearchResult($datas);

                        }
                    }
                }
        }
        
        if(count($estates)<=0) {
            $estates=$em->getRepository('CaravaneEstateBundle:Estate')->findLastUpdated($max);
        }
       
        return array(
            'estates'      => $estates
        );


    }


    public function addEstateAction($id) {
         $em = $this->getDoctrine()->getManager();

        if($user=$this->getUser()) {
            if($estate=$em->getRepository('CaravaneEstateBundle:Estate')->find($id)) {
                if(!$userEstate=$em->getRepository('CaravaneEstateBundle:UserEstate')->findOneBy(array('user'=>$user,'estate'=>$estate))) {
                    $userEstate=new UserEstate();
                    $userEstate->setUser($user);
                    $userEstate->setEstate($estate);
                }
                $message="viewed";
                if($_POST['save']=="true") {
                    if($userEstate->getSaved()==true) {
                        $userEstate->setSaved(false);
                        $message="unsaved";
                    }
                    else {
                        $userEstate->setSaved(true);
                        $message="saved";
                    }

                }
                $em->persist($userEstate);
                $em->flush();
                return array('success'=>'ok','message'=>$message,'post'=>$_POST['save']);
            }
            return array('error'=>'no estate');
        }
        return array('error'=>'no user');

    }


    public function getForm($type="vente") {


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
            "0.5"=>"500 m",
            "01"=>"1 km",
            "02"=>"2 km",
            "05"=>"5 km",
            "10"=>"10 km"
        );

        $search_form['sort']=array(
            "prix asc"=>"Prix croissants",
            "prix desc"=>"Prix decroissants",
            "locfr asc"=>"Communes",
            "updatedOn desc"=>"Nouveautés",
        );

        return $search_form;
    }




    private function parseSearch($datas, $type="sale") {
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
            if(count($datas['category'])==0) {
               unset($datas['category']);
            }


        }else {
            unset($datas['category']);
        }

        if($datas['zone']!='') {
            $d=explode(",",$datas['zone']);
            $datas['zone']=array_filter($d, function($k) {
                return $k>0;
            });
            if(count($datas['zone'])<=0) {
                unset($datas['zone']);
            }

        }else {
            unset($datas['zone']);
        }

        if($datas['address']!="") {
            $datas['around']=0;
            $geocoder = $this->get('ivory_google_map.geocoder');
                $response = $geocoder->geocode($datas['address'].", bruxelles");
                foreach($response->getResults() as $result)
                {
                    if($location=$result->getGeometry()->getLocation()) {
                        $lat=$location->getLatitude();
                        $lng=$location->getLongitude();
                    }

                }
            $datas['latlng']=$lat.",".$lng;
        }
        else {
            unset($datas['address']);
        }
        if($datas['latlng']=='') {
            unset($datas['latlng']);
        }
        if(!isset($datas['around'])) {
            $datas['around']=0;
        }
        else {
            if($datas['around']!=true || $datas['around']!=1) {
                $datas['around']=0;
            }
        }

        if($datas['area']=='') {
            unset($datas['area']);

        }

        if($datas['prix']!='') {
            $d=explode(",",$datas['prix']);
            $datas['prix']=array_filter($d, function($k) {
                return ($k!='' && $k!=false);
            });
             if(count($datas['prix'])<=0) {
                unset($datas['prix']);
            }
        }else {
            unset($datas['prix']);
        }

        return $datas;
    }




}
