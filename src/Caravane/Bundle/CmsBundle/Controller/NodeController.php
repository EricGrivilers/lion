<?php

namespace Caravane\Bundle\CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Caravane\Bundle\CmsBundle\Entity\Node;
use Caravane\Bundle\CmsBundle\Form\NodeType;

/**
 * Node controller.
 *
 */
class NodeController extends Controller
{


    public function updateWysiwygAction(Request $request,$id)
    {

        $content=$request->query->get('content');
        $em = $this->getDoctrine()->getManager();
        $node=$em->getRepository('CaravaneCmsBundle:Node')->find($id);
        $node->setContent($content);

        $locale=$this->get('translator')->getLocale() ;
        $node->setTranslatableLocale($locale);
        $em->persist($node);
        $em->flush();

        return new Response('ok');
    }

    public function renderAction($uri) {
        $em = $this->getDoctrine()->getManager();
        if(!$node=$em->getRepository('CaravaneCmsBundle:Node')->findOneByUri($uri)) {
            return new Response('');
        }
        return new Response($node->getContent());
    }

    /**
     * Lists all Node entities.
     *
     */
    public function indexAction(Request $request)
    {
        $locale=$this->get('translator')->getLocale() ;

        $this->setUp();
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CaravaneCmsBundle:Node')->findAll();

        return $this->render('CaravaneCmsBundle:Node:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Node entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Node();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('node_show', array('id' => $entity->getId())));
        }

        return $this->render('CaravaneCmsBundle:Node:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Node entity.
     *
     * @param Node $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Node $entity)
    {
        $form = $this->createForm(new NodeType(), $entity, array(
            'action' => $this->generateUrl('node_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Node entity.
     *
     */
    public function newAction()
    {
        $entity = new Node();
        $form   = $this->createCreateForm($entity);

        return $this->render('CaravaneCmsBundle:Node:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Node entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CaravaneCmsBundle:Node')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Node entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CaravaneCmsBundle:Node:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Node entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CaravaneCmsBundle:Node')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Node entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CaravaneCmsBundle:Node:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Node entity.
    *
    * @param Node $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Node $entity)
    {
        $form = $this->createForm(new NodeType(), $entity, array(
            'action' => $this->generateUrl('node_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Node entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CaravaneCmsBundle:Node')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Node entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $locale=$this->get('translator')->getLocale();
            $entity->setTranslatableLocale($locale);

            $em->flush();

            return $this->redirect($this->generateUrl('node_edit', array('id' => $id)));
        }

        return $this->render('CaravaneCmsBundle:Node:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Node entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CaravaneCmsBundle:Node')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Node entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('node'));
    }

    /**
     * Creates a form to delete a Node entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('node_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }


    private function setUp() {
        $uris=array(
            '/baseline',
            '/home/address',
            '/home/presentation',
            '/home/profile/create',
            '/home/app/presentation',
            '/home/partners/leading',
            '/home/partners/knight',
            '/home/social',
            '/title/new-property',
            '/title/presentation',
            '/title/app',
            '/title/partners',
            '/title/follow-us',
            '/title/contact-us',
            '/title/contact',
            '/title/our-coordinates'
        );
        $em = $this->getDoctrine()->getManager();
        foreach($uris as $uri) {
            if(!$node=$em->getRepository('CaravaneCmsBundle:Node')->findOneByUri($uri)) {
                $node =new Node;
                $node->setUri($uri);
                $em->persist($node);
                $em->flush();
            }
        }

    }
}
