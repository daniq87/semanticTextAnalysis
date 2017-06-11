<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function homeAction() {
        return $this->render('default/index.html.twig');
    }

    /**
     * Creates and returns a Form instance from the $type of the form.
     * @param string|FormTypeInterface $type    The built type of the form
     * @param type $entity The initial data for the form
     * @param type $route The action's form
     * @param array $parametersRoute parameters of the route
     * @param type $method Method of the form
     * @return Form
     */
    public function getCreateForm($type, $entity, $route, $parametersRoute, $method) {

        if ($parametersRoute == null) {
            $parametersRoute = array();
        }

        $form = $this->createForm($type, $entity, array(
            'action' => $this->generateUrl($route, $parametersRoute),
            'method' => $method));

        return $form;
    }
   
    /**
     * Check if the entity exist. If not exist throw NotFoundHttpException
     * @param object $entity. The entity to check
     * @param string $nameEntity The name of the entity to show in the message exception
     * @throws NotFoundHttpException
     */
   public function checkExistEntity($entity,$nameEntity) {
        if (!$entity) {
            throw $this->createNotFoundException($nameEntity . " not found");
        }
    }
}
