<?php
	class FloatValidator implements IValidator{

		private static $generalErrorMessages = array( 
										  			  'INVALID_FORMAT' => 'The e-mail address you entered is not in a correct format!',
										  			);

		private $returnMessages = array();

		public function isValid($value){
			if (is_float($value)){
				return true;
			}
			else{
				$this->setMessage($this->generalErrorMessages['INVALID_FORMAT']);
				return false;
			}

		}

		private function setMessage($message){
			array_push($this->returnMessages, $message);
		}

		public function getMessages(){
			return $this->returnMessages;			
		}

	}
?>