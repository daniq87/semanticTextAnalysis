<?php

namespace AppBundle\Tests\Entity;

/**
 * Description of ReviewScoreTest
 *
 * @author Daniel
 */
class ReviewScoreTest extends \PHPUnit_Framework_TestCase {

    protected $review;
    protected $reviewScore;

    const REVIEW_DESCRIPTION = "Across the road from Santa Monica Pier is exactly where you want to be when visiting Santa Monica, as well as not far from lots of shops and restaurants/bars.Hotel itself is very new & modern, rooms were great. Comfortable beds & possibly the best shower ever!";

    public function setUp() {
        $this->review = new \AppBundle\Entity\Review;
        $this->review->setDescription(self::REVIEW_DESCRIPTION);
        $this->reviewScore = new \AppBundle\Entity\ReviewScore($this->review);
    }

    /**
     * @test
     * Test Get review is not empty and review description is correct.
     */
    public function testGetReview() {

        $this->assertNotEmpty($this->reviewScore->getReview());
        $this->assertEquals(self::REVIEW_DESCRIPTION, $this->reviewScore->getReview()->getDescription());
    }

    /**
     * @test
     * Test set and get Score
     */
    public function testGetScore() {
        $this->reviewScore->setScore(3);
        $this->reviewScore->setScore($this->reviewScore->getScore() + 1);

        $this->assertEquals(4, $this->reviewScore->getScore());
    }

    /**
     * @test
     * Test set and get Matches. The order is correct.
     */
    public function testGetMatches() {

        $matches = [
            1 => "rooms were great",
            3 => "best shower",
            2 => "Comfortable bed",
            0 => "not far from lots of shops and restaurants/bar",
        ];

        $this->reviewScore->setMatchesArray($matches);


        $this->assertEquals("not far from lots of shops and restaurants/bar, rooms were great, Comfortable bed, best shower", $this->reviewScore->getMatches());
    }

}
