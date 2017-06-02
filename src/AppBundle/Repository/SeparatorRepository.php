<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * SeparatorRepository
 *
 */
class SeparatorRepository extends EntityRepository {

    public function findAllOrderedById() {

        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('s')
                        ->from('AppBundle:Separator', 's')
                        ->orderBy('s.id', 'ASC')
                        ->getQuery()->getResult();
    }

}
