<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Criteria
 *
 * @ORM\Table(name="criterias")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CriteriaRepository")
 * @UniqueEntity("name")
 */
class Criteria {

    /**
     * Many Criterias have One Topic.
     * @ManyToOne(targetEntity="Topic", inversedBy="criterias")
     * @JoinColumn(name="topic_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $topic;

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
     * @return Criteria
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
     * Set topic
     *
     * @param \AppBundle\Entity\Topic $topic
     * @return Criteria
     */
    public function setTopic(\AppBundle\Entity\Topic $topic = null) {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get topic
     *
     * @return \AppBundle\Entity\Topic 
     */
    public function getTopic() {
        return $this->topic;
    }

}
