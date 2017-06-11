<?php

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Criteria;
use AppBundle\Entity\Topic;
use PHPUnit_Framework_TestCase;

/**
 * CriteriaTest
 *
 * @author Daniel
 */
class CriteriaTest extends PHPUnit_Framework_TestCase {

    protected $criteria;
    protected $hotelTopic;

    public function setUp() {

        $this->hotelTopic = new Topic;
        $this->hotelTopic->setName("hotel");

        $this->criteria = new Criteria();
        $this->criteria->setName("resort");
    }

    /**
     * @test
     * Test get name Criteria
     */
    public function testGetNameCriteria() {
        $this->assertEquals("resort", $this->criteria->getName());
        $this->criteria->setName("  resort   ");
        $this->assertEquals("resort", $this->criteria->getName());
    }

    /**
     * @test
     * Test get Topic from criteria.
     */
    public function testGetTopicFromCriteria() {

        $this->criteria->setTopic($this->hotelTopic);
        $this->assertNotEmpty($this->criteria->getTopic());
        $this->assertEquals("hotel", $this->criteria->getTopic()->getName());
    }

}
