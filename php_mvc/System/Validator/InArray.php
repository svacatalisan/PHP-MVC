<?php

require_once 'IValidator.php';

class InArray implements IValidator {

    protected $message = null;
    protected $haystack = array();

    /**
     * Getter for the haystack propriety
     * @return array
     */
    public function getHaystack() {
        return $this->haystack;
    }

    /**
     * Setter for the haystack propriety    
     * @param array $haystack
     */
    public function setHaystack(array $haystack) {
        $this->haystack = $haystack;
    }

    /**
     * Recursive function for searching a multidimensional array
     * @param mixed $needle
     * @param array $haystack
     * @return boolean true if the needle is found, else otherwise 
     */
    protected function in_array_r($needle, $haystack) {
        foreach ($haystack as $item) {
            if (($item == $needle) || (is_array($item) && $this->in_array_r($needle, $item))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns true if and only if $value is contained in the haystack option. 
     *
     * @param  mixed $value
     * @return boolean
     */
    public function isValid($value) {
        return $this->in_array_r($value, $this->haystack);
    }

    /**
     * Gets the error messages
     * @return String 
     */
    public function getMessages() {
        return $this->message;
    }

}
