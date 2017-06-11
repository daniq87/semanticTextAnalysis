<?php

namespace AppBundle\Repository;

/**
 * NegativeAttributeRepository
 */
class NegativeAttributeRepository extends \Doctrine\ORM\EntityRepository {

    /**
     * Get all NegativeAttribute
     * @return array NegativeAttribute
     */
    public function findAllOrderedByName() {

        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('n')
                        ->from('AppBundle:NegativeAttribute', 'n')
                        ->orderBy('n.name', 'ASC')
                        ->getQuery()->getResult();
    }

    /**
     * Get NegativeAttribute filtered by name(like)
     * @param String $name
     * @return array NegativeAttribute
     */
    public function findLikeName($name) {

        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('n')
                        ->from('AppBundle:NegativeAttribute', 'n')
                        ->where($qb->expr()->like('n.name', ':filter'))
                        ->orderBy('n.name', 'ASC')
                        ->setParameter('filter', '%' . $name . '%')
                        ->getQuery()->getResult();
    }

}
