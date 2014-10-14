<?php

namespace Caravane\Bundle\EstateBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Caravane\Bundle\EstateBundle\Entity\Estate;
use Caravane\Bundle\EstateBundle\Form\EstateType;
use Caravane\Bundle\EstateBundle\Form\SearchType;

/**
 * Estate controller.
 *
 */
class EstateController extends Controller
{

    /**
     * Lists all Estate entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $paginator  = $this->get('knp_paginator');

        $entities = $em->getRepository('CaravaneEstateBundle:Estate')->findAll();

        return $this->render('CaravaneEstateBundle:Estate:index.html.twig', array(
            'entities' => $entities,
        ));
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

        $form->add('submit', 'submit', array('label' => 'Create'));

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

        $form->add('submit', 'submit', array('label' => 'Update'));

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
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }





    public function lastUpdatedAction($max=3) {
        $em = $this->getDoctrine()->getManager();
        $estates=$em->getRepository('CaravaneEstateBundle:Estate')->findLastUpdated($max);
         return $this->render('CaravaneEstateBundle:Frontend:Homepage/last_updated.html.twig', array(
            'estates'      => $estates
        ));
    }


    public function searchFormAction(Request $request, $oRequest) {
        $search_form=$this->searchForm($oRequest);
        return $this->render('CaravaneEstateBundle:Estate:search.html.twig', array(
            'search_form'   => $search_form->createView()
        ));
    }

    public function searchAction(Request $request) {

        if(!$datas=$request->request->get('form')) {
            $datas=array('location'=>0);
        }

        $type=($datas['location']==1?'rent':'sale');
        $em = $this->getDoctrine()->getManager();
        if(isset($datas['reference'])) {
            if($datas['reference']!="") {
                return $this->redirect($this->generateUrl('caravane_estate_frontend_estate_'.$type.'_show',array('reference'=>$datas['reference'])));
            }
        }


        $estates=$em->getRepository('CaravaneEstateBundle:Estate')->getSearchResult($datas);
        if(count($estates)<=0) {
            return new Response('end');
        }
        return $this->render('CaravaneEstateBundle:Frontend:list.html.twig', array(
            'estates'      => $estates
        ));
    }

    




    public function saleViewAction($reference) {
        $em = $this->getDoctrine()->getManager();
        $estate=$em->getRepository('CaravaneEstateBundle:Estate')->findOneBy(array('reference'=>"030/".$reference,'status'=>true));
        return $this->render('CaravaneEstateBundle:Frontend:one.html.twig', array(
            'estate'      => $estate
        ));
    }

    public function rentViewAction($reference) {
        $em = $this->getDoctrine()->getManager();
        $estate=$em->getRepository('CaravaneEstateBundle:Estate')->findOneBy(array('reference'=>"030/".$reference,'status'=>true));
        return $this->render('CaravaneEstateBundle:Frontend:one.html.twig', array(
            'estate'      => $estate
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
        if(count($estates)<=0) {
            return new Response('end');
        }

        return $this->render('CaravaneEstateBundle:Frontend:list.html.twig', array(
            'estates'      => $estates
        ));
    }

    public function rentListAction(Request $request) {

        if(!$datas=$request->request->get('form')) {
            $datas=array();
        }
        $em = $this->getDoctrine()->getManager();
        //$estates=$em->getRepository('CaravaneEstateBundle:Estate')->findBy(array("location"=>true,"status"=>true));
        $estates=$em->getRepository('CaravaneEstateBundle:Estate')->getSearchResult($datas, array('location'=>1));
        if(count($estates)<=0) {
            return new Response('end');
        }
        return $this->render('CaravaneEstateBundle:Frontend:list.html.twig', array(
            'estates'      => $estates
        ));
    }

    public function detailAction($id) {
        $em = $this->getDoctrine()->getManager();
        $estate=$em->getRepository('CaravaneEstateBundle:Estate')->find($id);
        return $this->render('CaravaneEstateBundle:Frontend:estate_detail.html.twig', array(
            'estate'      => $estate
        ));
    }




    public function searchForm($request) {



        $datas=array('location'=>0);
        $em = $this->getDoctrine()->getManager();
        $prices=$em->getRepository('CaravaneEstateBundle:Price')->getPrices();



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
            ->add('reference',"text",array(
                "attr"=>array(
                    "placeholder"=>"Reference"
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
            ->add('location','choice', array(
                "label"=>false,
                "expanded"=>true,
                "multiple"=>false,
                "data"=>0,
                "choices"=>array(
                    "0"=>"Vente",
                    "1"=>"Location"
                ),
                "attr"=>array(
                    "class"=>"btn-group btn-group-justified",
                    "data-toggle"=>"buttons"
                )
            ))
            ->add('isNew','checkbox',array(
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
                "label"=>"Ordonner les résultats par",
                "choices"=>array(
                    "prix asc"=>"Prix croissants",
                    "prix desc"=>"Prix decroissants",
                    "locfr asc"=>"Communes",
                    "updatedOn desc"=>"Nouveautés",
                )
            ))

            ->getForm();
            $form->add('submit', 'submit', array('label' => 'Search'));
         //   $form->setMethod('GET');
            $form->handleRequest($request);

            return $form;
    }
}
