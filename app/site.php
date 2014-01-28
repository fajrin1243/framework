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
			echo "</br>";
			echo $key->nama;
			echo "</br>";
			echo "<a href='site/delete?id=".$key->id."'>Delete</a>";
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

	public function delete(){
		/*$id = $_GET['id'];
		parent::query("DELETE FROM siswa WHERE id='".$id."'");*/
		$id = $_GET['id'];
		$sql = "DELETE FROM siswa WHERE id='$id'";
		$result = mysql_query($sql);
	}

	public function update(){
		
	}
}
