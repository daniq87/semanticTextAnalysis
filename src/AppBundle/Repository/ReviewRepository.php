<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ReviewRepository
 *
 */
class ReviewRepository extends EntityRepository {

    public function findAllOrderedById() {

        $qb = $this->getEntityManager()->createQueryBuilder();

        return $qb->select('r')
                        ->from('AppBundle:Review', 'r')
                        ->orderBy('r.id', 'DESC')->getQuery()->getResult();
    }

    /**
     * Return reviews filtered by name(like)
     * @param String $name
     * @return Review
     */
    public function findLikeName($name) {

        $qb = $this->getEntityManager()->createQueryBuilder();
        return  $qb->select('r')
                        ->from('AppBundle:Review', 'r')
                        ->where($qb->expr()->like('r.description', ':filter'))
                        ->orderBy('r.id', 'DESC')
                        ->setParameter('filter', '%' . $name . '%')
                        ->getQuery()->getResult();
    }

}
