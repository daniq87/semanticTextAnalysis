<?php

namespace AppBundle\Exception;

/**
 * Class MandatoryElementsTextAnalizeException
 *
 * Thrown when thre are not exists any mandatory elements
 * 
 * Code: 1 , Does not exist Criterias
 * Code: 2 , Does not exist Positive attributes
 * Code: 3 , Does not exist Negative attributes
 *
 * @package AppBundle\Exception
 * @author Daniel
 */
class MandatoryElementsTextAnalizeException extends \Exception {

    // Code errors
    const CODE_NOT_EXISTS_CRITERIAS = 1;
    const CODE_NOT_EXISTS_POSITIVE_ATTRIBUTES = 2;
    const CODE_NOT_EXISTS_NEGATIVE_ATTRIBUTES = 3;
    // Message exception
    const MSG_NOT_EXISTS_CRITERIAS = "Does not exist Criterias for any Topic. You need to create them to analyze the text properly";
    const MSG_NOT_EXISTS_POSITIVE_ATTRIBUTES = "Does not exist Positive attributes. You need to create them to analyze the text properly";
    const MSG_NOT_EXISTS_NEGATIVE_ATTRIBUTES = "Does not exist Negative attributes. You need to create them to analyze the text properly";

    /**
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * return the custom message of the exception
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    /**
     * Return the action name of the elements which do not exist
     */
    public function getRedirectAction() {
        $actionName = '';

        switch ($this->code) {
            case 1: $actionName = 'app_topic_index';
                break;
            case 2: $actionName = 'app_positive_attribute_index';
                break;
            case 3: $actionName = 'app_negative_attribute_index';
                break;
        }
        
        return $actionName;
    }

}
