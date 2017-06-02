<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Topic;

/**
 * Create Topics by default
 *
 * @author Daniel
 */
class LoadTopics extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $this->createTopic($manager,'room');
        $this->createTopic($manager,'hotel');
        
        $this->createTopic($manager,'staff');
        $this->createTopic($manager,'location');
        $this->createTopic($manager,'breakfast');
        $this->createTopic($manager,'bed');
        $this->createTopic($manager,'food');
        $this->createTopic($manager,'bathroom');
        $this->createTopic($manager,'restaurant');
        $this->createTopic($manager,'pool');
        $this->createTopic($manager,'bar');
        $this->createTopic($manager,'cost');
        
    }

    private function createTopic($manager, $name) {
        $topic = new Topic();
        $topic->setName($name);

        $manager->persist($topic);
        $manager->flush();
        
        $this->addReference($name, $topic);
    }

    public function getOrder() {
        return 3;
    }

}