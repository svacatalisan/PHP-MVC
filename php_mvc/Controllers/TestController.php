<?php

class TestController extends BaseController {

    private $itest;
    private $idbs;

    public function __construct(ITest $itest, IDBService $idbs) {
        $this->itest = $itest;
        $this->idbs = $idbs;
    }

    public function index() {
		$test = 'this is just a test';
        $this->ReturnView($test);
    }
	
	public function show(){
		$data = array('p1' => 'a', 'p2' => 'b', 'p3' => 'c', 'p4' => 'd');
		$views = array('Index', 'Show');
		$this->ReturnView($data, 'Show');
	}

}

?>