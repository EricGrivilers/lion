<?php

namespace Caravane\Bundle\EstateBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Caravane\Bundle\EstateBundle\Entity\Estate;
use Caravane\Bundle\EstateBundle\Form\EstateType;

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
}
