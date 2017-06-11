<?php

namespace AppBundle\Services;

use AppBundle\Entity\ReviewScore;
use AppBundle\Entity\Separator;
use AppBundle\Exception\MandatoryElementsTextAnalizeException;

/**
 * AnalizeTextService
 *
 * @author Daniel
 */
class AnalizeTextService {

    const NEGATION = 'not';

    /**
     * Calculate score and matches from review description
     * @param ReviewScore $reviewScore
     * @param array Criteria $criterias
     * @param array $arrayAttributeRegEx
     * @param array Separator $separators
     * @param boolean $calculatePositive
     * @return ReviewScore
     */
    public function calculateScore(ReviewScore $reviewScore, $criterias, array $arrayAttributeRegEx, $separators, $calculatePositive) {
        $this->checkMandatoryData($criterias, $arrayAttributeRegEx, $calculatePositive);
        $reviewDescription = str_replace(PHP_EOL, '', $reviewScore->getReview()->getDescription());

        foreach ($arrayAttributeRegEx as $pattern) {

            if (preg_match_all($pattern, $reviewDescription, $matches, PREG_OFFSET_CAPTURE)) {

                foreach ($this->deleteDuplicate($matches[0]) as $attribute) {

                    $match = $this->searchByCriteriaAttribute($reviewDescription, $criterias, $attribute, $reviewScore, $separators);

                    if ($match !== null) {
                        $reviewScore = $this->setScoreAndMatches($attribute[1], $reviewScore, $match, $calculatePositive);
                    } else {
                        $match = $this->searchPositiveAttributeWithNegation($calculatePositive, $attribute, $reviewDescription, $reviewScore, $separators);

                        if ($match !== null) {
                            $reviewScore = $this->setScoreAndMatches($attribute[1], $reviewScore, $match, false);
                        } else if ($this->isAttributeInReview($this->getReviewDescriptionWithoutSeparators($reviewDescription, $separators), $attribute[0], $reviewScore)) {
                            $reviewScore = $this->setScoreAndMatches($attribute[1], $reviewScore, $attribute[0], $calculatePositive);
                        }
                    }
                }
            }
        }

        return $reviewScore;
    }

    /**
     * Check if any mandatory array exist
     * @param array $criterias
     * @param array $arrayAttributeRegEx
     * @param array $calculatePositive
     * @throws MandatoryElementsTextAnalizeException
     */
    private function checkMandatoryData($criterias, $arrayAttributeRegEx, $calculatePositive) {
        if (empty($criterias)) {
            throw new MandatoryElementsTextAnalizeException(MandatoryElementsTextAnalizeException::MSG_NOT_EXISTS_CRITERIAS, MandatoryElementsTextAnalizeException::CODE_NOT_EXISTS_CRITERIAS);
        } else if (empty($arrayAttributeRegEx)) {
            if ($calculatePositive) {
                throw new MandatoryElementsTextAnalizeException(MandatoryElementsTextAnalizeException::MSG_NOT_EXISTS_POSITIVE_ATTRIBUTES, MandatoryElementsTextAnalizeException::CODE_NOT_EXISTS_POSITIVE_ATTRIBUTES);
            } else {
                throw new MandatoryElementsTextAnalizeException(MandatoryElementsTextAnalizeException::MSG_NOT_EXISTS_NEGATIVE_ATTRIBUTES, MandatoryElementsTextAnalizeException::CODE_NOT_EXISTS_NEGATIVE_ATTRIBUTES);
            }
        }
    }

    /**
     * Check if review description contains attribute name
     * @param string $reviewDescription
     * @param string $attribute
     * @param ReviewScore $reviewScore
     * @return boolean
     */
    private function isAttributeInReview($reviewDescription, $attribute, ReviewScore $reviewScore) {

        return preg_match("/\b" . $attribute . "\b/i", $reviewDescription) &&
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

            if ($valueSeparator == ".") { // If the separator is '.', we add '\\' to build the regEx properly
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

    private function setScoreAndMatches($position, ReviewScore $reviewScore, $word, $calculatePositive) {

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

}
