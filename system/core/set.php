<?php

/* 	Alias Library
*/

Class set
{
	public static function autoload()
	{
		$system = array(
						"lang",
						"define",
						"controller",
						"model",
						"error");
						
		get::required("library/system/",$system);
		
		$php = array(
						"getinfo",
						"getlink",
						"gethtml",
						"getform",
						"geterror",
						"getdb"	
					);
		
		get::required("library/php/",$php);
		
		$php = array(
						"getinfo",
						"getlink",
						"gethtml",
						"getform",
						"geterror",
						"getconnect",
						"getmobile",
						"getdb"	
					);
		
		get::required("library/php/",$php);
	}
	
	public static function exec($path,$class,$function="index")
	{
	
		self::required($path,$class,$function);
		
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
				$error = array("error"=>"404","msg"=>"Halaman ".$class." tidak ditemukan");
			}
		}
		else
		{
			$error = array("error"=>"404","msg"=>"Halaman ".$class." tidak ditemukan");
		}
		
		return (!empty($error) ? $error : "");
	}
	
	public static function library($class)
	{
		self::_required("library/",$class);
	}
	
	public static function app($class,$function="")
	{
		return self::_required("app/",$class,$function);
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
			$explode_seperator = explode("/",$view[$i]);
			$is_file = $explode_seperator[count($explode_seperator)-1];
			$file_extension = strpos($is_file,".php");
			
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
	
	public static function getSign($start,$finish,$data)
	{
		$posStart = strpos($data,$start);
		$wStart = strlen($start);
		$start = substr($data,$posStart);
		$posFinish = strpos($start,$finish);
		$length = strlen($finish);
		
		$return = array();
		$return['sign'] = substr($data,$posStart,$posFinish+$length);
		$return['string'] = substr($data,$posStart+$wStart,$posFinish-$length); 
				
		if(!is_numeric($posStart))
		{
			return "";
		}
		else
		{
			return $return;
		}
	}
	
	public static function config($config="",$stripTags="")
	{
		require_once("config/configuration.php");
	
		$getConfig = configuration::get($config);
		return (empty($stripTags) ? $getConfig : strip_tags($getConfig));
	}
}

?>