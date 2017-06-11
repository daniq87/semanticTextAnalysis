<?php

use AppBundle\Entity\Criteria;
use AppBundle\Entity\NegativeAttribute;
use AppBundle\Entity\PositiveAttribute;
use AppBundle\Entity\Review;
use AppBundle\Entity\ReviewScore;
use AppBundle\Entity\Separator;
use AppBundle\Exception\MandatoryElementsTextAnalizeException;
use AppBundle\Services\AnalizeTextService;
use AppBundle\Services\ReviewScoreService;
use AppBundle\Services\ReviewService;
use Doctrine\ORM\EntityManager;

/**
 * Tests of ReviewScoreServiceTest
 *
 * @author Daniel
 */
class ReviewScoreServiceTest extends PHPUnit_Framework_TestCase {

    protected $reviewsDescription = [
        "The location is perfect but the room is very small. We are not going to come back.",
        "I was excited to stay at this Hotel. It looked cute and was reasonable. It turned out to be terrible. We were woken up both mornings at 5:45 AM by noisy neighbors. The shower was clogged up...the room was sooooo small we kept tripping over each other. The saving grace was the pool at the Loews next door. I wish we had paid an extra $50 and stayed at a nicer place. This motel should cost no more than $99 a night.",
        "Terrible. Old, not quite clean. Lost my reservation, then \"found\" a smaller room, for the same price, of course. Noisy. Absolutely no parking, unless you luck out for the $10 spaces (of which there are 12). Water in bathroom sink would not turn off. Not hair dryer, no iron in room. Miniscule shower- better be thin to use it!",
        "I have stayed here 4 or 5 times while visiting LA. Despite travelling all over the world and staying in some of the best international hotels ( for work), Hotel Caliornia is one of my absolute favourites. Perfect location, right on the beach. I love the way you can just open your door and be outside, no elevators, corridors big glass windows. The ambience is so nice, retro perfect. As for the staff, I have had consistently superb service, with much more personal service and attention to detail than is usual in bigger hotels. Aaron and Katy were just two who have been exemplary this time but really everyone is terrific. Can't recommend it highly enough.",
        "Found this hotel by reading over tripadvisor while planning a little beach getaway. Really good price by the beach. James the front desk manager was really fun, he made our stay more fun than we thought it would be. We are going to come back with our friends soon.",
        "Across the road from Santa Monica Pier is exactly where you want to be when visiting Santa Monica, as well as not far from lots of shops and restaurants/bars.Hotel itself is very new & modern, rooms were great. Comfortable beds & possibly the best shower ever!"
    ];
    protected $criteriasName = [
        "room", "apartment", "chamber", "hotel", "property", "lodge", "resort", "staff", "service", "personnel", "he", "she"
        , "location", "spot", "breakfast", "bed", "sleep quality", "mattresses", "linens"
        , "food", "dinner", "lunch", "bathroom", "lavatory",
        "shower", "toilet", "bath", "pool", "spa", "wellness", "bar", "price", "bill"
    ];
    protected $positiveAttributeName = [
        "great", "good", "very nice", "amazing", "careful", "well", "helpful", "friendly", "clean", "cleanliness", "easy", "excellent", "top", "superb",
        "fantastic", "best", "comfortable", "perfect", "love", "going to come back", "made our stay", "was fun", "not far from"
    ];
    protected $negativeAttributeName = [
        "problem", "unfriendly", "horrible", "stue", "unsupported", "average", "dirty", "unsupported", "didn't work", "bad", "ancient", "cold", "tiny", "small",
        "hard", "uncomfortable", "torn", "Stay away", "old", "decrepit", "terrible", "broken", "junk", "awful", "worst", "disgusting", "falling out", "minty",
        "thin", "nightmare", "freezing", "didn't sleep", "rude", "undisciplined", "fell off", "rotten", "mess", "surly", "not going to come back",
    ];
    protected $separatorName = [
        ".", ",", "&", "-", "but", "as well as"
    ];
    protected $reviews = [];
    protected $criterias = [];
    protected $positiveAttributes = [];
    protected $negativeAttributes = [];
    protected $separators = [];
    protected $arrayPositiveRegEx = [];
    protected $arrayNegativeRegEx = [];
    protected $analizeTextService;

    private function initializeReviews() {
        for ($i = 0; $i < count($this->reviewsDescription); $i++) {
            $review = new Review();
            $review->setDescription($this->reviewsDescription[$i]);

            $this->reviews[] = $review;
        }
    }

