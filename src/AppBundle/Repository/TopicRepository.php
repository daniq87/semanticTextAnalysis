<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * TopicRepository
 *
 */
class TopicRepository extends EntityRepository {

    /**
     * Get all Topics ordered by name ASC.
     * @return array Topic
     */
    public function findAllOrderedByName() {

        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('t')
                        ->from('AppBundle:Topic', 't')
                        ->orderBy('t.name', 'ASC')
                        ->getQuery()->getResult();
    }

    /**
     * Return topics filtered by name(like)
     * @param String $name
     * @return array Topic
     */
    public function findLikeName($name) {

        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('t')
                        ->from('AppBundle:Topic', 't')
                        ->where($qb->expr()->like('t.name', ':filter'))
                        ->orderBy('t.name', 'ASC')
                        ->setParameter('filter', '%' . $name . '%')
                        ->getQuery()->getResult();
    }

}
