<?php

namespace AppBundle\Services;

use AppBundle\Entity\PositiveAttribute;
use Doctrine\ORM\EntityManager;
use AppBundle\Interfaces\Services\CrudInterface;

/**
 * PositiveAttributeService
 *
 * @author Daniel
 */
class PositiveAttributeService implements CrudInterface {

    const ENTITY_NAME = 'AppBundle:PositiveAttribute';

    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    /**
     * insert PositiveAttribute into table positive_attribute
     * @param PositiveAttribute $attribute
     */
    public function create($attribute) {
        $this->em->persist($attribute);
        $this->em->flush($attribute);
    }

    /**
     * Update PositiveAttribute from table positive_attribute
     * @param PositiveAttribute $attribute
     */
    public function update($attribute) {
        $this->em->flush($attribute);
    }

    /**
     * Delete positiveAttribute from table positive_attribute
     * @param PositiveAttribute $attribute
     */
    public function delete($attribute) {
        $this->em->remove($attribute);
        $this->em->flush();
    }

    /**
     * Get positiveAttribute by id
     * @param integer $id
     */
    public function findById($id) {
        return $this->em->getRepository(self::ENTITY_NAME)->find($id);
    }

    /**
     * Get positiveAttributes filtering by attributeName
     * @param string $attributeName
     * @return array PositiveAttribute
     */
    public function findLikeName($attributeName) {
        return $this->em->getRepository(self::ENTITY_NAME)->findLikeName($attributeName);
    }

    /**
     * Get All positive attributes ordered DESC by Id
     * @return array PositiveAttribute
     */
    public function findAllOrderedByName() {
        return $this->em->getRepository(self::ENTITY_NAME)->findAllOrderedByName();
    }

    /**
     * Get all positive attributes or filtered by attributeName
     * @param string $attributeName
     * @return array AppBundle\Entity\PositiveAttribute
     */
    public function getPositiveAttributes($attributeName) {

        if (!empty($attributeName)) {
            $topics = $this->findLikeName($attributeName);
        } else {
            $topics = $this->findAllOrderedByName();
        }

        return $topics;
    }

}
