<?php
	class FrontControllerTests extends \PHPUnit_Framework_TestCase{

		public $frontController;

		protected function setUp(){
			$this->frontController = $this->getMockBuilder('FrontController')
										  ->setMethods(array('getInstance'))
										  ->getMock();
		}

		public function checkInstances(){

		}

	}
?>