<?php

class Password implements IValidator {

    private $message = null;

    /**
     * Verifies if a password is at least 8 characters long, contains at least
     * one uppercase letter, one lowercase letter and at least one digit.
     * @param String $value
     * @return boolean true if the pattern is matched, false otherwise
     */
    public function isValid($value) {
        if ('' === $value) {
            $this->message = "The inserted value is an empty string.";
            return false;
        }

        $pattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/'; //the password pattern


        if (preg_match($pattern, $value) === 1) {
            //verify if the pattern is matched
            return true;
        } else {
            $this->message = "The password must be at least 8 characters long and must contain at least a one upper case letter, one lower case letter and one numeric digit.";
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