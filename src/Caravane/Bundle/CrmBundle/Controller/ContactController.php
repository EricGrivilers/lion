<?php

namespace Caravane\Bundle\CrmBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Caravane\Bundle\CrmBundle\Entity\Contact;
use Caravane\Bundle\CrmBundle\Form\ContactType;

/**
 * Contact controller.
 *
 */
class ContactController extends Controller
{

    /**
     * Lists all Contact entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

       /* $dql   = "SELECT E FROM CaravaneCrmBundle:Contact E";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $entities = $paginator->paginate(
                $query,
                $request->query->get('page', 1),
                25,
                array('defaultSortFieldName' => 'E.createdOn', 'defaultSortDirection' => 'desc')

        );
        */
        $entities=$em->getRepository('CaravaneCrmBundle:Contact')->findAll();
        // parameters to template
        return $this->render('CaravaneCrmBundle:Contact:index.html.twig', array('entities' => $entities));



    }
    /**
     * Creates a new Contact entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Contact();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('_crm_contact_show', array('id' => $entity->getId())));
        }

        return $this->render('CaravaneCrmBundle:Contact:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Contact entity.
     *
     * @param Contact $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Contact $entity)
    {
        $form = $this->createForm(new ContactType(), $entity, array(
            'action' => $this->generateUrl('caravane_crm_contact_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Contact entity.
     *
     */
    public function newAction()
    {
        $entity = new Contact();
        $form   = $this->createCreateForm($entity);

        return $this->render('CaravaneCrmBundle:Contact:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Contact entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CaravaneCrmBundle:Contact')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CaravaneCrmBundle:Contact:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Contact entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CaravaneCrmBundle:Contact')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CaravaneCrmBundle:Contact:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Contact entity.
    *
    * @param Contact $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Contact $entity)
    {
        $form = $this->createForm(new ContactType(), $entity, array(
            'action' => $this->generateUrl('caravane_crm_contact_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Contact entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CaravaneCrmBundle:Contact')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('caravane_crm_contact_edit', array('id' => $id)));
        }

        return $this->render('CaravaneCrmBundle:Contact:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Contact entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CaravaneCrmBundle:Contact')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Contact entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('_crm_contact'));
    }

    /**
     * Creates a form to delete a Contact entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('caravane_crm_contact_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }



    public function exportAction() {
        $em = $this->getDoctrine()->getManager();
        $estates=$em->getRepository('CaravaneCrmBundle:Contact')->findAll();

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("ImmoLeLion")
        //->setLastModifiedBy("Giulio De Donato")
        ->setTitle("Biens");
        //->setSubject("Office 2005 XLSX Test Document")
        //->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
        //->setKeywords("office 2005 openxml php")
        //->setCategory("Test result file");
        $phpExcelObject->setActiveSheetIndex(0)
        ->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'Reference')
        ->setCellValue('C1', 'Nom')
        ->setCellValue('D1', 'Prix')
        ->setCellValue('E1', 'Type')
        ->setCellValue('F1', 'Localité')
        ->setCellValue('G1', 'Actif')
        ->setCellValue('H1', 'Jour')
        ->setCellValue('I1', 'Sem.')
        ->setCellValue('J1', 'Mois')
        ->setCellValue('K1', 'Total')
        ->setCellValue('L1', 'Viewed')
        ->setCellValue('M1', 'Favorites')
        ->setCellValue('N1', 'Créé')
        ->setCellValue('O1', 'Mis à jour')
        ;

        $i=2;
        foreach($estates as $estate) {
            
            $viewed="";
            $saved="";
            foreach($estate->getUser() as $e) {
                if($e->getSaved()!= true ) {
                    $viewed.=$e->getUser()->getContact()->getSalutation()." ".$e->getUser()->getContact()->getFirstname()." ".$e->getUser()->getContact()->getLastname()."\n";
                }
                else {
                    $saved.=$e->getUser()->getContact()->getSalutation()." ".$e->getUser()->getContact()->getFirstname()." ".$e->getUser()->getContact()->getLastname()."\n";
                }
            }
            $createdOn=$estate->getCreatedOn()->format('Y-m-d');
            if($estate->getUpdatedOn()) {
                $updatedOn=$estate->getUpdatedOn()->format('Y-m-d');
            }
            $phpExcelObject->getActiveSheet()
            ->setCellValue('A'.$i , $estate->getId())
            ->setCellValue('B'.$i, $estate->getReference())
            ->setCellValue('C'.$i, $estate->getName())
            ->setCellValue('D'.$i, $estate->getPrix())
            ->setCellValue('E'.$i, $estate->getLocation()==1?'Location':'Vente')
            ->setCellValue('F'.$i, $estate->getLocfr())
            ->setCellValue('G'.$i, $estate->getStatus()==1?"Oui":"Non")
            ->setCellValue('H'.$i, $estate->getDayview())
            ->setCellValue('I'.$i, $estate->getWeekview())
            ->setCellValue('J'.$i, $estate->getMonthview())
            ->setCellValue('K'.$i, $estate->getTotalview())
            ->setCellValue('L'.$i, $viewed)
            ->setCellValue('M'.$i, $saved)
            ->setCellValue('N'.$i, $createdOn)
            ->setCellValue('O'.$i, $updatedOn)
           ;
           $i++;
        }
        //$phpExcelObject->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=stream-file.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;        
    }



}
