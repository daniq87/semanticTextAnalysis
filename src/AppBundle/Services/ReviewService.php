<?php

namespace AppBundle\Services;

use AppBundle\Entity\Review;
use AppBundle\Interfaces\Services\CrudInterface;
use Doctrine\ORM\EntityManager;

/**
 * ReviewService
 *
 * @author Daniel
 */
class ReviewService implements CrudInterface {

    const ENTITY_NAME = 'AppBundle:Review';

    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    /**
     * insert Review into table reviews
     * @param Review $review
     */
    public function create($review) {
        $this->em->persist($review);
        $this->em->flush($review);
    }

    /**
     * Update Review from table reviews
     * @param Review $review
     */
    public function update($review) {
        $this->em->flush($review);
    }

    /**
     * Delete review from table review
     * @param Review $review
     */
    public function delete($review) {
        // Remove review
        $this->em->remove($review);
        $this->em->flush();
    }

    /**
     * Get review by id
     * @param integer $id
     */
    public function findById($id) {
        return $this->em->getRepository(self::ENTITY_NAME)->find($id);
    }

    /**
     * Get reviews filtered by description, or get all of them if you pass the parameter reviewName empty.
     * @param string reviewName. It's not required.
     * @return array Reviews
     */
    public function getReviewsFilterAjax($reviewName) {

        if (!empty($reviewName)) {
            $reviews = $this->findLikeName($reviewName);
        } else {
            $reviews = $this->findAllOrderedById();
        }

        return $reviews;
    }

    /**
     * Get reviews filtering by reviewName
     * @param string $reviewName
     * @return array Review
     */
    public function findLikeName($reviewName) {
        return $this->em->getRepository(self::ENTITY_NAME)->findLikeName($reviewName);
    }

    /**
     * Get All reviews ordered DESC by Id
     * @return array Review
     */
    public function findAllOrderedById() {
        return $this->em->getRepository(self::ENTITY_NAME)->findAllOrderedById();
    }

    /**
     * Create reviews from CSV file
     * @param string $fileTmpName
     */
    public function createReviewsFromCsvFile($fileTmpName) {
        if (!empty($fileTmpName)) {
            $fileHandle = fopen($fileTmpName, "r");

            while (($row = fgetcsv($fileHandle, 0, ",")) !== FALSE) {
                $review = new Review();
                $review->setDescription($this->getDescription($row));
                $this->create($review);
            }
        }
    }

    private function getDescription($row) {
        $description = "";
        foreach ($row as $line) {
            if (empty($description)) {
                $description = $line;
            } else {
                $description .= "," . $line;
            }
        }

        return $description;
    }

}
