<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Criteria;

/**
 * Create criterias by default
 *
 * @author Daniel
 */
class LoadCriterias extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        // TOPIC: ROOM
        $this->createCriteria($manager, 'room', 'room');
        $this->createCriteria($manager, 'apartment', 'room');
        $this->createCriteria($manager, 'chamber', 'room');
        // TOPIC: HOTEL
        $this->createCriteria($manager, 'hotel', 'hotel');
        $this->createCriteria($manager, 'property', 'hotel');
        $this->createCriteria($manager, 'lodge', 'hotel');
        $this->createCriteria($manager, 'resort', 'hotel');
        // TOPIC: STAFF
        $this->createCriteria($manager, 'staff', 'staff');
        $this->createCriteria($manager, 'service', 'staff');
        $this->createCriteria($manager, 'personnel', 'staff');
        $this->createCriteria($manager, 'crew', 'staff');
        $this->createCriteria($manager, 'he', 'staff');
        $this->createCriteria($manager, 'she', 'staff');
        // TOPIC: LOCATION
        $this->createCriteria($manager, 'location', 'location');
        $this->createCriteria($manager, 'spot', 'location');
        // TOPIC: BREAKFAST
        $this->createCriteria($manager, 'breakfast', 'breakfast');

        // TOPIC: BED
        $this->createCriteria($manager, 'bed', 'bed');
        $this->createCriteria($manager, 'sleep quality', 'bed');
        $this->createCriteria($manager, 'mattresses', 'bed');
        $this->createCriteria($manager, 'linens', 'bed');

        // TOPIC: FOOD
        $this->createCriteria($manager, 'food', 'food');
        $this->createCriteria($manager, 'dinner', 'food');
        $this->createCriteria($manager, 'lunch', 'food');

        // TOPIC: BATHROOM
        $this->createCriteria($manager, 'bathroom', 'bathroom');
        $this->createCriteria($manager, 'lavatory', 'bathroom');
        $this->createCriteria($manager, 'shower', 'bathroom');
        $this->createCriteria($manager, 'toilet', 'bathroom');
        $this->createCriteria($manager, 'bath', 'bathroom');

        // TOPIC: RESTAURANT
        $this->createCriteria($manager, 'restaurant', 'restaurant');

        // TOPIC: POOL
        $this->createCriteria($manager, 'pool', 'pool');
        $this->createCriteria($manager, 'spa', 'pool');
        $this->createCriteria($manager, 'wellness', 'pool');
        // TOPIC: BAR
        $this->createCriteria($manager, 'bar', 'bar');
        // TOPIC: COST
        $this->createCriteria($manager, 'price', 'cost');
        $this->createCriteria($manager, 'bill', 'cost');
    }

    private function createCriteria($manager, $name, $topicName) {
        $criteria = new Criteria();
        $criteria->setName($name);
        $criteria->setTopic($this->getReference($topicName));

        $manager->persist($criteria);
        $manager->flush();
    }

    public function getOrder() {
        return 4;
    }

}
