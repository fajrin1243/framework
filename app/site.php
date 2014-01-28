<?php

class site extends controller{
	function __construct()
	{
		parent::__construct();
	}

	public function index(){
		$modelku = parent::get("app/modelku");
		$data = $modelku->getdata("siswa");
		print_r($data);
		echo "<br>";
		foreach ($data as $key) {
			echo $key->id;
			echo $key->nama;
			echo "</br>";
		}
	}

	public function create(){
		echo parent::view("view/input");
	}

	public function save(){
		$id = $_POST['id'];
		$nama = $_POST['nama'];

		$model = parent::get("app/modelku");
		$model->savedata("siswa",array('id' => $id, 'nama'=>$nama));
	}
}
