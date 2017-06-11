<?php

namespace AppBundle\Services;

use AppBundle\Entity\NegativeAttribute;
use Doctrine\ORM\EntityManager;
use AppBundle\Interfaces\Services\CrudInterface;

/**
 * NegativeAttributeService
 *
 * @author Daniel
 */
class NegativeAttributeService implements CrudInterface {

    const ENTITY_NAME = 'AppBundle:NegativeAttribute';

    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    /**
     * insert NegativeAttribute into table negative_attribute
     * @param NegativeAttribute $attribute
     */
    public function create($attribute) {
        $this->em->persist($attribute);
        $this->em->flush($attribute);
    }

    /**
     * Update NegativeAttribute from table negative_attribute
     * @param NegativeAttribute $attribute
     */
    public function update($attribute) {
        $this->em->flush($attribute);
    }

    /**
     * Delete negativeAttribute from table negative_attribute
     * @param NegativeAttribute $attribute
     */
    public function delete($attribute) {
        $this->em->remove($attribute);
        $this->em->flush();
    }

    /**
     * Get negativeAttribute by id
     * @param integer $id
     */
    public function findById($id) {
        return $this->em->getRepository(self::ENTITY_NAME)->find($id);
    }

    /**
     * Get negativeAttributes filtering by attributeName
     * @param string $attributeName
     * @return array NegativeAttribute
     */
    public function findLikeName($attributeName) {
        return $this->em->getRepository(self::ENTITY_NAME)->findLikeName($attributeName);
    }

    /**
     * Get All negative attributes ordered DESC by name
     * @return array NegativeAttribute
     */
    public function findAllOrderedByName() {
        return $this->em->getRepository(self::ENTITY_NAME)->findAllOrderedByName();
    }

    /**
     * Get all negative attributes or filtered by attributeName
     * @param string $attributeName
     * @return array AppBundle\Entity\NegativeAttribute
     */
    public function getNegativeAttributes($attributeName) {

        if (!empty($attributeName)) {
            $topics = $this->findLikeName($attributeName);
        } else {
            $topics = $this->findAllOrderedByName();
        }

        return $topics;
    }

}
