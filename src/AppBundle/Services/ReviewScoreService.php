<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\ReviewScore;
use AppBundle\Services\ReviewService;
use AppBundle\Interfaces\Services\CrudInterface;

/**
 * Description of ReviewScoreService
 *
 * @author Daniel
 */
class ReviewScoreService implements CrudInterface {

    const ENTITY_NAME = 'AppBundle:ReviewScore';
    const NEGATION = 'not';

    private $em;
    private $reviewService;

    public function __construct(EntityManager $em, ReviewService $reviewService) {
        $this->em = $em;
        $this->reviewService = $reviewService;
    }

    /**
     * insert ReviewScore into table review_scores
     * @param ReviewScore $reviewScore
     */
    public function create($reviewScore) {
        $this->em->persist($reviewScore);
        $this->em->flush($reviewScore);
    }

    /**
     * Update ReviewScore from table review_scores
     * @param ReviewScore $reviewScore
     */
    public function update($reviewScore) {
        $this->em->flush($reviewScore);
    }

    /**
     * Delete ReviewScore from table review_scores
     * @param ReviewScore $reviewScore
     */
    public function delete($reviewScore) {

        $this->em->remove($reviewScore);
        $this->em->flush();
    }

    /**
     * Get ReviewScore by id
     * @param integer $id
     */
    public function findById($id) {
        return $this->em->getRepository(self::ENTITY_NAME)->find($id);
    }

    /**
     * Calculate score and matches from review description
     * @param ReviewScore $reviewScore
     * @param array Criteria $criterias
     * @param array $arrayPositiveRegEx
     * @param array Separator $separators
     * @param boolean $calculatePositive
     * @return ReviewScore
     */
    public function calculateScore(ReviewScore $reviewScore, $criterias, array $arrayPositiveRegEx, $separators, $calculatePositive) {

        $reviewDescription = str_replace(PHP_EOL, '', $reviewScore->getReview()->getDescription());

        foreach ($arrayPositiveRegEx as $pattern) {

            if (preg_match_all($pattern, $reviewDescription, $matches, PREG_OFFSET_CAPTURE)) {

                foreach ($this->deleteDuplicate($matches[0]) as $attribute) {

                    $match = $this->searchByCriteriaAttribute($reviewDescription, $criterias, $attribute, $reviewScore, $separators);

                    if ($match !== null) {
                        $reviewScore = $this->calculation($attribute[1], $reviewScore, $match, $calculatePositive);
                    } else {
                        $match = $this->searchPositiveAttributeWithNegation($calculatePositive, $attribute, $reviewDescription, $reviewScore, $separators);

                        if ($match !== null) {
                            $reviewScore = $this->calculation($attribute[1], $reviewScore, $match, false);
                        } else if ($this->isAttributeInReview($this->getReviewDescriptionWithoutSeparators($reviewDescription, $separators), $attribute[0], $reviewScore)) {
                            $reviewScore = $this->calculation($attribute[1], $reviewScore, $attribute[0], $calculatePositive);
                        }
                    }
                }
            }
        }

        return $reviewScore;
    }

    /**
     * Check if review description contains attribute name
     * @param string $reviewDescription
     * @param string $attribute
     * @param ReviewScore $reviewScore
     * @return boolean
     */
    private function isAttributeInReview($reviewDescription, $attribute, ReviewScore $reviewScore) {
        return strripos($reviewDescription, $attribute) !== false &&
                strripos($reviewScore->getMatches(), $attribute) === false;
    }

    /**
     * Search match by the pattern:  /\bnot.*\bAttribute/i
     * @param boolean $calculatePositive
     * @param array $attribute
     * @param string $reviewDescription
     * @param ReviewScore $reviewScore
     * @param Separator $separators
     * @return string match 
     */
    private function searchPositiveAttributeWithNegation($calculatePositive, $attribute, $reviewDescription, $reviewScore, $separators) {

        if ($calculatePositive) {
            $negativePattern = "/\b" . self::NEGATION . ".*\b" . $attribute[0] . "/i";
            $match = $this->getMatchFromReview($negativePattern, $reviewDescription, $separators);

            if ($match !== null && strripos($reviewScore->getMatches(), $match) === false) {
                return $match;
            }
        }
        return null;
    }

    /**
     * Search match by the pattern:  /\bAttributeName.*\bCriteriaName|\bCriteriaName.*\bAttribute/i
     * @param string $reviewDescription
     * @param listObj $criterias
     * @param array $attribute
     * @param ReviewScore $reviewScore
     * @param Separator $separators
     * @return string match 
     */
    private function searchByCriteriaAttribute($reviewDescription, $criterias, $attribute, $reviewScore, $separators) {

        foreach ($criterias as $criteria) {
            $criteriaName = $criteria->getName();
            $pattern = "/\b" . $attribute[0] . ".*\b" . $criteriaName . "|\b" . $criteriaName . ".*\b" . $attribute[0] . "/i";
            $match = $this->getMatchFromReview($pattern, $reviewDescription, $separators);

            if ($match !== null && strripos($reviewScore->getMatches(), $match) === false) {
                return $match;
            }
        }

        return null;
    }

