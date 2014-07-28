<?php

class StringLength implements IValidator {

    protected $message = array();
    protected $max = 255;
    protected $min = 0;

    /**
     * Getter for the min propriety
     * @return Int
     */
    public function getMin() {
        return $this->min;
    }

    /**
     * Setter for the min propriety
     * @param Int $min
     */
    public function setMin($min) {
        if ($min <= $this->max) {
            $this->min = $min;
        } else {
            $this->message[] = 'The min value must be less than or equal to $max';
        }
    }

    /**
     * Getter for the max propriety
     * @return Int
     */
    public function getMax() {
        return $this->max;
    }

    /**
     * Setter for the min propriety
     * @param Int $max
     */
    public function setMax($max) {
        if ($max >= $this->min) {
            $this->max = $max;
        } else {
            $this->message[] = 'The max value must be grater than or equal to $min';
        }
    }

    /**
     * Verifies if $value's length is between min and max
     * @param String $value
     * @return boolean
     */
    public function isValid($value) {
        if (!is_string($value)) {
            $this->message[] = 'The parameter should be a string';
            return false;
        }

        $length = mb_strlen($value); //get the length of the string

        if (is_int($this->min) & $length < $this->min) {
            $this->message[] = 'The provided string is too short.';
            return false;
        }

        if (is_int($this->min) & $this->max < $length) {
            $this->message[] = 'The provided string is too long.';
            return false;
        }
    }

    /**
     * Gets the error messages
     * @return array 
     */
    public function getMessages() {
        return $this->message;
    }

}

