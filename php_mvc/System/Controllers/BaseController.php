<?php

	abstract class BaseController {
		
		private $controllerName;
		private $actionName;
		private $params;
		private $view;
        protected $db;
		
		protected function __construct() {
            global $config;
            $this->db = new Db($config);
        }

		abstract function Index();
		
		//getter and setter for controller
		public function getControllerName(){
			return $this->controllerName;
		}
		public function setControllerName($controller){
			$this->controllerName = $controller;
		}
		
		//getter and setter for action
		public function getActionName(){
			return $this->actionName;
		}
		public function setActionName($action){
			$this->actionName = $action;
		}
		
		//getter and setter for parameters
		public function getParams(){
			return $this->params;
		}
		public function setParams($params){
			$this->params = $params;
		}
		
		//getter and setter for view
		public function getView(){
			return $this->view;
		}
		public function setView($view){
			$this->view = $view;
		}
		
		/*
		* Returns all requested views based on their paths
		* $viewModel param -> the array of all model that need to be passed to the view.
		* $viewsPaths param -> the array of views that should be loaded in the view.
		*/
		public function ReturnView($viewModel, $viewsPaths){
			if (isset($viewsPaths)){
				$this->view->setPath($viewsPaths);
			}
			return $this->view->render($viewModel);
		}
	}

?>