<?php
class modelku extends model{

	function ___construct()
	{ 
		/*buat ngeload constructor dari parentnya yakni model karena extendsnya atau parentnya menggunakan class model*/
		parent::__construct();
	}

	function getdata($table)
	{
		/*function getdata adalah menampilkan data dari table yang diminta sesuai parsing variable table dari controllernya.
		parent::get adalah salah satu fungsi dari library sincproject pada class model yang menjadi parent class modelku.
		*/
		return parent::get($table,"","array","","","","");
	}

	function savedata($table,$post,$where=""){
		return parent::store($table,$post,$where);
	}
	function deletedata($id){
		parent::query("DELETE FROM table_name WHERE id='".$id."'");
	}
}