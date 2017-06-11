<?php

namespace AppBundle\DataFixtures\ORM;

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Separator;

/**
 * Create separator by default
 *
 * @author Daniel
 */
class LoadSeparator implements FixtureInterface {

    public function load(ObjectManager $manager) {
        $this->createSeparator($manager, ".", true);
        $this->createSeparator($manager, ",", true);
        $this->createSeparator($manager, "&", true);        
        $this->createSeparator($manager, "-", true);
        $this->createSeparator($manager, "but", false);
        $this->createSeparator($manager, "as well as", false);
        
    }

    private function createSeparator($manager, $separatorName, $is_symbol) {
        
        $separator = new Separator();
        $separator->setSeparator($separatorName);
        $separator->setIsSymbol($is_symbol);

        $manager->persist($separator);
        $manager->flush();
    }

}
