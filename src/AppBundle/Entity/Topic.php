<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Topic
 * 
 * @ORM\Table(name="topics")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TopicRepository")
 * @UniqueEntity("name")
 */
class Topic {

    /**
     * One Topic has Many Criterias.
     * @OneToMany(targetEntity="Criteria", mappedBy="criteria")
     */
    protected $criterias;

    public function __construct() {
        $this->criterias = new ArrayCollection();
    }

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
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=150)
     */
    private $name;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Topic
     */
    public function setName($name) {
        $this->name = trim($name);

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Add criteria
     *
     * @param \AppBundle\Entity\Criteria $criteria
     *
     * @return Topic
     */
    public function addCriteria(\AppBundle\Entity\Criteria $criteria) {
        $this->criterias[] = $criteria;

        return $this;
    }

    /**
     * Remove criteria
     *
     * @param \AppBundle\Entity\Criteria $criteria
     */
    public function removeCriteria(\AppBundle\Entity\Criteria $criteria) {
        $this->criterias->removeElement($criteria);
    }

    /**
     * Get criterias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCriterias() {
        return $this->criterias;
    }

}
