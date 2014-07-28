<?php
	interface IValidator{
	
		/*
		* The method that will actually compute the validation.
		*/
		function isValid($value);
		
		/*
		* The method that will return all failure messages.
		*/
		function getMessages();
	}
?>