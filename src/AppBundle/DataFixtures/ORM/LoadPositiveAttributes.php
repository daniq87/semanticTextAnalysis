<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\PositiveAttribute;

/**
 * Create attributes by default
 *
 * @author Daniel
 */
class LoadPositiveAttributes extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $this->createAttribute($manager,'great');
        $this->createAttribute($manager,'good');
        $this->createAttribute($manager,'very nice');
        $this->createAttribute($manager,'amazing');
        $this->createAttribute($manager,'careful');
        $this->createAttribute($manager,'well');
        $this->createAttribute($manager,'helpful');
        $this->createAttribute($manager,'friendly');
        $this->createAttribute($manager,'clean');
        $this->createAttribute($manager,'cleanliness');
        $this->createAttribute($manager,'easy');
        $this->createAttribute($manager,'excellent');
        $this->createAttribute($manager,'top');
        $this->createAttribute($manager,'superb');
        $this->createAttribute($manager,'fantastic');
        $this->createAttribute($manager,'best');
        $this->createAttribute($manager,'comfortable');
        $this->createAttribute($manager,'perfect');
        $this->createAttribute($manager,'love');
        $this->createAttribute($manager,'going to come back');
        $this->createAttribute($manager,'made our stay');
        $this->createAttribute($manager,'was fun');
        $this->createAttribute($manager,'not far from');    
    }

    private function createAttribute($manager, $name) {
        $attribute = new PositiveAttribute();
        $attribute->setName($name);

        $manager->persist($attribute);
        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }

}
