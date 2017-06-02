<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * ReviewScore
 *
 * @ORM\Table(name="review_score")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReviewScoreRepository")
 */
class ReviewScore {

    public function __construct(Review $review) {
        $this->review = $review;
        $this->score = 0;
    }

    /**
     * One ReviewScore has One Review.
     * @OneToOne(targetEntity="Review")
     * @JoinColumn(name="review_id", referencedColumnName="id")
     */
    private $review;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer")
     */
    private $score;

    /**
     * @var string
     *
     * @ORM\Column(name="matches", type="text", nullable=true)
     */
    private $matches;

    /**
     * @var array
     */
    private $matchesArray = array();

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set score
     *
     * @param integer $score
     * @return ReviewScore
     */
    public function setScore($score) {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer 
     */
    public function getScore() {
        return $this->score;
    }

    /**
     * Set matches
     *
     * @param string $matches
     * @return ReviewScore
     */
    public function setMatches($matches) {
        $this->matches = $matches;

        return $this;
    }

    /**
     * Get matches
     *
     * @return string 
     */
    public function getMatches() {
        return $this->matches;
    }

    /**
     * Get review
     *
     * @return \AppBundle\Entity\Review 
     */
    function getReview() {
        return $this->review;
    }

    /**
     * Set review
     *
     * @param \AppBundle\Entity\Review $review
     * @return Review
     */
    function setReview(\AppBundle\Entity\Review $review) {
        $this->review = $review;
    }

    /**
     * Get matchesArray
     *
     * @return array 
     */
    function getMatchesArray() {
        return $this->matchesArray;
    }

    /**
     * Set matchesArray
     * Order array $matchesArray and setMatches($matchesArray)
     * @param type $matchesArray
     */
    function setMatchesArray($matchesArray) {

        if (!empty($matchesArray)) {
            $this->setMatches($this->getMatchesOrdered($matchesArray));
        }

        $this->matchesArray = $matchesArray;
    }

    /**
     * Order array matches and build a string with the format: match1, match2, match3,..
     * @param array $matches
     * @return string
     */
    private function getMatchesOrdered($matches) {
        $result = "";
        ksort($matches);
        foreach ($matches as $val) {
            if (empty($result)) {
                $result = $val;
            } else {
                $result .= ", " . $val;
            }
        }

        return $result;
    }

}
