<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CriteriaRepository
 *
 */
class CriteriaRepository extends EntityRepository {

    /**
     * Get all Criterias ordered by name ASC.
     * @return array AppBundle\Entity\Criteria
     */
    public function findAllOrderedByName() {
        
        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('c')
                        ->from('AppBundle:Criteria', 'c')
                        ->orderBy('c.name', 'ASC')
                        ->getQuery()->getResult();        

    }

    /**
     * Return criterias filtered by $topicId, name
     * @param String $topicId
     * @param String $name
     * @return array Criteria
     */
    public function findLike($topicId, $name) {

        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('c')
                        ->from('AppBundle:Criteria', 'c')
                        ->join('c.topic', 't')
                        ->where('t.id = :topicId', $qb->expr()->like('c.name', ':filter'))
                        ->orderBy('c.name', 'ASC')
                        ->setParameters(array('topicId' => $topicId, 'filter' => '%' . $name . '%'))
                        ->getQuery()->getResult();


    }

    /**
     * Find criterias by topicId
     * @param type $topicId
     * @return Array criteria
     */
    public function findByTopic($topicId) {

        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('c')
                        ->from('AppBundle:Criteria', 'c')
                        ->join('c.topic', 't')
                        ->where('t.id = :topicId')
                        ->orderBy('c.name', 'ASC')
                        ->setParameter('topicId', $topicId)
                        ->getQuery()->getResult();
    }

}
