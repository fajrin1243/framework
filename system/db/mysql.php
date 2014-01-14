<?php

use system\framework as framework;
use system\library\getutility as getutility;
use system\library\gethtml as gethtml;
use system\library\getcommon as getcommon;

class mysql
{
	
	//object database
	private static $connect;
	
	public static function connect($host="",$user="",$pass="")
	{
		if(!isset(self::$connect->connect))
		{
			$host = (empty($host) ? model::config('db_host'): $host);
			$user = (empty($user) ? model::config('db_user'): $user);
			$pass = (empty($pass) ? model::config('db_password'): $pass);
			
			self::$connect = new stdClass();
			self::$connect->connect = mysql_connect($host,$user,$pass="");
			
		}
		
		//return self::$connect;
	}
	
	public static function db($db="")
	{
		if((!empty($db)) || (!isset(self::$connect->db)))
		{
			$db = (empty($db) ? model::config('db') : $db);
			self::$connect->db = mysql_select_db($db);
		}
		
		return self::$connect;
	}
	
	public static function query($query="",$debug="")
	{
		
		if(!isset(self::$connect->connect))
		{
			self::connect();
		}
		
		
		if(!isset(self::$connect->db))
		{
			self::db();
		}
		
		if($debug == "system.debug")
		{
			return $query;
			break;
		}
		
		return mysql_query($query,self::$connect->connect);
	}

	
	
	public static function getArray($setQuery)
	{
		if(empty($setQuery))
		{
			return "";
		}
	
		while($get = mysql_fetch_object($setQuery))
		{
			$return[] = $get;
		}
		
		return (!empty($return) ? $return : "");
		self::close();
	}
	
	public static function getObject($setQuery)
	{
		if(empty($setQuery))
		{
			return "";
		}
		
		return mysql_fetch_object($setQuery);
		self::close();
	}

	private static function close()
	{
		
		msql_close(self::$connect->connect);
		self::$connect = "";
	}
	
	/* 	shortcut to get data within parameter
		$table = string || array();
					? array = array("table_name"=>"field_name","join_table_name"=>"join_field_name");
		$where = array;
		$dataType = object || Array;
		$limit = array("start","limit");
		$select = string;
		$order = array("key"=>"ASC || DESC");
	*/
	public static function get($table="",$where="",$dataType="",$select="*",$order="",$limit="",$like="",$debug="")
	{
		//join table
		if(is_array($table))
		{
			$table = self::setJoin($table);
		}
		
		$setWhere = self::where($where);
		$where = (!empty($where) ? getutility::setString($setWhere['where']," WHERE ",$setWhere['delimiter']) : "");
		$order = (!empty($order) ? getutility::setString($order," ORDER BY "," "," ") : "");
		$limit = (!empty($limit) ? (is_array($limit) ? " LIMIT ".$limit[0].",".$limit[1] : " LIMIT 0,".$limit) : "");
		$like = (!empty($like) ? (!empty($where) ? getutility::setString($like," "," && "," LIKE ","'% "," %'") : getutility::setString($like," WHERE "," && "," LIKE ","'% "," %'")) : "");
		//if($table == "content") { $where =  "WHERE keterangan LIKE '% lorem %'"; } else { $where = "";}
		
		if($debug == "system.debug")
		{
			return $sql = "SELECT ".(!empty($select) ? $select : "*")." FROM ".$table." ".$where." ".$like." ".$order." ".$limit;
			break;
		}
		
		$setQuery = self::query("SELECT ".(!empty($select) ? $select : "*")." FROM ".$table." ".$where." ".$like." ".$order." ".$limit);		
		return ($dataType == "array" ? self::getArray($setQuery) : self::getObject($setQuery));
	}
	
	public static function setjoin($table)
	{
		$join = "";
		$i =1;
		foreach($table as $key=>$val)
		{
			if($i == 1)
			{
				$table = $key;
				$field = $val;
				$join = $table;
			}
			else
			{
				$join .= " LEFT JOIN ".$key." ON (".$key.".".$val."=".$table.".".$field.")";
			}
			
			$i++;
		}
		
		return $join;
	}
	
	public static function where($where)
	{
		$signs = array("!",">","<","=","like","in");
		$delimiter = array("&&","||","and","or");
		$getWhere = array();
	
		if(!empty($where))
		{
			$i = 1;
			foreach($where as $key=>$val)
			{
				$define = 0;
				$is_delimiter = 0;
				
				/*
				foreach($delimiter as $delimit)
				{
					$is_delimit = strpos(strtolower($val),$delimit);
					if(is_numeric($is_delimit))
					{
						$val_field = substr($val,0,$is_delimit);
						$val_sign = substr($val,$is_delimit);
						break;
					}
				}
				
				
				if(empty($val_field)) { $val_field = $val;}
				if(empty($val_sign)) { $val_sign = "";}*/
				
				if($i > 1)
				{
					foreach($delimiter as $del)
					{
						if(strtolower($del) == $val)
						{
							$getDelimiter[] = " ".$val." ";
							$is_delimiter = 1;
							break;
						}
					}
					
					if(empty($is_delimiter))
					{
						$getDelimiter[] = " && ";
					}
				}
				
				
				if(empty($is_delimiter))
				{
					foreach($signs as $sign)
					{
						$is_sign = strpos(strtolower($key),$sign);
						if(is_numeric($is_sign))
						{
							$key_field = substr($key,0,$is_sign);
							$key_sign = substr($key,$is_sign);
							
							if(strtolower($key_sign) == "like")
							{
								$getWhere[$key_field] = $key_sign." '%".$val."%'";
							}
							else if(strtolower($key_sign) == "in")
							{
								if(is_array($val))
								{
									$val = implode(",",$val);
								}
								
								$getWhere[$key_field] = $key_sign." (".$val.")";
							}
							else
							{
								$getWhere[$key_field] = $key_sign." '".$val."'";
							}
							$define = 1;
							break;
						}
					}
				
					if(empty($define)) { $getWhere[$key] = "='".$val."'"; }
				
				}
				
				$i++;
			}
		}
		
		$set['where'] = $getWhere;
		$set['delimiter'] = (!empty($getDelimiter) ? $getDelimiter : " ");
		
		return $set;
	}
	
	public static function store($table,$params,$where)
	{
	
		if(!empty($where))
		{
			$params = getutility::setString($params," SET ",",","=","'");
			$setWhere = self::where($where);
			$where = getutility::setString($setWhere['where']," WHERE ",$setWhere['delimiter']);
			
			//query for update
			$query = "UPDATE ".$table.$params.$where;
			
			return self::query($query);
		}
		else
		{
			$fields = getutility::setString(array_keys($params)," ","",",","`","`");
			$values = getutility::setString(array_values($params),"","",",","'","'");
			
			//query for insert
			$query = "INSERT INTO ".$table." (".$fields.") "." VALUES (".$values.") ";
			
			return self::query($query);			
		}
	}
	
	public function __debug($method="",$param1="",$param2="",$param3="",$param4="",$param5="",$param6="",$param7="")
	{
		switch($method)
		{
			case "get":
				return self::get($param1,$param2,$param3,$param4,$param5,$param6,$param7,"system.debug");
			break;
			
			default:
				return self::query($param1,"system.debug");
			break;
		}
	}
	
	public static function work()
	{
		echo "working";
	}

	
}