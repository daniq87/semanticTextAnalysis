<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Criteria;
use AppBundle\Form\CriteriaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\DefaultController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CriteriaController extends DefaultController {

    /**
     * @Route("/criteria/add/{topicId}", name="app_criteria_add")
     * @return type
     */
    public function addAction($topicId) {
        $form = $this->getCreateForm(new CriteriaType(), new Criteria(), 'app_criteria_create', array('topicId' => $topicId), 'POST');

        return $this->render('Criteria/criteria_form.html.twig', array(
                    'title' => "New criteria",
                    'actionButton' => "Create criteria",
                    'form' => $form->createView(),
                    'topicId' => $topicId));
    }

    /**
     * @Route("/criteria/create/{topicId}", name="app_criteria_create")
     */
    public function createAction($topicId, Request $request) {
        $criteria = new Criteria();
        $form = $this->getCreateForm(new CriteriaType(), $criteria, 'app_criteria_create', array('topicId' => $topicId), 'POST');

        $form->handleRequest($request);

        if ($form->isValid()) {
            $topic = $this->get('app.topic')->findById($topicId);
            $criteria->setTopic($topic);

            $this->get('app.criteria')->create($criteria);
            $this->addFlash('mensaje', 'The criteria has been created.');

            return $this->redirectToRoute('app_topic_edit', array('id' => $topicId));
        }

        return $this->render('Criteria/criteria_form.html.twig', array(
                    'title' => "New criteria",
                    'actionButton' => "Create criteria",
                    'form' => $form->createView(),
                    'topicId' => $topicId));
    }

    /**
     * @Route("criteria/edit/{id}/{topicId}", name="app_criteria_edit")
     */
    public function editAction($id, $topicId) {

        $criteria = $this->get('app.criteria')->findById($id);
        $this->checkExistEntity($criteria, "Criteria");

        $form = $this->getCreateForm(new CriteriaType(), $criteria, 'app_citeria_update', array('id' => $criteria->getId(), 'topicId' => $topicId), 'POST');
        return $this->render('Criteria/criteria_form.html.twig', array(
                    'title' => "Edit criteria",
                    'actionButton' => "Update criteria",
                    'form' => $form->createView()));
    }

    /**
     * @Route("criteria/update/{id}/{topicId}", name="app_citeria_update" )
     */
    public function updateAction($id, $topicId, Request $request) {
        $criteria = $this->get('app.criteria')->findById($id);

        $this->checkExistEntity($criteria, "Criteria");

        $form = $this->getCreateForm(new CriteriaType(), $criteria, 'app_citeria_update', array('id' => $criteria->getId(), 'topicId' => $topicId), 'POST');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.criteria')->update($criteria);
            $this->addFlash('successMessage', 'The criteria has been modified.');
            return $this->redirectToRoute('app_topic_edit', array('id' => $topicId));
        }
        return $this->render('Criteria/criteria_form.html.twig', array(
                    'title' => "Edit criteria",
                    'actionButton' => "Update criteria",
                    'form' => $form->createView()));
    }

    /**
     * @Route("criteria/delete/{id}", name="app_criteria_delete")
     */
    public function deleteAction($id, Request $request) {
        $criteria = $this->get('app.criteria')->findById($id);
        $this->checkExistEntity($criteria, "Criteria");

        if ($request->isXMLHttpRequest()) {
            $this->get('app.criteria')->delete($criteria);

            return new Response(
                    json_encode(array(
                        "notification" => "The criteria has been deleted.")), 200, array("Content-Type" => "application/json")
            );
        }
    }

}
