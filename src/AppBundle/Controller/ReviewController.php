<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Review;
use AppBundle\Form\ReviewType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReviewController extends DefaultController {

    /**
     * @Route("/review/index", name="app_review_index")
     */
    public function indexAction(Request $request) {

        // Parametters by default
        $template = "Review/index.html.twig";
        $pag = $request->query->getInt("page", 1);
        $reviewDescription = "";

        if ($request->isXmlHttpRequest()) { // Parametters when it's an Ajax request
            $template = "Review/reviews_list.html.twig";
            $pag = $_GET['pag'];
            $reviewDescription = $_GET['reviewName'];
        }
        // Get all the reviews or filtered by description
        $reviews = $this->get('app.review')->getReviewsFilterAjax($reviewDescription);
        // Pagination with knp bundle 
        $pagination = $this->get('app.paginator')->getPagination($reviews, $pag, 3);

        return $this->render($template, array('pagination' => $pagination));
    }

    /**
     * @Route("/review/create_from_csv", name="app_review_create_from_csv")
     */
    public function createFromCsvAction() {
        $this->get('app.review')->createReviewsFromCsvFile($_FILES['csv-file']['tmp_name']);

        return new Response(
                json_encode(array(
                    "notification" => "Create Reviews from CSV file")), 200, array("Content-Type" => "application/json")
        );
    }

    /**
     * @Route("/review/add", name="app_review_add")
     */
    public function addAction() {
        $form = $this->getCreateForm(new ReviewType(), new Review(), 'app_review_create', null, 'POST');

        return $this->render('Review/review_form.html.twig', array(
                    'title' => "New review",
                    'actionButton' => "Create review",
                    'form' => $form->createView()));
    }

    /**
     * @Route("/review/create", name="app_review_create")
     */
    public function createAction(Request $request) {
        $review = new Review();
        $form = $this->getCreateForm(new ReviewType(), $review, 'app_review_create', null, 'POST');
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('app.review')->create($review);
            $this->addFlash('successMessage', 'The review has been created.');
            return $this->redirectToRoute('app_review_index');
        }

        return $this->render('Review/review_form.html.twig', array(
                    'title' => "New review",
                    'actionButton' => "Create review",
                    'form' => $form->createView()));
    }

    /**
     * @Route("review/edit/{id}", name="app_review_edit")
     */
    public function editAction($id) {

        $review = $this->get('app.review')->findById($id);
        $this->checkExistEntity($review, "Review");
        $form = $this->getCreateForm(new ReviewType(), $review, 'app_review_update', array('id' => $review->getId()), 'POST');

        return $this->render('Review/review_form.html.twig', array(
                    'title' => "Edit review",
                    'actionButton' => "Update review",
                    'form' => $form->createView()));
    }

    /**
     * @Route("review/update/{id}", name="app_review_update" )
     */
    public function updateAction($id, Request $request) {
        $review = $this->get('app.review')->findById($id);
        $this->checkExistEntity($review, "Review");

        $form = $this->getCreateForm(new ReviewType(), $review, 'app_review_update', array('id' => $review->getId()), 'POST');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.review')->update($review);
            $this->addFlash('successMessage', 'The review has been modified.');
            return $this->redirectToRoute('app_review_edit', array('id' => $review->getId()));
        }

        return $this->render('Review/review_form.html.twig', array(
                    'title' => "Edit review",
                    'actionButton' => "Update review",
                    'form' => $form->createView()));
    }

    /**
     * @Route("review/delete/{id}", name="app_review_delete")
     */
    public function deleteAction($id, Request $request) {
        $review = $this->get('app.review')->findById($id);
        $this->checkExistEntity($review, "Review");

        if ($request->isXMLHttpRequest()) {

            // First remove ReviewScore if it exists
            $reviewScore = $this->get('app.score')->findByReviewId($id);
            if ($reviewScore != null) {
                $this->get('app.score')->delete($reviewScore);
            }

            // Remove Review
            $this->get('app.review')->delete($review);
            return new Response(
                    json_encode(array(
                        "notification" => "The review has been deleted.")), 200, array("Content-Type" => "application/json")
            );
        }
    }

}
