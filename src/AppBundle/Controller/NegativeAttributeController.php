<?php

namespace AppBundle\Controller;

use AppBundle\Controller\DefaultController;
use AppBundle\Entity\NegativeAttribute;
use AppBundle\Form\NegativeAttributeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NegativeAttributeController extends DefaultController {

    
     /**
     * @Route("/negative_attribute/index", name="app_negative_attribute_index")
     */
    public function indexAction(Request $request) {

        // Parameters by default
        $template = "Attribute/index.html.twig";
        $attributeName = "";
        $pag = $request->query->getInt("page", 1);
        
        if ($request->isXMLHttpRequest()) { // Parameters when it is an Ajax request
            $template = "Attribute/negative_attribute_list.html.twig";  
            $attributeName = $_GET['attributeName'];
            $pag = $_GET['pag'];
        }
        
        // Get attributes
        $attributes =  $this->get('app.negative')->getNegativeAttributes($attributeName);
        // KnpPaginatorBundle
        $pagination = $this->get('app.paginator')->getPagination($attributes, $pag, 7);

        return $this->render($template, array('pagination' => $pagination,'tabSelected' => "negative"));
    }
    
    /**
     * @Route("/negative_attribute/add", name="app_negative_attribute_add")
     */
    public function addAction() {
        $form = $this->getCreateForm(new NegativeAttributeType(), new NegativeAttribute(), 'app_negative_attribute_create', null, 'POST');

        return $this->render('Attribute/positive_attribute_form.html.twig', array(
                    'title' => "New attribute",
                    'actionButton' => "Create attribute",
                    'form' => $form->createView()));
    }

    /**
     * @Route("/negative_attribute/create", name="app_negative_attribute_create")
     */
    public function createAction(Request $request) {
        $attribute = new NegativeAttribute();
        $form = $this->getCreateForm(new NegativeAttributeType(), $attribute, 'app_negative_attribute_create', null, 'POST');
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('app.negative')->create($attribute);
            $this->addFlash('successMessage', 'The attribute has been created.');
            return $this->redirectToRoute('app_negative_attribute_index');
        }

        return $this->render('Attribute/positive_attribute_form.html.twig', array(
                    'title' => "New attribute",
                    'actionButton' => "Create attribute",
                    'form' => $form->createView()));
    }

    /**
     * @Route("negative_attribute/edit/{id}", name="app_negative_attribute_edit")
     */
    public function editAction($id) {
        $attribute = $this->get('app.negative')->findById($id);
        $this->checkExistEntity($attribute, "Attribute");

        $form = $this->getCreateForm(new NegativeAttributeType(), $attribute, 'app_negative_attribute_update', array('id' => $attribute->getId()), 'PUT');
        return $this->render('Attribute/positive_attribute_form.html.twig', array(
                    'title' => "Edit attribute",
                    'actionButton' => "Update attribute",
                    'form' => $form->createView()));
    }

    /**
     * @Route("negative_attribute/update/{id}", name="app_negative_attribute_update" )
     */
    public function updateAction($id, Request $request) {
        $attribute = $this->get('app.negative')->findById($id);
        $this->checkExistEntity($attribute, "Attribute");

        $form = $this->getCreateForm(new NegativeAttributeType(), $attribute, 'app_negative_attribute_update', array('id' => $attribute->getId()), 'PUT');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.negative')->update($attribute);
            $this->addFlash('successMessage', 'The attribute has been modified.');
            return $this->redirectToRoute('app_negative_attribute_edit', array('id' => $attribute->getId()));
        }
        return $this->render('Attribute/positive_attribute_form.html.twig', array(
                    'title' => "Edit attribute",
                    'actionButton' => "Update attribute",
                    'form' => $form->createView()));
    }

    /**
     * @Route("negative_attribute/delete/{id}", name="app_negative_attribute_delete")
     */
    public function deleteAction($id, Request $request) {
        $attribute = $this->get('app.negative')->findById($id);
        $this->checkExistEntity($attribute, "Attribute");

        if ($request->isXMLHttpRequest()) {
            $this->get('app.negative')->delete($attribute);
            return new Response(
                    json_encode(array(
                        "notification" => "The attribute has been deleted.")), 200, array("Content-Type" => "application/json")
            );
        }
    }

}

