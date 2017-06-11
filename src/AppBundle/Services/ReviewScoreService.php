<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\ReviewScore;
use AppBundle\Services\ReviewService;
use AppBundle\Interfaces\Services\CrudInterface;

/**
 * Description of ReviewScoreService
 *
 * @author Daniel
 */
class ReviewScoreService implements CrudInterface {

    const ENTITY_NAME = 'AppBundle:ReviewScore';
    private $em;
    private $reviewService;

    public function __construct(EntityManager $em, ReviewService $reviewService) {
        $this->em = $em;
        $this->reviewService = $reviewService;
    }

    /**
     * insert ReviewScore into table review_scores
     * @param ReviewScore $reviewScore
     */
    public function create($reviewScore) {
        $this->em->persist($reviewScore);
        $this->em->flush($reviewScore);
    }

    /**
     * Update ReviewScore from table review_scores
     * @param ReviewScore $reviewScore
     */
    public function update($reviewScore) {
        $this->em->flush($reviewScore);
    }

    /**
     * Delete ReviewScore from table review_scores
     * @param ReviewScore $reviewScore
     */
    public function delete($reviewScore) {

        $this->em->remove($reviewScore);
        $this->em->flush();
    }

    /**
     * Get ReviewScore by id
     * @param integer $id
     */
    public function findById($id) {
        return $this->em->getRepository(self::ENTITY_NAME)->find($id);
    }
    
    /**
     * Get all review score or filter by reviewName
     * @param string $reviewName
     * @return ReviewScore
     */
    public function getScoreFilterAjax($reviewName) {
        if (!empty($reviewName)) {
            $reviewsScore = $this->findLikeReviewDescription($reviewName);
        } else {
            $reviewsScore = $this->findAllReviewScore();
        }

        $reviews = $this->reviewService->getReviewsFilterAjax($reviewName);
        foreach ($reviews as $review) {
            if (!$this->isExistReview($review->getId(), $reviewsScore)) {
                $reviewScore = new ReviewScore($review);
                $reviewsScore[] = $reviewScore;
            }
        }

        return $reviewsScore;
    }

    private function isExistReview($idReview, $reviewsScore) {
        foreach ($reviewsScore as $reviewScore) {
            if ($reviewScore->getReview()->getId() === $idReview) {
                return true;
            }
        }

        return false;
    }

    /**
     * Delete all scores form table: review_scores
     */
    public function removeScores() {
        $scores = $this->findAllOrderedById();

        foreach ($scores as $score) {
            $this->delete($score);
        }
    }

    /**
     * Get all scores
     */
    public function findAllReviewScore() {
        return $this->em->getRepository(self::ENTITY_NAME)->findAllReviewScore();
    }

    /**
     * Get all scores
     */
    public function findAllOrderedById() {
        return $this->em->getRepository(self::ENTITY_NAME)->findAllOrderedById();
    }

    /**
     * Get score filtered by review Description
     */
    public function findLikeReviewDescription($reviewDescription) {
        return $this->em->getRepository(self::ENTITY_NAME)->findLikeReviewDescription($reviewDescription);
    }

    /**
     * Get ReviewScore by idReview
     * @param integer $idReview
     * @return ReviewScore
     */
    public function findByReviewId($idReview) {
        return $this->em->getRepository(self::ENTITY_NAME)->findByReviewId($idReview);
    }

}
