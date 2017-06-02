<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ReviewScoreRepository
 */
class ReviewScoreRepository extends EntityRepository {

    /**
     * Get ReviewScore by idReview
     * @param integer $idReview
     * @return ReviewScore
     */
    public function findByReviewId($idReview) {

        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('rs')
                        ->from('AppBundle:ReviewScore', 'rs')
                        ->join('rs.review', 'r')
                        ->where($qb->expr()->eq('r.id', ':id'))
                        ->setParameter('id',  $idReview )
                        ->getQuery()->getOneOrNullResult();
    }

    /**
     * Return all ReviewScore
     * @return ReviewScore
     */
    public function findAllOrderedById() {

        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('rs')
                        ->from('AppBundle:ReviewScore', 'rs')
                        ->orderBy('rs.id', 'DESC')
                        ->getQuery()->getResult();
    }

    /**
     * Return all ReviewScore
     * @return reviewScore
     */
    public function findAllReviewScore() {

        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('rs')
                        ->from('AppBundle:ReviewScore', 'rs')
                        ->join('rs.review', 'r')
                        ->orderBy('r.id', 'DESC')
                        ->getQuery()->getResult();
    }

    /**
     * Return score filtered by review description(like)
     * @param String $description
     * @return array ReviewScore
     */
    public function findLikeReviewDescription($description) {

        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('rs')
                        ->from('AppBundle:ReviewScore', 'rs')
                        ->join('rs.review', 'r')
                        ->where($qb->expr()->like('r.description', ':filter'))
                        ->orderBy('r.id', 'DESC')
                        ->setParameter('filter', '%' . $description . '%')
                        ->getQuery()->getResult();
    }

}
