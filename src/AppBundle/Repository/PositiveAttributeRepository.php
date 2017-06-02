<?php

namespace AppBundle\Repository;


/**
 * PositiveAttributeRepository
 *
 */
class PositiveAttributeRepository extends \Doctrine\ORM\EntityRepository {

    /**
     * Get all PositiveAttribute
     * @return array PositiveAttribute
     */
    public function findAllOrderedByName() {

        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('p')
                        ->from('AppBundle:PositiveAttribute', 'p')
                        ->orderBy('p.name', 'ASC')
                        ->getQuery()->getResult();
    }

    /**
     * Return PositiveAttribute filtered by name(like)
     * @param String $name
     * @return array PositiveAttribute
     */
    public function findLikeName($name) {

        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('p')
                        ->from('AppBundle:PositiveAttribute', 'p')
                        ->where($qb->expr()->like('p.name', ':filter'))
                        ->orderBy('p.name', 'ASC')
                        ->setParameter('filter', '%' . $name . '%')
                        ->getQuery()->getResult();
    }

}
