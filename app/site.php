<?php

class site extends controller{
	function __construct()
	{
		parent::__construct();
	}

	public function index(){
		parent::view('view/iniview.php');
	}
}
