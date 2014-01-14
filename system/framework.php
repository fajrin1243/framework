<?php

namespace system;

class framework
{
	
	function __construct()
	{
		
	}
	
	private static $objects;
	
	public static $instance;
	
	public static function instance()
	{
		if(!isset(self::$instance))
		{
			$obj = __CLASS__;
			self::$instance = new $obj;
		}
		
		return self::$instance;
	}
	
	//configuration
	public static function config($key="")
	{
		$config = self::get('config/config');
		
		$get = $config->get($key);
		$getdb = $config->getDb($key);
		return (!empty($get) ? $get : (!empty($getdb) ? $getdb : "Undefined config '".$key."'"));
	}
	
	public static function exec()
	{
		//include General Define
		require_once('system/core/define.php');
	
		//set timezone
		date_default_timezone_set(self::config("default_time_zone"));
		
		//load systems
		self::set(array_values(self::system()),array_keys(self::system()));
		
		//load utility
		//self::set('system/library/library','library','system\library\library');
		self::set("system/library/gethtml","gethtml","system\library\gethtml");
		self::set("system/library/getutility","getutility","system\library\getutility");
		self::set("system/library/getcommon","getcommon","system\library\getcommon");
		
		
		
		//load autoload file
		self::autoload();
		
		//routing
		self::route();

		
	}
	
	public static function route()
	{
		self::get('config/route');
		self::get('system/core/app_route');
	}
	
	private static function autoload()
	{
		$autoload = self::get('config/autoload');
		$loads = $autoload->load();
		foreach($loads as $load)
		{
			self::get($load);
		}
	}
	
	public static function setObjectValue($key="",$val="")
	{
		return self::$objects[ $key ] = $val;
	}
	
	public static function set( $path, $key="", $object = "")
	{
	
		$path = (!is_array($path) ? array($path) : $path);
		$key = (!is_array($key) ? array($key) : $key);
		$object = (!is_array($object) ? array($object) : $object);
		
		
		for($i=0;$i<count($path);$i++)
		{
			//if we not found the key
			if(empty($key[$i]))
			{
				if(is_numeric(strpos($path[$i],DS)))
				{
					$split = explode(DS,$path[$i]);
					$last = count($split) - 1;
					$key[$i] = $split[$last];
				}
				else
				{
					$key[$i] = $path[$i];
				}
			}
			
			if(empty($object[$i]))
			{
				$object[$i] = $key[$i];
			}
			
			if(!file_exists($path[$i].EXT))
			{
				return $error = $key[$i]." Doesn't Exists";
			}
			else
			{	
				require_once($path[$i] . EXT);
				self::$objects[ $key[$i] ] = new $object[$i]( self::$instance );
			}
		}
	}
	
	
	
	/**
		* direct access object, if we can't found these object directly create it
		* @params Single params as object or path to that object
		* @returns Object
	*/
	public static function direct($object="")
	{
		if(empty($object))
		{
			return "Empty Parameters";
		}
		
		//checking if that params is path
		if(is_numeric(strpos($object,DS)))
		{
			$split = explode(DS,$object);
			$last = count($split) - 1;
			$file = $split[$last];
			
			if(is_numeric(strpos($file,EXT)))
			{
				$fsplit = explode(EXT,$file);
				$file = $fsplit[0];
			}

			$path = $object;
		}
		else
		{
			$file = $object;
			$path = $object;
		}
		
		if( empty(self::$objects[ $file ] ) )
		{			
			self::set($path,$file);
		}
		
		
		
		return (!empty(self::$objects[ $file ]) ? self::$objects[ $file ] : "");
	}
	
	/** 
	 * alias for direct()
	 */
	public static function get($object)
	{
		$object = self::direct($object);
		return (!empty($object) ? $object : "");
	}
	
	private static function system()
	{
		return array(
			"controller" => "system/core/controller",
			"model" => "system/core/model",
			"helper" => "system/core/helper",
			"get" => "system/core/get",
		);
	}
	
	
	//alas for get('library');
	/*
	public static function library($class="")
	{
		$getlibrary = self::get('library');
		return $getlibrary->get($class);
	}*/
	
	//alas for get('ext');
	public static function ext($class="")
	{
		$getext = self::get('getext');
		return $getext->get($class);
	}
	
}

?>