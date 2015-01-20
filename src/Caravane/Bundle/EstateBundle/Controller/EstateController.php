<?php

namespace Caravane\Bundle\EstateBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Caravane\Bundle\EstateBundle\Entity\Estate;
use Caravane\Bundle\EstateBundle\Entity\Photo;
use Caravane\Bundle\EstateBundle\Entity\Location;
use Caravane\Bundle\EstateBundle\Entity\Zone;
use Caravane\Bundle\EstateBundle\Entity\Area;
use Caravane\Bundle\EstateBundle\Entity\UserEstate;
use Caravane\Bundle\EstateBundle\Form\EstateType;
use Caravane\Bundle\EstateBundle\Form\SearchType;

/**
 * Estate controller.
 *
 */
class EstateController extends Controller
{

    public function importAction() {

        $em = $this->getDoctrine()->getManager();


/*temp */

        $areas=$em->getRepository('CaravaneEstateBundle:Area')->findAll();
        foreach($areas as $area) {
            $latlng=$area->getLatLng();
            $t=explode(",",$latlng);
            $area->setLat($t[0]);
            $area->setLng($t[1]);
            $em->persist($area);
        }
        $em->flush();

        $categoryMaison=$em->getRepository('CaravaneEstateBundle:Category')->findOneByName('Maison');
        $categoryAppartement=$em->getRepository('CaravaneEstateBundle:Category')->findOneByName('Appartement');
        $categoryAutre=$em->getRepository('CaravaneEstateBundle:Category')->findOneByName('Autre');

        $zone1=$em->getRepository('CaravaneEstateBundle:Zone')->find(1);
        $zone2=$em->getRepository('CaravaneEstateBundle:Zone')->find(2);
        $zone3=$em->getRepository('CaravaneEstateBundle:Zone')->find(3);
        $zone4=$em->getRepository('CaravaneEstateBundle:Zone')->find(4);

/*
        $rs = curl_init();
        curl_setopt($rs,CURLOPT_URL,'http://www.esimmo.com/Virtual/lelion/secteurs.php' );
        curl_setopt($rs,CURLOPT_HEADER,0);
        curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($rs,CURLOPT_FOLLOWLOCATION,1);
        $xml = curl_exec($rs);
        $areasXml = new \SimpleXMLElement($xml);

        foreach($areasXml as $k=>$areaXml) {
        }
*/

        $rs = curl_init();
        //?OxySeleBiensParPage=1000
       // curl_setopt($rs,CURLOPT_URL,'http://www.evosys.be/Virtual/lelion/resultats.php?OxySeleOffr=V' );
        curl_setopt($rs,CURLOPT_URL,'http://www.esimmo.com/Virtual/lelion/carte.php?OxySeleOffr=V&OxySeleBiensParPage=1000' );
        curl_setopt($rs,CURLOPT_HEADER,0);
        curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($rs,CURLOPT_FOLLOWLOCATION,1);
        $xml = curl_exec($rs);

        $estates = new \SimpleXMLElement($xml);

        foreach($estates as $k=>$listEstate) {
            //echo $listEstate->CLAS;
            if(!$estate= $em->getRepository('CaravaneEstateBundle:Estate')->findOneByReference('030/'.$listEstate->CLAS)) {
                $estate=new Estate;
                $estate->setReference('030/'.$listEstate->CLAS);

                $date=date_create_from_format('d/m/Y', $listEstate->MODI_DATE);
                //$datetime = new \DateTime();
                //$datetime->createFromFormat('d/m/Y', $listEstate->MODI_DATE);
                $estate->setCreatedOn($date);
            }


            $estate->setBathrooms(intval($listEstate->BAIN_NBR));

            $geocoder = $this->get('ivory_google_map.geocoder');
            $response = $geocoder->geocode($listEstate->ADRN." ".$listEstate->ADR1.", ".$listEstate->LOCA);

            foreach($response->getResults() as $result)
            {
                if($location=$result->getGeometry()->getLocation()) {
                    $lat=$location->getLatitude();
                    $lng=$location->getLongitude();
                    $estate->setLat($lat);
                    $estate->setLng($lng);

                    $area=$em->getRepository('CaravaneEstateBundle:Area')->getClosestArea($lat,$lng);
                    $estate->setArea($area);
                }

            }


            $rs = curl_init();
            curl_setopt($rs,CURLOPT_URL, 'http://www.esimmo.com/Virtual/lelion/offre.php?OxySeleCode='.$listEstate->CODE);
            curl_setopt($rs,CURLOPT_HEADER,0);
            curl_setopt($rs,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($rs,CURLOPT_FOLLOWLOCATION,1);
            $xml = curl_exec($rs);
            $xmlEstates = new \SimpleXMLElement($xml);
            $xmlEstate=$xmlEstates->OFFRE[0];

            $estate->setPrix($xmlEstate->PRIX);
            $estate->setSummary(substr((string)$xmlEstate->FLASH_FR,0,254));
            $estate->setDescription(strip_tags("<p>".(string)$xmlEstate->FLASH_FR."</p>".(string)$xmlEstate->DESCR_FR),"<p><br><a><i><u><ul><li>");
            $estate->setName($xmlEstate->REFE);
            $estate->setLocation($xmlEstate->OFFR=='V'?0:1);

            if(!$loc=$em->getRepository('CaravaneEstateBundle:Location')->findOneByFr($xmlEstate->COMM)) {
                $loc=new Location();
                $loc->setFr(ucfirst($xmlEstate->COMM));
                $em->persist($loc);
            }
            $estate->setLocFr($loc->getZip()." ".$loc->getFr());
            $estate->setZip(intval($loc->getZip()));

            if(substr($xmlEstate->TABLIMME,0,2)=='01') {
                $category=$categoryAppartement;
            }
            else if(substr($xmlEstate->TABLIMME,0,2)=='02') {
                $category=$categoryMaison;
            }
            else {
                $category=$categoryAutre;
            }
            $estate->setCategory($category);

            $zone=null;
            if(substr($xmlEstate->TABLGEOG,0,2)=='00' || substr($xmlEstate->TABLGEOG,0,2)=='01' || $xmlEstate->TABLGEOG=="0") {
                $zone=$zone1;
            }
            else if(substr($xmlEstate->TABLGEOG,0,2)=='02') {
                $zone=$zone2;
            }
            else if(substr($xmlEstate->TABLGEOG,0,2)=='10') {
                $zone=$zone3;
            }
            else if(substr($xmlEstate->TABLGEOG,0,2)=='11') {
                $zone=$zone4;
            }
            $estate->setZone($zone);

            $estate->setRooms(intval($xmlEstate->CHBR_NBR));
            $estate->setGarages(intval($xmlEstate->VOIT_NBR));
            $estate->setSurface(intval($xmlEstate->SURF_HAB));
            if($xmlEstate->JARD_ON!=0) {
                $garden="Jardin";
            }
            else if(intval($xmlEstate->SUPE_TER)>0) {
                $garden="Terrasse";
            }
            else {
                $garden="";
            }
            $estate->setGarden($garden);



            foreach($estate->getPhoto() as $photo) {
                $estate->removePhoto($photo);
            }
            for($i=1;$i<=20;$i++) {
                $id=($i<10?"0".$i:$i);
                $xmlPhoto="PHOTO_".$id;
                if($xmlUrl=$xmlEstate->$xmlPhoto) {
                    //echo $xmlUrl;
                    if(preg_match("/\//",$xmlUrl)) {
                        $t=explode("/",$xmlUrl);
                        $filename=$t[count($t)-1];
                        if($ch = curl_init($xmlUrl)) {
                            $fp = fopen(__DIR__.'/../../../../../web/photos/big/'.$filename, 'wb');
                            curl_setopt($ch, CURLOPT_FILE, $fp);
                            curl_setopt($ch, CURLOPT_HEADER, 0);
                            curl_exec($ch);
                            curl_close($ch);
                            fclose($fp);
                            if(!$photo=$em->getRepository('CaravaneEstateBundle:Photo')->findOneByFilename($filename)) {
                                $photo= new Photo();
                                $photo->setFilename($filename);
                                $photo->setRanking(intval(substr($filename,0,2)));
                                $photo->setEstate($estate);
                                $em->persist($photo);
                            }

                            echo $filename."<br/>";
                        }

                    }

                }

            }

           // echo $listEstate->MODI_DATE;
           // $datetime = new \DateTime();
           // $datetime->createFromFormat('d/m/Y', $xmlEstate->MODI_DATE);
            $date=date_create_from_format('d/m/Y', $xmlEstate->MODI_DATE);
           // echo " - ".$datetime->format('Y-m-d H:i:s')."<br/>";
           // echo " - ".$date->format('Y-m-d H:i:s')."<br/>";
            $estate->setUpdatedOn($date);

            $em->persist($estate);
        }
        $em->flush();






        return $this->redirect($this->generateUrl('caravane_estate_backend_estate'));

    }


    /**
     * Lists all Estate entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

      /*  $paginator  = $this->get('knp_paginator');

        $entities = $em->getRepository('CaravaneEstateBundle:Estate')->findAll();

        return $this->render('CaravaneEstateBundle:Estate:index.html.twig', array(
            'entities' => $entities,
        ));


*/
    $dql   = "SELECT E FROM CaravaneEstateBundle:Estate E";
    $query = $em->createQuery($dql);

    $paginator  = $this->get('knp_paginator');
    $entities = $paginator->paginate(
        $query,
        $request->query->get('page', 1)/*page number*/,
        25,/*limit per page*/
        array('defaultSortFieldName' => 'E.updatedOn', 'defaultSortDirection' => 'desc')

    );

    // parameters to template
    return $this->render('CaravaneEstateBundle:Estate:index.html.twig', array('entities' => $entities));

    }
    /**
     * Creates a new Estate entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Estate();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('caravane_estate_backend_estate_show', array('id' => $entity->getId())));
        }

        return $this->render('CaravaneEstateBundle:Estate:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Estate entity.
     *
     * @param Estate $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Estate $entity)
    {
        $form = $this->createForm(new EstateType(), $entity, array(
            'action' => $this->generateUrl('caravane_estate_backend_estate_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Sauver'));

        return $form;
    }

    /**
     * Displays a form to create a new Estate entity.
     *
     */
    public function newAction()
    {
        $entity = new Estate();
        $form   = $this->createCreateForm($entity);

        return $this->render('CaravaneEstateBundle:Estate:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Estate entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CaravaneEstateBundle:Estate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Estate entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CaravaneEstateBundle:Estate:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Estate entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CaravaneEstateBundle:Estate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Estate entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CaravaneEstateBundle:Estate:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Estate entity.
    *
    * @param Estate $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Estate $entity)
    {
        $form = $this->createForm(new EstateType(), $entity, array(
            'action' => $this->generateUrl('caravane_estate_backend_estate_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Sauver'));

        return $form;
    }
    /**
     * Edits an existing Estate entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CaravaneEstateBundle:Estate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Estate entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('caravane_estate_backend_estate_edit', array('id' => $id)));
        }

        return $this->render('CaravaneEstateBundle:Estate:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Estate entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CaravaneEstateBundle:Estate')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Estate entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('caravane_estate_backend_estate'));
    }

    /**
     * Creates a form to delete a Estate entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('caravane_estate_backend_estate_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete','attr'=>array('class'=>'btn btn-danger')))
            ->getForm()
        ;
    }





    public function lastUpdatedAction($max=3) {
        $em = $this->getDoctrine()->getManager();

       if(!$user=$this->getUser()) {
             $user=null;
       }
        $estates=$em->getRepository('CaravaneEstateBundle:Estate')->findLastUpdated($max, $user);
         return $this->render('CaravaneEstateBundle:Frontend:Homepage/last_updated.html.twig', array(
            'estates'      => $estates
        ));
    }


    public function searchFormAction(Request $request, $type='sale') {
        $search_form=$this->searchForm($request, $type);
        return $this->render('CaravaneEstateBundle:Estate:search.html.twig', array(
            'search_form'   => $search_form->createView(),
            'type'=>$type
        ));
    }

    public function searchAction(Request $request) {


        if(!$datas=$request->request->get('search_form')) {
            $datas=array('location'=>0);
        }


        $type=($datas['location']==1?'rent':'sale');

        $search_form=$this->searchForm($request, $type);
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
        if(count($estates)<=0 && $request->isXmlHttpRequest()) {
            return new Response('end');
        }
        return $this->render('CaravaneEstateBundle:Frontend:list.html.twig', array(
            'estates'      => $estates,
           'search_form'   => $search_form->createView(),
            'type'=>$type
        ));
    }
/*
     public function searchByAreaAction(Request $request, $type,$id) {
        $em=$this->getDoctrine()->getmanager();
        $estates=$em->getRepository('CaravaneEstateBundle:Estate')->findByArea($id);
         $search_form=$this->searchForm($request, $type);

         return $this->render('CaravaneEstateBundle:Frontend:list.html.twig', array(
            'estates'      => $estates,
           'search_form'   => $search_form->createView(),
            'type'=>$type
        ));
    }
*/
    public function searchByAreasAction(Request $request, $type="sale") {
        $areas=array();
        $em=$this->getDoctrine()->getmanager();
        $estates=$em->getRepository('CaravaneEstateBundle:Estate')->findByAreaGrouped($type);


        foreach($estates as $k=>$area) {
            if($area['latlng']) {
                $tA=explode(',',$area['latlng']);
                $areas[]=array('id'=>$area['id'],'num'=>$area[1],"lat"=>$tA[0],"lng"=>$tA[1]);
            }

        }
        $response = new Response();
        $response->setContent(json_encode($areas));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    public function saleViewAction($reference) {
        $em = $this->getDoctrine()->getManager();
        $estate=$em->getRepository('CaravaneEstateBundle:Estate')->findOneBy(array('reference'=>"030/".$reference,'status'=>true));
        return $this->render('CaravaneEstateBundle:Frontend:one.html.twig', array(
            'estate'      => $estate,
            'type'=>'sale'
        ));
    }

    public function rentViewAction($reference) {
        $em = $this->getDoctrine()->getManager();
        $estate=$em->getRepository('CaravaneEstateBundle:Estate')->findOneBy(array('reference'=>"030/".$reference,'status'=>true));
        return $this->render('CaravaneEstateBundle:Frontend:one.html.twig', array(
            'estate'      => $estate,
            'type'=>'rent'
        ));
    }

    public function saleListAction(Request $request) {

        if(!$datas=$request->query->get('c_s')) {
            $datas=array();
        }
        $em = $this->getDoctrine()->getManager();
        //$estates=$em->getRepository('CaravaneEstateBundle:Estate')->findBy(array("location"=>0,"status"=>true));

        $estates=$em->getRepository('CaravaneEstateBundle:Estate')->getSearchResult($datas, array('location'=>0));
       // echo "----------------------".count($estates)."------------";
       // die();
        if(count($estates)<=0 && $request->isXmlHttpRequest()) {
            return new Response('end');
        }

        return $this->render('CaravaneEstateBundle:Frontend:list.html.twig', array(
            'estates'      => $estates,
            'type'=>'sale'
        ));
    }

    public function rentListAction(Request $request) {

        if(!$datas=$request->request->get('form')) {
            $datas=array();
        }
        $em = $this->getDoctrine()->getManager();
        //$estates=$em->getRepository('CaravaneEstateBundle:Estate')->findBy(array("location"=>true,"status"=>true));
        $estates=$em->getRepository('CaravaneEstateBundle:Estate')->getSearchResult($datas, array('location'=>1));
        if(count($estates)<=0 && $request->isXmlHttpRequest()) {
            return new Response('end');
        }
        return $this->render('CaravaneEstateBundle:Frontend:list.html.twig', array(
            'estates'      => $estates,
            'type'=>'rent'
        ));
    }


    public function newListAction(Request $request) {

        if(!$datas=$request->query->get('c_s')) {
            $datas=array();
        }
        $em = $this->getDoctrine()->getManager();
        //$estates=$em->getRepository('CaravaneEstateBundle:Estate')->findBy(array("location"=>0,"status"=>true));

        $estates=$em->getRepository('CaravaneEstateBundle:Estate')->getSearchResult($datas, array('isNew'=>1));
        if(count($estates)<=0 && $request->isXmlHttpRequest()) {
            return new Response('end');
        }

        return $this->render('CaravaneEstateBundle:Frontend:list.html.twig', array(
            'estates'      => $estates,
            'type'=>'sale'
        ));
    }



    public function detailAction($id) {
        $em = $this->getDoctrine()->getManager();
        if($estate=$em->getRepository('CaravaneEstateBundle:Estate')->find($id)) {
            if($user=$this->getUser()) {
                if(!$ue=$em->getRepository('CaravaneEstateBundle:UserEstate')->findOneBy(
                    array(
                        'estate'=>$estate->getId(),
                        'user'=>$user->getId()
                    )
                )) {
                    $ue= new UserEstate();
                    $ue->setUser($user);
                    $ue->setEstate($estate);
                    $ue->setCounter(0);
                }

                $ue->setCounter($ue->getCounter()+1);
                $ue->setDate(new \Datetime("now"));
                $em->persist($ue);
                $em->flush();
            }
        }


        return $this->render('CaravaneEstateBundle:Frontend:estate_detail.html.twig', array(
            'estate'      => $estate
        ));
    }


    public function addToFavoriteAction($id) {
        $em = $this->getDoctrine()->getManager();
        if($user=$this->getUser()) {
            if($estate=$em->getRepository('CaravaneEstateBundle:Estate')->find($id)) {
                if(!$userEstate=$em->getRepository('CaravaneEstateBundle:UserEstate')->findOneBy(array('user'=>$user,'estate'=>$estate))) {
                    $userEstate=new UserEstate();
                    $userEstate->setUser($user);
                    $userEstate->setEstate($estate);
                }
                if($userEstate->getSaved()==true) {
                    $userEstate->setSaved(false);
                }
                else {
                    $userEstate->setSaved(true);
                }
                $em->persist($userEstate);
                $em->flush();
                return new Response('success');

            }
            else {
                return new Response('no estate');
            }
        }
        return new Response('no user');
    }


    private function searchForm($request, $type='sale') {
        $em = $this->getDoctrine()->getManager();
        $prices=$em->getRepository('CaravaneEstateBundle:Price')->getPrices($type);

        $options=array('prices'=>$prices,'type'=>$type);
        if($type=='rent') {
            $options['location']=1;

        }
        else {
            $options['location']=0;
        }
        $options['isNewBuilding']=0;
         if($type=='new') {
            $options['isNewBuilding']=1;
            $options['location']=0;
        }


        $search_form = $this->createForm( 'search_form', null, $options);
        $search_form->get('location')->setData($options['location']);
        $search_form->add('submit', 'submit', array('label' => 'Rechercher','attr'=>array('class'=>'form-control btn-red')));
        $search_form->handleRequest($request);
        return $search_form;
/*
        $datas=array('location'=>($type=='sale'?0:1),"sort"=>"updatedOn desc");
        if($type=='new') {
            $datas['isNewBuilding']=true;
            $datas['location']=0;
        }
        $em = $this->getDoctrine()->getManager();
        $prices=$em->getRepository('CaravaneEstateBundle:Price')->getPrices($type);



        $form = $this->createFormBuilder($datas)
        ->add('prix','choice', array(
                "label"=>false,
                "expanded"=>true,
                "multiple"=>true,
                'choices' => $prices,
                "attr"=>array(
                    "class"=>"btn-group btn-group-vertical",
                    "data-toggle"=>"buttons"
                )
            ))
            ->add('area',"entity",array(
                "label"=>false,
                "empty_value" => 'Quartier',
                "class"=>"Caravane\Bundle\EstateBundle\Entity\Area"
            ))
            ->add('zone','entity', array(
                "label"=>false,
                "expanded"=>true,
                "multiple"=>true,
                "class"=>"Caravane\Bundle\EstateBundle\Entity\Zone",
                "attr"=>array(
                    "class"=>"btn-group btn-group-vertical",
                    "data-toggle"=>"buttons"
                )
            ))
            ->add("rayon","choice",array(
                "label"=>false,
                "empty_value" => 'Rayon',
                "choices"=>array(
                    "1"=>"1 km",
                    "5"=>"5 km",
                    "10"=>"10 km",
                    "20"=>"20 km",
                    "50"=>"50 km"
                )
            ))
            ->add('reference',"text",array(
                "attr"=>array(
                    "placeholder"=>"Reference"
                )
            ))
             ->add('address',"text",array(
                "attr"=>array(
                    "placeholder"=>"Adresse"
                )
            ))
            ->add('category','entity', array(
                "label"=>false,
                "expanded"=>true,
                "multiple"=>true,
                "class"=>"Caravane\Bundle\EstateBundle\Entity\Category",
                "attr"=>array(
                    "class"=>"btn-group btn-group-vertical",
                    "data-toggle"=>"buttons"
                )
            ))
            ->add('location','hidden')

            ->add('isNewBuilding',($type!='rent'?'checkbox':'hidden'),array(
                "label"=>"Biens neufs uniquement",
                "attr"=>array(
                    "class"=>"btn "
                )
            ))
            ->add('keyword','text',array(
                "attr"=>array(
                    "placeholder"=>"Mot clef (ex.: piscine, brugmann)"
                )
            ))
            ->add('offset','hidden',array(
                "data"=>0))
            ->add('limit','hidden',array(
                "data"=>24,
            ))
            ->add('sort','choice',array(
                "label"=>false,
                "empty_value" => 'Ordonner les résultats par',
                "choices"=>array(
                    "prix asc"=>"Prix croissants",
                    "prix desc"=>"Prix decroissants",
                    "locfr asc"=>"Communes",
                    "updatedOn desc"=>"Nouveautés",
                )
            ))

            ->getForm();
            $form->add('submit', 'submit', array('label' => 'Rechercher','attr'=>array('class'=>'form-control btn-red')));
         //   $form->setMethod('GET');
            $form->handleRequest($request);

            return $form;
            */
    }
}
