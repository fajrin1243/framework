<?php

/* 	Class Error
*/

Class Error
{
	
	public static function set($type="")
	{	
		switch($type)
		{
			default:
			$msg = "Page Not Found";
			break;
		}
		
		echo "lalala";
		//echo get::view(app_route::config("error").".php",array("msg"=>$msg));
	}
}

?>