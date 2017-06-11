<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Topic;
use AppBundle\Form\TopicType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\DefaultController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TopicController extends DefaultController {

    /**
     * @Route("/topic/index", name="app_topic_index")
     */
    public function indexAction(Request $request) {

        // Parameters by default
        $template = "Topic/index.html.twig";
        $pag = $request->query->getInt("page", 1);
        $topicName = "";

        if ($request->isXmlHttpRequest()) { // Parametters when it's an Ajax request
            $template = "Topic/topicsList.html.twig";
            $pag = $_GET['pag'];
            $topicName = $_GET['topicName'];
        }

        // Get all the topics or filtered by name.
        $topics = $this->get('app.topic')->getTopics($topicName);

        // Pagination with knp bundle 
        $pagination = $this->get('app.paginator')->getPagination($topics, $pag, 7);

        return $this->render($template, array('pagination' => $pagination));
    }

    /**
     * @Route("/topic/add", name="app_topic_add")
     * @return type
     */
    public function addAction() {
        $form = $this->getCreateForm(new TopicType(), new Topic(), 'app_topic_create', null, 'POST');

        return $this->render('Topic/topic_form.html.twig', array(
                    'title' => "New topic",
                    'actionButton' => "Create topic",
                    'form' => $form->createView()));
    }

    /**
     * @Route("/topic/create", name="app_topic_create")
     */
    public function createAction(Request $request) {
        $topic = new Topic();
        $form = $this->getCreateForm(new TopicType(), $topic, 'app_topic_create', null, 'POST');

        $form->handleRequest($request);

        if ($form->isValid()) {

            $this->get('app.topic')->create($topic);
            $this->addFlash('successMessage', 'The topic has been created.');
            return $this->redirectToRoute('app_topic_edit', array('id' => $topic->getId()));
        }

        return $this->render('Topic/topic_form.html.twig', array(
                    'title' => "New Topic",
                    'actionButton' => "Create topic",
                    'form' => $form->createView()));
    }

    /**
     * @Route("topic/edit/{id}", name="app_topic_edit")
     */
    public function editAction($id, Request $request) {
        $topic = $this->get('app.topic')->findById($id);
        $this->checkExistEntity($topic, "Topic");
        $form = $this->getCreateForm(new TopicType(), $topic, 'app_topic_update', array('id' => $topic->getId()), 'POST');
        
        // CRITERIA
        $template = "Topic/topic_form.html.twig";
        $pag = $request->query->getInt("page", 1);
        $criteriaName = "";
        if ($request->isXMLHttpRequest()) {
            $template = "Criteria/criterias_list.html.twig";
            $criteriaName = $_GET['criteria'];
            $pag = $_GET['pag'];
        } 
        $criterias = $this->get('app.criteria')->getCriterias($id, $criteriaName);

        // Pagination with knp bundle 
        $criteriaList = $this->get('app.paginator')->getPagination($criterias, $pag, 3);
        return $this->render($template, array(
                    'title' => "Edit topic",'actionButton' => "Update topic",
                    'form' => $form->createView(),'topicId' => $topic->getId(),
                    'topicName' => $topic->getName(),'criteriaList' => $criteriaList
        ));
    }

    /**
     * @Route("topic/update/{id}", name="app_topic_update" )
     */
    public function updateAction($id, Request $request) {
        $topic = $this->get('app.topic')->findById($id);
        $this->checkExistEntity($topic, "Topic");

        $form = $this->getCreateForm(new TopicType(), $topic, 'app_topic_update', array('id' => $topic->getId()), 'POST');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.topic')->update($topic);
            $this->addFlash('successMessage', 'The topic has been modified.');
            return $this->redirectToRoute('app_topic_edit', array('id' => $topic->getId()));
        }
        return $this->render('Topic/topic_form.html.twig', array(
                    'title' => "Edit topic",
                    'actionButton' => "Update topic",
                    'form' => $form->createView()));
    }

    /**
     * @Route("topic/delete/{id}", name="app_topic_delete")
     */
    public function deleteAction($id, Request $request) {
        $topic = $this->get('app.topic')->findById($id);
        $this->checkExistEntity($topic, "Topic");

        if ($request->isXMLHttpRequest()) {
            $this->get('app.topic')->delete($topic);
            return new Response(
                    json_encode(array(
                        "notification" => "The topic has been deleted.")), 200, array("Content-Type" => "application/json")
            );
        }
    }

}
