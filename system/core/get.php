<?php

/* 	Class Get
*/

Class get
{/*
	public static $autoload;

	function __construct()
	{

		echo self::$autoload;
		
		if(empty(self::$autoload))
		{
			//load configuration
			get::required("config/","config");
			get::required("config/","autoload");
			get::required("config/","route");
			
			//execution route
			get::exec("system/core/","app_route");
			
			//load autoload
			self::autoload();
		}
	}
	
	public static function autoload()
	{
		$core = array(
						"lang",
						"define",
						"controller",
						"model");
						
		$systems = autoload::systems();
		$libraries = autoload::libraries();
		
		get::required("system/core/",$core);
		get::required("system/php/",$systems);
		get::required("library/",$libraries);
		
		get::required("system/db_driver/",config::get("db_driver"));
		
		self::$autoload = 1;
	}*/
	
	public static function exec($path,$class,$function="index")
	{
		self::required($path,$class,$function);
		
		$function = (empty($function) ? "index" : $function);
		
		if(class_exists($class))
		{	
			//cek method to verify function in the class
			if(method_exists($class,$function))
			{
				//render the default function
				//(!empty($function) ? $class::$function() : "");
				$obj = new $class;
				$obj->$function();
				
			}
			else
			{
				$error = array("error"=>"404","msg"=>"Halaman ".$function." tidak ditemukan");
			}
		}
		else
		{
			$error = array("error"=>"404","msg"=>"Halaman ".$class." tidak ditemukan");
		}
		
		return (!empty($error) ? $error : "");
	}
	
	//alias library
	public static function library($class)
	{
		return self::required("library/",$class);
	}
	
	//alias app
	public static function app($class,$function="")
	{
		return self::required("app/",$class,$function);
	}
	
	
	public static function direct($path="",$class="",$function="")
	{
		return self::required("app/".$path."/",$class,$function);
	}
	
	
	//required file
	private static function required($path="",$class,$function="")
	{
		if(!is_array($class))
		{
			$class = array($class);			
		}
		
		for($i=0;$i<count($class);$i++)
		{
			$file = $path.(!is_numeric(strpos($class[$i],".php")) ? $class[$i].".php" : $class[$i]);
		
			$explode_seperator = explode("/",$file);
			$is_file = $explode_seperator[count($explode_seperator)-1];
			$file_extension = strpos($is_file,".php");
			$theClass = substr($is_file,0,$file_extension);
			
			if(file_exists($file))
			{
				require_once($file);
			}
			
		}
		
		return (!empty($error) ? $error : "");
	}
	
	//get file in function get
	public static function view($view,$parse="")
	{
		if(!is_array($view))
		{
			$view = array($view);			
		}
		
		for($i=0;$i<count($view);$i++)
		{
			$explode_seperator = explode(DS,$view[$i]);
			$is_file = $explode_seperator[count($explode_seperator)-1];
			$file_extension = strpos($is_file,EXT);
			
			if(!file_exists($view[$i]))
			{
				return "View doesn't Exists";
			}
			
			ob_start();
			
			$parse;
			
			include APP_PATH.$view[$i];
			$myvar = ob_get_contents();	
					
			ob_end_clean();
			
			/*$jumlahSign = substr_count($myvar,"{");
			
			for($i=0;$i<$jumlahSign;$i++)
			{
				$getSign = self::getSign("{","}",$myvar);
				$myvar = str_replace($getSign['sign'],$data[$getSign['string']],$myvar);
			}*/
			
			return $myvar;
		}
		
		
		
	}
}

?>