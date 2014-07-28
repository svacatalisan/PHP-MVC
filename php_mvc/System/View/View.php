<?php

	class View{
		
		const ROOT = 'Views/';
		private $path = array();
		private $controller;
		private $action;
		private $defaultPath;
		
		public function __construct($context){
			$this->controller = get_class($context);
			$this->action = $context->getActionName();
			$this->defaultPath = $this->controller.'/'.$this->action;
			$this->setPath($this->defaultPath);
		}
		
		//setters and getters for views
		public function setPath($viewsPaths){
			if (isset($viewsPaths)){
				if (is_array($viewsPaths)){
					foreach($viewsPaths as $current){
						$this->checkAndBuildPartialPath($current);
					}
				}
				else{
					$this->checkAndBuildPartialPath($viewsPaths);
				}
			}
		}
		
		public function getPath(){
			return $this->path;
		}
		
		/*
		* Renders the view. Decompresses the data and make the real include in the current html page.
		*/
		public function render($viewModel){
			extract($viewModel);
			foreach ($this->path as $currentKey => $currentValue){
				include $currentValue;
			}
		}
		
		/*
		* Builds the physical path to include the view.
		*/
		private function generateIncludeFilePath($path){
			return self::ROOT. $path .'.html';
		}
		
		/*
		* Based on an initial array, inserts a new associative element based on $key and $value parameters.
		*/
		private function array_push_assoc($array, $key, $value){
			$array[$key] = $value;
			return $array;
		}
		
		/*
		* Checks the path format and formats it into the correct way. If the path it's ok it's pushed in the deliverable array of paths.
		*/
		private function checkAndBuildPartialPath($path){
			$components = explode('/',$path);
			if (count($components) == 1){
				$path = $this->controller.'/'.$path;
			}
			if (!in_array($path, $this->path)){
				$this->path = $this->array_push_assoc($this->path, $path, $this->generateIncludeFilePath($path));
			}
		}
	}
?>