    private function initializeCriterias() {
        for ($i = 0; $i < count($this->criteriasName); $i++) {
            $criteria = new Criteria();
            $criteria->setName($this->criteriasName[$i]);

            $this->criterias[] = $criteria;
        }
    }

    private function initializePositiveAttributes() {
        for ($i = 0; $i < count($this->positiveAttributeName); $i++) {
            $positiveAttribute = new PositiveAttribute();
            $positiveAttribute->setName($this->positiveAttributeName[$i]);
            $this->positiveAttributes[] = $positiveAttribute;
        }
    }

    private function initializeNegativeAttributes() {
        for ($i = 0; $i < count($this->negativeAttributeName); $i++) {
            $negativeAttribute = new NegativeAttribute();
            $negativeAttribute->setName($this->negativeAttributeName[$i]);
            $this->negativeAttributes[] = $negativeAttribute;
        }
    }

    private function initializeSeparators() {
        for ($i = 0; $i < count($this->separatorName); $i++) {

            $separator = new Separator();
            $separator->setSeparator($this->separatorName[$i]);
            $separator->setIsSymbol(true);
            if (ctype_alpha($separator->getSeparator())) {
                $separator->setIsSymbol(false);
            }

            $this->separators[] = $separator;
        }
    }

    public function setUp() {
        // Get test Reviews.
        $this->initializeReviews();
        // Get Criterias.
        $this->initializeCriterias();
        // Get Positive attributes.
        $this->initializePositiveAttributes();
        // Get Negative attributes.
        $this->initializeNegativeAttributes();
        // Get separators
        $this->initializeSeparators();
        $this->analizeTextService = new AnalizeTextService();
        $this->arrayPositiveRegEx = $this->analizeTextService->getRegEx($this->positiveAttributes);
        $this->arrayNegativeRegEx = $this->analizeTextService->getRegEx($this->negativeAttributes);
    }

    /**
     * @test
     * calculate every score review
     */
    public function testCalculateScore() {

        $reviewService = $this->createMock(ReviewService::class);
        $this->analizeTextService = new AnalizeTextService();
        $entityManager = $this
                ->getMockBuilder(EntityManager::class)
                ->disableOriginalConstructor()
                ->getMock();


        $reviewScoreService = new ReviewScoreService($entityManager, $reviewService);



        foreach ($this->reviews as $key => $review) {
            $reviewScore = new ReviewScore($review);
            // Calculate positive attributes.
            $reviewScore = $this->analizeTextService->calculateScore($reviewScore, $this->criterias, $this->arrayPositiveRegEx, $this->separators, true);
            // Calculate negative attributes.
            $reviewScore = $this->analizeTextService->calculateScore($reviewScore, $this->criterias, $this->arrayNegativeRegEx, $this->separators, false);

            switch ($key) {

                case 0: // "The location is perfect but the room is very small. We are not going to come back.",
                    $this->assertEquals(-1, $reviewScore->getScore());
                    $this->assertEquals("location is perfect, room is very small, not going to come back", $reviewScore->getMatches());
                    break;
                case 1: // "I was excited to stay at this Hotel. It looked cute and was reasonable. It turned out to be terrible. We were woken up both mornings at 5:45 AM by noisy neighbors. The shower was clogged up...the room was sooooo small we kept tripping over each other. The saving grace was the pool at the Loews next door. I wish we had paid an extra $50 and stayed at a nicer place. This motel should cost no more than $99 a night.",
                    $this->assertEquals(-2, $reviewScore->getScore());
                    $this->assertEquals("terrible, room was sooooo small", $reviewScore->getMatches());
                    break;
                case 2: // "Terrible. Old, not quite clean. Lost my reservation, then \"found\" a smaller room, for the same price, of course. Noisy. Absolutely no parking, unless you luck out for the $10 spaces (of which there are 12). Water in bathroom sink would not turn off. Not hair dryer, no iron in room. Miniscule shower- better be thin to use it!",
                    $this->assertEquals(-5, $reviewScore->getScore());
                    $this->assertEquals("terrible, old, not quite clean, smaller room, thin", $reviewScore->getMatches());
                    break;
                case 3: // "I have stayed here 4 or 5 times while visiting LA. Despite travelling all over the world and staying in some of the best international hotels ( for work), Hotel Caliornia is one of my absolute favourites. Perfect location, right on the beach. I love the way you can just open your door and be outside, no elevators, corridors big glass windows. The ambience is so nice, retro perfect. As for the staff, I have had consistently superb service, with much more personal service and attention to detail than is usual in bigger hotels. Aaron and Katy were just two who have been exemplary this time but really everyone is terrific. Can't recommend it highly enough.",
                    $this->assertEquals(4, $reviewScore->getScore());
                    $this->assertEquals("best international hotel, Perfect location, love, superb service", $reviewScore->getMatches());
                    break;
                case 4: // "Found this hotel by reading over tripadvisor while planning a little beach getaway. Really good price by the beach. James the front desk manager was really fun, he made our stay more fun than we thought it would be. We are going to come back with our friends soon.",
                    $this->assertEquals(3, $reviewScore->getScore());
                    $this->assertEquals("good price, he made our stay, going to come back", $reviewScore->getMatches());
                    break;
                case 5: // "Across the road from Santa Monica Pier is exactly where you want to be when visiting Santa Monica, as well as not far from lots of shops and restaurants/bars.Hotel itself is very new & modern, rooms were great. Comfortable beds & possibly the best shower ever!"
                    $this->assertEquals(4, $reviewScore->getScore());
                    $this->assertEquals("not far from lots of shops and restaurants/bar, rooms were great, Comfortable bed, best shower", $reviewScore->getMatches());
                    break;
            }
        }
    }

