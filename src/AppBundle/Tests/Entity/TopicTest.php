<?php

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Criteria;
use AppBundle\Entity\Topic;
use PHPUnit_Framework_TestCase;

/**
 * TopicTest
 * 
 * @author Daniel
 */
class TopicTest extends PHPUnit_Framework_TestCase {

    protected $criteriasName = [
         "hotel", "property", "lodge", "resort",  
         "bed", "sleep quality", "mattresses", "linens"
    ];
    protected $criterias = [];
    
    protected $hotelTopic;
    protected $bedTopic;

    public function setUp() {

        $this->hotelTopic = new Topic;
        $this->hotelTopic->setName("hotel");
        
        $this->bedTopic = new Topic;
        $this->bedTopic->setName("bed");
        
        // Get Criterias.
        for ($i = 0; $i < count($this->criteriasName); $i++) {
            $criteria = new Criteria();
            $criteria->setName($this->criteriasName[$i]);

            $this->criterias[] = $criteria;
        }
    }
    
    /**
     * @test
     * Get topic
     */
    public function testGetTopic(){
        
        $this->assertEquals("bed", $this->bedTopic->getName());
        $this->hotelTopic->setName("  hotel   ");
        $this->assertEquals("hotel", $this->hotelTopic->getName());
    }
    
    /**
     * @test
     * Test add criterias to a Topic
     */
    public function testAddCriteria(){
        $this->hotelTopic->addCriteria($this->criterias[0]);
        $this->hotelTopic->addCriteria($this->criterias[1]);
        $this->hotelTopic->addCriteria($this->criterias[2]);
        $this->hotelTopic->addCriteria($this->criterias[3]);
        

        $this->assertCount(4, $this->hotelTopic->getCriterias());
        $this->assertEquals("hotel", $this->hotelTopic->getCriterias()->get(0)->getName());
        $this->assertEquals("property", $this->hotelTopic->getCriterias()->get(1)->getName());
        $this->assertEquals("lodge", $this->hotelTopic->getCriterias()->get(2)->getName());
        $this->assertEquals("resort", $this->hotelTopic->getCriterias()->get(3)->getName());
    }
    
     /**
     * @test
     * Test add criterias to a Topic
     */
    public function testRemoveCriteria(){
        $this->hotelTopic->addCriteria($this->criterias[0]);
        $this->hotelTopic->addCriteria($this->criterias[1]);

        $this->assertCount(2, $this->hotelTopic->getCriterias());
        

        $this->hotelTopic->removeCriteria($this->criterias[1]);
        
        $this->assertCount(1, $this->hotelTopic->getCriterias());

    }
    
    
    

}
