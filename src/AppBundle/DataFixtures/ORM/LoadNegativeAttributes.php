<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\NegativeAttribute;

/**
 * Create attributes by default
 *
 * @author Daniel
 */
class LoadNegativeAttributes extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {

        $this->createAttribute($manager, 'problem');
        $this->createAttribute($manager, 'unfriendly');
        $this->createAttribute($manager, 'horrible');
        $this->createAttribute($manager, 'stue');
        $this->createAttribute($manager, 'unsupported');
        $this->createAttribute($manager, 'average');
        $this->createAttribute($manager, 'dirty');
        $this->createAttribute($manager, 'negative');
        $this->createAttribute($manager, 'unsupported');
        $this->createAttribute($manager, 'hell');
        $this->createAttribute($manager, 'bad');
        $this->createAttribute($manager, "didn't work");
        $this->createAttribute($manager, 'ancient');
        $this->createAttribute($manager, 'cold');
        $this->createAttribute($manager, 'tiny');
        $this->createAttribute($manager, 'small');
        $this->createAttribute($manager, 'hard');
        $this->createAttribute($manager, 'uncomfortable');
        $this->createAttribute($manager, 'torn');
        $this->createAttribute($manager, 'Stay away');
        $this->createAttribute($manager, 'old');
        $this->createAttribute($manager, 'decrepit');
        $this->createAttribute($manager, 'terrible');
        $this->createAttribute($manager, 'broken');
        $this->createAttribute($manager, 'junk');
        $this->createAttribute($manager, 'awful');
        $this->createAttribute($manager, 'worst');
        $this->createAttribute($manager, 'disgusting');
        $this->createAttribute($manager, 'falling out');
        $this->createAttribute($manager, 'minty');
        $this->createAttribute($manager, 'thin');
        $this->createAttribute($manager, 'nightmare');
        $this->createAttribute($manager, 'freezing');
        $this->createAttribute($manager, "didn't sleep");
        $this->createAttribute($manager, 'rude');
        $this->createAttribute($manager, 'undisciplined');
        $this->createAttribute($manager, 'fell off');
        $this->createAttribute($manager, 'rotten');
        $this->createAttribute($manager, 'mess');
        $this->createAttribute($manager, 'surly');
        $this->createAttribute($manager, 'not going to come back');
    }

    private function createAttribute($manager, $name) {
        $attribute = new NegativeAttribute();
        $attribute->setName($name);

        $manager->persist($attribute);
        $manager->flush();
    }

    public function getOrder() {
        return 2;
    }

}
