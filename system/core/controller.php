<?php

use system\framework as framework;
use system\library\gethtml as gethtml;
use system\library\getcommon as getcommon;
use system\library\getutility as getutility;

Class controller
{

	function __construct()
	{
		self::instance();
	}

	private static $instance;
	
	public static function instance()
	{
		return self::$instance = framework::$instance;
	}
	
	public static function set( $path, $key="" , $object = "")
	{
		return framework::set( $path, $key , $object);
	}
	
	public static function setObjectValue( $key="" , $val = "")
	{
		return framework::setObjectValue( $key, $val);
	}
	
	public static function get( $key )
	{
		return framework::get( $key);
	}
	
	
	public static function ext($class="")
	{
		$getext = self::get('ext');
		return $getext->get($class);
	}
	
	public static function view($view="",$parser="")
	{
		if(!is_array($view))
		{
			$view = array($view);			
		}
		
		for($i=0;$i<count($view);$i++)
		{
			$explode_seperator = explode("/",$view[$i]);
			$is_file = $explode_seperator[count($explode_seperator)-1];
			$view[$i] = (is_numeric(strpos($is_file,EXT)) ? $view[$i] : $view[$i].EXT);
			
			if(!file_exists(APP_PATH.$view[$i]))
			{
				return "View doesn't Exists";
			}
			
			ob_start();
			
			//prepare variable
			$parse = $parser;
			
			$sinc = new controller;
			
			$gethtml = new gethtml;
			$getutility = new getutility;
			$getcommon = new getcommon;
			
			include APP_PATH.$view[$i];
			$myvar = ob_get_contents();	
					
			ob_end_clean();
			
			return $myvar;
		}
	}	

	public static function getconfig($config="")
	{
		return framework::config($config);
	}
	
	

}

?>