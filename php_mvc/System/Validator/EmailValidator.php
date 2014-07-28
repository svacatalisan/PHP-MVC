<?php
	class EmailValidator implements IValidator{

		private static $generalErrorMessages = array( 'INVALID' => 'The e-mail address must be a string!',
										  			  'INVALID_FORMAT' => 'The e-mail address you entered is not in a correct format!',
										  			);

		private $returnMessages = array();

		public function isValid($value){
			if (!is_string($value)){
				$this->setMessage($this->generalErrorMessages['INVALID']);
			}

			if (!$this->splitEmailParts($value)) {
				$this->setMessage($this->generalErrorMessages['INVALID_FORMAT']);
			}

			return true;

		}

		protected function splitEmailParts($value)
	    {
	        // Split email address up and disallow '..'
	        if ((strpos($value, '..') !== false) or
	            (!preg_match('/^(.+)@([^@]+)$/', $value, $matches))) {
	            return false;
	        }
	        return true;
	    }

		private function setMessage($message){
			array_push($this->returnMessages, $message);
		}

		public function getMessages(){
			return $this->returnMessages;			
		}
		
	}
?>