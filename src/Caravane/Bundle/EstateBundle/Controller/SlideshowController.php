<?php

namespace Caravane\Bundle\EstateBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Caravane\Bundle\EstateBundle\Entity\Slideshow;
use Caravane\Bundle\EstateBundle\Form\SlideshowType;

/**
 * Slideshow controller.
 *
 */
class SlideshowController extends Controller
{

    public function renderAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('CaravaneEstateBundle:Slideshow')->findAll();
        return $this->render('CaravaneCmsBundle:Frontend:Widget/slider.html.twig', array(
            'slides' => $entities,
        ));
    }
    /**
     * Lists all Slideshow entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CaravaneEstateBundle:Slideshow')->findAll();

        return $this->render('CaravaneEstateBundle:Slideshow:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Slideshow entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Slideshow();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $entity->getPhoto()->setUploadDir('photos/slide');
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('slideshow', array('id' => $entity->getId())));
        }

        return $this->render('CaravaneEstateBundle:Slideshow:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Slideshow entity.
     *
     * @param Slideshow $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Slideshow $entity)
    {
        $form = $this->createForm(new SlideshowType(), $entity, array(
            'action' => $this->generateUrl('slideshow_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Slideshow entity.
     *
     */
    public function newAction()
    {
        $entity = new Slideshow();
        $form   = $this->createCreateForm($entity);

        return $this->render('CaravaneEstateBundle:Slideshow:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Slideshow entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CaravaneEstateBundle:Slideshow')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Slideshow entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CaravaneEstateBundle:Slideshow:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Slideshow entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CaravaneEstateBundle:Slideshow')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Slideshow entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CaravaneEstateBundle:Slideshow:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Slideshow entity.
    *
    * @param Slideshow $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Slideshow $entity)
    {
        $form = $this->createForm(new SlideshowType(), $entity, array(
            'action' => $this->generateUrl('slideshow_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Slideshow entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CaravaneEstateBundle:Slideshow')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Slideshow entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('slideshow_edit', array('id' => $id)));
        }

        return $this->render('CaravaneEstateBundle:Slideshow:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Slideshow entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CaravaneEstateBundle:Slideshow')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Slideshow entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('slideshow'));
    }

    /**
     * Creates a form to delete a Slideshow entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('slideshow_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
