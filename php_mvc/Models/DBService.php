<?php
	
	class DBService implements IDBService{
		public function __construct(){}
	
		public function load(){
			return 'load';
		}
		
		public function store(){
			return 'DBService concrete implementation level1 <br/><br/>';
		}
	}
?>