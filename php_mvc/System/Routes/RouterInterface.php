<?php
interface RouterInterface{

		//setters and getters for each property
        public function setSchema($schema);
		
		public function getSchema();
        
		public function setControllerName($name);
		
		public function getControllerName();
		
		public function setActionName($name);
		
		public function getActionName();
		
		public function setParams($params);
		
		public function getParams();
        
        public function setRequestType($request);
		
		public function getRequestType();
	

}

?>
