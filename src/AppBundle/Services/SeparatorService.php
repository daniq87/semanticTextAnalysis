<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
/**
 * SeparatorService
 *
 * @author Daniel
 */
class SeparatorService {

    const ENTITY_NAME = 'AppBundle:Separator';

    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    /**
     * Get topic by id
     * @param integer $id
     */
    public function findAllOrderedById() {
        return $this->em->getRepository(self::ENTITY_NAME)->findAllOrderedById();
    }
}