    /**
     * Build RegEx array with the attributesList. 
     * Every array's position can contain maximum 5 attributes
     * Pattern RegEx: /attr1|attr2|attr3|attr4|attr5/i
     * @param Colletion $attributesList
     * @return array RegEx
     */
    public function getRegEx($attributesList) {

        $subArray = array_chunk($attributesList, 5);
        $i = 0;
        $arrayRegEx = array();
        foreach ($subArray as $attributes) {
            $regEx = "/";
            foreach ($attributes as $key => $attribute) {
                if ($key == 0) {
                    $regEx .= $attribute->getName();
                } else {
                    $regEx .= "|" . $attribute->getName();
                }
            }
            $arrayRegEx[$i] = $regEx . "/i";
            $i++;
        }

        return $arrayRegEx;
    }

    /**
     * Split review description through the regular expresion:~separator1|separator2|separator3~  
     * sample RegEx: ~\.|,|&|-|\bbut\b|\bas well as\b~
     * @param string $reviewDescription
     * @param array AppBundle\Entity\Separator $separators
     * @return array string
     */
    private function splitReviewDescription($reviewDescription, $separators) {

        $pattern = "~";
        foreach ($separators as $key => $separator) {
            $valueSeparator = $separator->getSeparator();

            if ($valueSeparator == ".") {
                $pattern .= "\\";
            }
            if ($key === 0) {
                $pattern .= $separator->getIsSymbol() ? $valueSeparator : "\b" . $valueSeparator . "\b";
            } else {
                $pattern .= $separator->getIsSymbol() ? ("|" . $valueSeparator) : ("|" . "\b" . $valueSeparator . "\b");
            }
        }
        $pattern .= "~";

        return preg_split($pattern, $reviewDescription);
    }

    /**
     * Return review description without separators(.|,|&|but|as well as|)
     * @param string $reviewDescription
     * @param array AppBundle\Entity\Separator $separators
     * @return string
     */
    private function getReviewDescriptionWithoutSeparators($reviewDescription, $separators) {
        $arrayReviewDescription = $this->splitReviewDescription($reviewDescription, $separators);
        return implode($arrayReviewDescription);
    }

    private function deleteDuplicate($matches) {
        $result = array();
        $i = 0;

        foreach ($matches as $attributes) {
            $j = 0;
            foreach ($attributes as $attribute) {
                if (!in_array($attribute, $result)) {
                    $result[$i][$j] = strtolower($attribute);
                    $j++;
                }
            }
            $i++;
        }
        return $result;
    }

    /**
     * Evaluate reviewDescription through the param $pattern(Regular expression). 
     * If the regEx is ok, return a match(string). Otherwise it'll return null.
     * @param string $pattern
     * @param string $reviewDescription
     * @param array AppBundle\Entity\Separator $separators
     * @return string match OR null
     */
    private function getMatchFromReview($pattern, $reviewDescription, $separators) {

        $reviewSplit = $this->splitReviewDescription($reviewDescription, $separators);
        foreach ($reviewSplit as $reviewSentence) {
            if (preg_match($pattern, $reviewSentence, $matches)) {
                return $matches[0];
            }
        }

        return null;
    }

    private function calculation($position, ReviewScore $reviewScore, $word, $calculatePositive) {

        if ($calculatePositive) {
            $reviewScore->setScore($reviewScore->getScore() + 1);
        } else {
            $reviewScore->setScore($reviewScore->getScore() - 1);
        }

        $this->setMatchesReview($position, $reviewScore, $word);

        return $reviewScore;
    }

    private function setMatchesReview($position, ReviewScore $reviewScore, $word) {

        if (empty($reviewScore->getMatchesArray())) {
            $reviewScore->setMatchesArray(array($position => $word));
        } else {
            $reviewScore->setMatchesArray($reviewScore->getMatchesArray() + array($position => $word));
        }
    }

    /**
     * Get all review score or filter by reviewName
     * @param string $reviewName
     * @return ReviewScore
     */
    public function getScoreFilterAjax($reviewName) {
        if (!empty($reviewName)) {
            $reviewsScore = $this->findLikeReviewDescription($reviewName);
        } else {
            $reviewsScore = $this->findAllReviewScore();
        }

        $reviews = $this->reviewService->getReviewsFilterAjax($reviewName);
        foreach ($reviews as $review) {
            if (!$this->isExistReview($review->getId(), $reviewsScore)) {
                $reviewScore = new ReviewScore($review);
                $reviewsScore[] = $reviewScore;
            }
        }

        return $reviewsScore;
    }

    private function isExistReview($idReview, $reviewsScore) {
        foreach ($reviewsScore as $reviewScore) {
            if ($reviewScore->getReview()->getId() === $idReview) {
                return true;
            }
        }

        return false;
    }

    /**
     * Delete all scores form table: review_scores
     */
    public function removeScores() {
        $scores = $this->findAllOrderedById();

        foreach ($scores as $score) {
            $this->delete($score);
        }
    }

    /**
     * Get all scores
     */
    public function findAllReviewScore() {
        return $this->em->getRepository(self::ENTITY_NAME)->findAllReviewScore();
    }

    /**
     * Get all scores
     */
    public function findAllOrderedById() {
        return $this->em->getRepository(self::ENTITY_NAME)->findAllOrderedById();
    }

    /**
     * Get score filtered by review Description
     */
    public function findLikeReviewDescription($reviewDescription) {
        return $this->em->getRepository(self::ENTITY_NAME)->findLikeReviewDescription($reviewDescription);
    }
    
    
    /**
     * Get ReviewScore by idReview
     * @param integer $idReview
     * @return ReviewScore
     */
    public function findByReviewId($idReview) {
        return $this->em->getRepository(self::ENTITY_NAME)->findByReviewId($idReview);
    }

}
