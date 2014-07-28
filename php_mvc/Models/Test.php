<?php
	
	class Test implements ITest{
		private $idbc;
	
		public function __construct(IDBConnector $idbc){
			$this->idbc = $idbc;
		}
		
		function show(){
			return 'Test concrete implementation level 1 <br/>'.$this->idbc->show();
		}
		
		function hide(){
			return $this->idbc->hide();
		}
	}
?>