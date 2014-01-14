<?php

namespace system\library;

use system\framework as framework;

class library
{
	function __construct()
	{
		//framework::set(array_values(self::elements()),array_keys(self::elements()));
		self::initiated();
	}
	
	
	function initiated()
	{
		foreach(self::elements() as $element)
		{
			require_once($element.".php");
		}
	}
	
	public static function get($class)
	{
		$classes = (!is_array($class)? array($class) : $class);
		foreach($classes as $class)
		{
			if(!class_exists($class))
			{
				return "Class ".$class." undefined";
			}
			
			return new $class;
		}
	}
	
	function elements($get="")
	{
		$elements = array(
			"getconnect" => "system/library/gethtml",
			"getinfo" => "system/library/getcommon",
			"getlang" => "system/library/getutility",
		);
		
		return (!empty($get) ? (!empty($elements[$get]) ? $elements[$get] : "Undefined Element ".$get) : $elements);
	}
}