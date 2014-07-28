<?php

class AlNum implements IValidator {

    private $message = null;

    /**
     * Verifies if a string is composed only from alphanumeric characters
     * @param String $value
     * @return boolean true if the value is a alphanumeric string, false otherwise
     */
    public function isValid($value) {
        if ('' === $value) {
            $this->message = "The inserted value is an empty string.";
            return false;
        }

        $pattern = '/[^a-zA-Z0-9]/';  //the alphanumeric pattern

        if (preg_match($pattern, $value) === 1) {  //verify if $value respects the pattern
            return true;
        } else {
            $this->message = "The inserted value does not contain only alphanumeric characters";
            return false;
        }
    }

    /**
     * Gets the error messages
     * @return String 
     */
    public function getMessages() {
        return $this->message;
    }

}