    /**
     * @test
     * It can't analize the review if doesn't exist Criterias
     */
    public function testMandatoryElementsExceptionCriterias() {

        $this->criterias = [];

        try {
            $reviewScore = new ReviewScore($this->reviews[0]);
            $reviewScore = $this->analizeTextService->calculateScore($reviewScore, $this->criterias, $this->arrayPositiveRegEx, $this->separators, true);
        } catch (MandatoryElementsTextAnalizeException $ex) {
            $this->expectExceptionCode($ex->getCode());
            $this->expectExceptionMessage($ex->getMessage());

            $this->assertEquals(MandatoryElementsTextAnalizeException::CODE_NOT_EXISTS_CRITERIAS, $ex->getCode());
            $this->assertEquals(MandatoryElementsTextAnalizeException::MSG_NOT_EXISTS_CRITERIAS, $ex->getMessage());
            $this->assertEquals('app_topic_index', $ex->getRedirectAction());
        }
    }

    /**
     * @test
     * It can't analize the review if doesn't exist Positive attributes
     */
    public function testMandatoryElementsExceptionPositiveAttributes() {

        $this->arrayPositiveRegEx = [];

        try {
            $reviewScore = new ReviewScore($this->reviews[0]);
            $reviewScore = $this->analizeTextService->calculateScore($reviewScore, $this->criterias, $this->arrayPositiveRegEx, $this->separators, true);
        } catch (MandatoryElementsTextAnalizeException $ex) {
            $this->expectExceptionCode($ex->getCode());
            $this->expectExceptionMessage($ex->getMessage());

            $this->assertEquals('app_positive_attribute_index', $ex->getRedirectAction());
            $this->assertEquals(MandatoryElementsTextAnalizeException::CODE_NOT_EXISTS_POSITIVE_ATTRIBUTES, $ex->getCode());
            $this->assertEquals(MandatoryElementsTextAnalizeException::MSG_NOT_EXISTS_POSITIVE_ATTRIBUTES, $ex->getMessage());
        }
    }

    /**
     * @test
     * It can't analize the review if doesn't exist Negative attributes
     */
    public function testMandatoryElementsExceptionNegativeAttributes() {

        $this->arrayNegativeRegEx = [];

        try {
            $reviewScore = new ReviewScore($this->reviews[0]);
            $reviewScore = $this->analizeTextService->calculateScore($reviewScore, $this->criterias, $this->arrayNegativeRegEx, $this->separators, false);
        } catch (MandatoryElementsTextAnalizeException $ex) {
            $this->expectExceptionCode($ex->getCode());
            $this->expectExceptionMessage($ex->getMessage());

            $this->assertEquals('app_negative_attribute_index', $ex->getRedirectAction());
            $this->assertEquals(MandatoryElementsTextAnalizeException::CODE_NOT_EXISTS_NEGATIVE_ATTRIBUTES, $ex->getCode());
            $this->assertEquals(MandatoryElementsTextAnalizeException::MSG_NOT_EXISTS_NEGATIVE_ATTRIBUTES, $ex->getMessage());
        }
    }

}
