<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ReviewScore;
use AppBundle\Exception\MandatoryElementsTextAnalizeException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CalculateScoreController extends Controller {

    /**
     * @Route("/calculate/index", name="app_calculate_index")
     */
    public function indexAction(Request $request) {

        // Value Parameters by default
        $reviewName = "";
        $template = "Calculate/index.html.twig";
        $pag = $request->query->getInt("page", 1);

        if ($request->isXmlHttpRequest()) { // Value Parameters when it's an Ajax request
            $template = "Calculate/score_reviews_list.html.twig";
            $pag = $_GET['pag'];
            $reviewName = $_GET['reviewName'];
        }

        $reviews = $this->get('app.score')->getScoreFilterAjax($reviewName);

        // Pagination with knp bundle 
        $pagination = $this->get('app.paginator')->getPagination($reviews, $pag, 4);
        return $this->render($template, array('pagination' => $pagination));
    }

    /**
     * @Route("/calculate/score", name="app_calculate_score")
     */
    public function calculateAction() {

        // If exist scores,Remove them.
        $this->get('app.score')->removeScores();

        /* Get all the required elements  to calculate the score of every review
         * Elements: array AppBundle\Entity\Criteria, array AppBundle\Entity\Separator
         *           array AppBundle\Entity\PositiveAttribute, array AppBundle\Entity\NegativeAttribute
         */
        $criterias = $this->get('app.criteria')->findAllOrderedByName();
        $separators = $this->get('app.separator')->findAllOrderedById();
        $positiveAttributes = $this->get('app.positive')->findAllOrderedByName();
        $negativeAttributes = $this->get('app.negative')->findAllOrderedByName();
        $arrayPositiveRegEx = $this->get('app.analizeText')->getRegEx($positiveAttributes);
        $arrayNegativeRegEx = $this->get('app.analizeText')->getRegEx($negativeAttributes);

        // Get all the reviews and calculate the score for each review.
        $reviews = $this->get('app.review')->findAllOrderedById();
        try {
            foreach ($reviews as $review) {
                $reviewScore = new ReviewScore($review);
                // Calculate positive score.
                $reviewScore = $this->get('app.analizeText')->calculateScore($reviewScore, $criterias, $arrayPositiveRegEx, $separators, true);
                // Calculate negative score.
                $reviewScore = $this->get('app.analizeText')->calculateScore($reviewScore, $criterias, $arrayNegativeRegEx, $separators, false);

                // Create \AppBundle\Entity\ReviewScore
                $this->get('app.score')->create($reviewScore);
            }

            return $this->redirectToRoute('app_calculate_index');
        } catch (MandatoryElementsTextAnalizeException $mandatoryElementsException) {
            $this->addFlash('error', $mandatoryElementsException->getMessage());
            // Redirect to the menu to create the mandatory element
            return $this->redirectToRoute($mandatoryElementsException->getRedirectAction());
        }
    }
    

}
