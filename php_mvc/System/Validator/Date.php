<?php

class Date implements IValidator {

    private $message = null;
    private $dateformat = 'yyyy-mm-dd';

    /**
     * Verifies if the $value is of Date type
     * @param String $value
     * @return boolean 
     */
    public function isValid($value) {

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            //verify if the string has the yyyy-mm-dd format
            $this->message = 'The date format should be ' . $this->dateformat;
            return false;
        }

        list($year, $month, $day) = sscanf($value, '%d-%d-%d');
        //parses the string according to the format digit(s)-digit(s)-digit(s)
        //and assign the values to their corresponding variables

        if (!checkdate($month, $day, $year)) { //verify if the date is correct
            $this->message = "The provided date is invalid";

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

