<?php

namespace AppBundle\Services;

use AppBundle\Entity\Criteria;
use AppBundle\Interfaces\Services\CrudInterface;
use Doctrine\ORM\EntityManager;

/**
 * Description of CriteriaService
 *
 * @author Daniel
 */
class CriteriaService implements CrudInterface {

    const ENTITY_NAME = 'AppBundle:Criteria';

    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    /**
     * insert Criteria into table criterias
     * @param Criteria $criteria
     */
    public function create($criteria) {
        $this->em->persist($criteria);
        $this->em->flush($criteria);
    }

    /**
     * Update Criteria from table criterias
     * @param Criteria $criteria
     */
    public function update($criteria) {
        $this->em->flush($criteria);
    }

    /**
     * Delete criteria from table criteria
     * @param Criteria $criteria
     */
    public function delete($criteria) {

        $this->em->remove($criteria);
        $this->em->flush();
    }

    /**
     * Get criteria by id
     * @param integer $id
     */
    public function findById($id) {
        return $this->em->getRepository(self::ENTITY_NAME)->find($id);
    }

    /**
     * Return criterias filtered by $topicId, name
     * @param String $topicId
     * @param String $criteriaName
     * @return array Criteria
     */
    public function findLike($topicId,$criteriaName) {
        return $this->em->getRepository(self::ENTITY_NAME)->findLike($topicId,$criteriaName);
    }

    /**
     * Find criterias by topicId
     * @param type $topicId
     * @return Array criteria
     */
    public function findByTopic($topicId) {
        return $this->em->getRepository(self::ENTITY_NAME)->findByTopic($topicId);
    }

    /**
     * Get All criterias ordered ASC name
     * @return array Criteria
     */
    public function findAllOrderedByName() {
        $e = $this->em;
        $repos = $e->getRepository(self::ENTITY_NAME);
        return $repos->findAllOrderedByName();
    }

    /**
     * Get all criterias or filtered by criteriaName
     * @param string $criteriaName
     * @return array AppBundle\Entity\Criteria
     */
    public function getCriterias($topicId, $criteriaName) {

        if (!empty($criteriaName)) {
            $topics = $this->findLike($topicId, $criteriaName);
        } else {
            $topics = $this->findByTopic($topicId);
        }

        return $topics;
    }

}
