<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Attribute;

/**
 * NegativeAttribute
 *
 * @ORM\Table(name="negative_attribute")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NegativeAttributeRepository")
 */
class NegativeAttribute extends Attribute
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
