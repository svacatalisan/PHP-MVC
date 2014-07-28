<?php

class Int implements IValidator {

    private $message = null;

    /**
     * Verifies if $value is of type Int
     * @param Int $value
     * @return boolean true if the value is int, false otherwise
     */
    public function isValid($value) {

        if (is_int($value)) {
            return true;
        } else {
            $this->message = "The inserted value is not an integer.";
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
