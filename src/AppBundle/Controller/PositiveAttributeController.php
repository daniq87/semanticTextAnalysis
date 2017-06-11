<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PositiveAttribute;
use AppBundle\Form\PositiveAttributeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\DefaultController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PositiveAttributeController extends DefaultController {

    /**
     * @Route("/positive_attribute/index", name="app_positive_attribute_index")
     */
    public function indexAction(Request $request) {

        // Parameters by default
        $template = "Attribute/index.html.twig";
        $attributeName = "";
        $pag = $request->query->getInt("page", 1);

        if ($request->isXMLHttpRequest()) { // Parameters when it is an Ajax request
            $template = "Attribute/positive_attribute_list.html.twig";
            $attributeName = $_GET['attributeName'];
            $pag = $_GET['pag'];
        }

        // Get attributes
        $attributes = $this->get('app.positive')->getPositiveAttributes($attributeName);
        // KnpPaginatorBundle
        $pagination = $this->get('app.paginator')->getPagination($attributes, $pag, 7);

        return $this->render($template, array('pagination' => $pagination, 'tabSelected' => "positive"));
    }

    /**
     * @Route("/positive_attribute/add", name="app_positive_attribute_add")
     */
    public function addAction() {
        $form = $this->getCreateForm(new PositiveAttributeType(), new PositiveAttribute(), 'app_attribute_create', null, 'POST');

        return $this->render('Attribute/positive_attribute_form.html.twig', array(
                    'title' => "New attribute",
                    'actionButton' => "Create attribute",
                    'form' => $form->createView()));
    }

    /**
     * @Route("/attribute/create", name="app_attribute_create")
     */
    public function createAction(Request $request) {
        $attribute = new PositiveAttribute();
        $form = $this->getCreateForm(new PositiveAttributeType(), $attribute, 'app_attribute_create', null, 'POST');
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('app.positive')->create($attribute);
            $this->addFlash('successMessage', 'The attribute has been created.');
            return $this->redirectToRoute('app_positive_attribute_index');
        }

        return $this->render('Attribute/positive_attribute_form.html.twig', array(
                    'title' => "New attribute",
                    'actionButton' => "Create attribute",
                    'form' => $form->createView()));
    }

    /**
     * @Route("attribute/edit/{id}", name="app_attribute_edit")
     */
    public function editAction($id) {
        $attribute = $this->get('app.positive')->findById($id);
        $this->checkExistEntity($attribute, "Attribute");

        $form = $this->getCreateForm(new PositiveAttributeType(), $attribute, 'app_attribute_update', array('id' => $attribute->getId()), 'PUT');
        return $this->render('Attribute/positive_attribute_form.html.twig', array(
                    'title' => "Edit attribute",
                    'actionButton' => "Update attribute",
                    'form' => $form->createView()));
    }

    /**
     * @Route("attribute/update/{id}", name="app_attribute_update" )
     */
    public function updateAction($id, Request $request) {
        $attribute = $this->get('app.positive')->findById($id);
        $this->checkExistEntity($attribute, "Attribute");

        $form = $this->getCreateForm(new PositiveAttributeType(), $attribute, 'app_attribute_update', array('id' => $attribute->getId()), 'PUT');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.positive')->update($attribute);
            $this->addFlash('successMessage', 'The attribute has been modified.');
            return $this->redirectToRoute('app_attribute_edit', array('id' => $attribute->getId()));
        }
        return $this->render('Attribute/positive_attribute_form.html.twig', array(
                    'title' => "Edit attribute",
                    'actionButton' => "Update attribute",
                    'form' => $form->createView()));
    }

    /**
     * @Route("positive_attribute/delete/{id}", name="app_positive_attribute_delete")
     */
    public function deleteAction($id, Request $request) {
        $attribute = $this->get('app.positive')->findById($id);
        $this->checkExistEntity($attribute, "Attribute");

        if ($request->isXMLHttpRequest()) {
            $this->get('app.positive')->delete($attribute);
            return new Response(
                    json_encode(array(
                        "notification" => "The attribute has been deleted.")), 200, array("Content-Type" => "application/json")
            );
        }
    }

}
