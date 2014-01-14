<?php

use system\framework as framework;

Class model
{
	function __construct()
	{
		self::initiated();
	}
	
	private static $db;
	
	private function initiated()
	{
		$driver = framework::config('db_driver');
		framework::set('system/db/'.$driver,$driver);
		self::$db = framework::get($driver);
	}
	
	public static function connect($host,$user,$pass,$db="")
	{
		self::$db->connect($host,$user,$pass);
		(!empty($db) ? self::$db->db($db) : "");
	}
	
	public static function db($db)
	{
		self::$db->db($db);
	}
	
	public static function query($query="",$dataType="")
	{
		$setQuery = self::$db->query($query);
		if(!empty($dataType))
		{
			$dataType = ($dataType == "array" ? "getArray" : "getObject");
			return self::$db->$dataType($setQuery);
		}
	}
	
	public static function store($table="",$params="",$where="")
	{
		return $setQuery = self::$db->store($table,$params,$where);
	}
	
	public static function get($table="",$where="",$dataType="",$select="*",$order="",$limit="",$like="")
	{
		return self::$db->get($table,$where,$dataType,$select,$order,$limit,$like);
	}
	
	public static function config($key="")
	{
		return framework::config($key);
	}
	
	public static function work()
	{
		echo "work";
	}
	
	public static function __debug($method,$param1="",$param2="",$param3="",$param4="",$param5="",$param6="",$param7="")
	{
		return self::$db->__debug($method,$param1,$param2,$param3,$param4,$param5,$param6,$param7);
	}
	
	
}