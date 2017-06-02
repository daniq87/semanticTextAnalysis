<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Separator
 *
 * @ORM\Table(name="separator")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SeparatorRepository")
 */
class Separator
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
     * @var string
     *
     * @ORM\Column(name="separator", type="string", length=255)
     */
    private $separator;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_symbol", type="boolean")
     */
    private $isSymbol;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set separator
     *
     * @param string $separator
     * @return Separator
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * Get separator
     *
     * @return string 
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    /**
     * Set isSymbol
     *
     * @param boolean $isSymbol
     * @return Separator
     */
    public function setIsSymbol($isSymbol)
    {
        $this->isSymbol = $isSymbol;

        return $this;
    }

    /**
     * Get isSymbol
     *
     * @return boolean 
     */
    public function getIsSymbol()
    {
        return $this->isSymbol;
    }
}
