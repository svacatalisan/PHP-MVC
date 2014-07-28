<?php
	
	class ChainValidator{
		
		private $messages = array();
		private $validators = array();
		private $expectedInterface = 'IValidator';
		
		/*
		* Attaches a new validator at the end of the queue.
		*/
		public function addValidator(IValidator $validator, $breakChainOnFailure = false, $priority = 0){
			$key = get_class($validator);

			if (!array_key_exists($key, $this->validators)) {
				if ($priority){
					$this->preAppendValidator($validator, $breakChainOnFailure);
				}else{
					$element = array('validator' => $validator, 'breakChain' => $breakChainOnFailure);
					$this->validators = array_push_assoc($this->validators, $key, $element);
				}
			}
		}

		/*
		* Attaches a new validator at the begining of the queue.
		*/
		public function preAppendValidator(IValidator $validator, $breakChainOnFailure = false){
			$key = get_class($validator);

			if (!array_key_exists($key, $this->validators)) {
				$element = array($key => array('validator' => $validator, 'breakChain' => $breakChainOnFailure));
				$this->validators = $element + $this->validators;
			}
		}

		/*
		* instantiates validators based on their names.
		* param $validators represents an array containing validators name.
		*/
		public function loadValidators($validators){
			if (is_array($validators)){
				foreach($validators as $value){
					$this->loadValidator($value);	
				}
			}elseif (is_string($validators)){
				$this->loadValidator($validators);
			}
		}

		/*
		* instantiates a validator based on its name.
		* param $validator represent string containig validator name.
		*/
		public function loadValidator($validator){
			if(is_string($validator)){
				$implementedInterfaces = new ReflectionClass($validator)->getInterfaceNames();
				if (in_array($this->expectedInterface, $implementedInterfaces){
					$validatorInstance = new $validator;
					$this->addValidator($validatorInstance);
				}
				else{
					die("The required validator doesn't exists");
				}
			}else{
				die("The parameter should be a string value!");
			}
		}
		
		/*
		* Detach a validator based on validator name which represents the associative array key.
		*/
		public function dettachValidator(IValidator $validator){
			$key = get_class($validator);
			
			if (array_key_exists($key, $this->validators)){
				unset($this->validators[$validatorKeyName]);
			}
		}
		
		/*
		* Returns all validators attached since then.
		*/
		public function getValidators(){
			return $this->validators;
		}
		
		/*
		* Returns all failure messages.
		*/
		public function getMessages(){
			return $this->messages;
		}
		
		/*
		* Returns true if and only if $value passes all validations in the chain
		*/
		public function isValid($value){
			//checks each validator and decide if breaking down or not if one of them is not valid.
			foreach($this->validators as $key => $value){
				//$value parameter is actually an array containing validator object and the boolean value for 
				//breaking down if the validation fails.
				$validator = $value['validator'];
				$breakOnFailure = $value['breakChain'];
				$isValid = $validator->isValid($value);
				if (!$isValid){
					//if invalid merges the new error messages in the resulting array.
					$this->messages = array_merge($this->messages, $validator->getMessages());
					//if $breakOnFailure is true then the execution stops and the false response is sent.
					if ($breakOnFailure){
						return false;
					}
				}
				//if no validator fails or if some validators fails but the execution continues then a specific response is sent back.
				return count($this->messages) == 0 ? true : false;
			}
		}
		
		/*
		* Return the count of attached validators
		*/
		public function countValidators(){
			return count($this->validators);
		}
		
		private function array_push_assoc($array, $key, $value){
			$array[$key] = $value;
			return $array;
		}
	}
	
?>