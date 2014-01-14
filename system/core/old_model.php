<?php

use system\utility as utility;

Class model
{
	function __construct()
	{
		
	}

	public static $setQuery = "";
	public static $isConnect = "";
	public static $isSetDB = "";
	public static $db_driver = "";
	
	//transactional
	public static $pdo = "";
	
	
	
	public static function getConnect()
	{
		$db_driver = config::get("db_driver");
		
		if((empty(self::$isConnect)) && (empty(self::$isSetDB)))
		{
			$db_driver->connect();
		}
	}
	
	public static function query($query="",$dataType="")
	{
		$db_driver = config::get("db_driver");
		self::$db_driver = new $db_driver;
		$is_select = strpos(strtolower($query),"select");
		
		if(is_numeric($is_select))
		{
		
			switch($dataType)
			{
				case "array":
					return self::$db_driver->getArray($query);
				break;
				
				case "array_object":
					return self::$db_driver->getObject($query,"array");
				break;
				
				default:
					return self::$db_driver->getObject($query);
				break;
			}
		}
		else
		{
			return self::$db_driver->setQuery($query);
		}
		
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
	public static function get($table="",$where="",$dataType="",$select="*",$order="",$limit="",$like="")
	{
		//join table
		if(is_array($table))
		{
			$table = self::setJoin($table);
		}
		
		$setWhere = self::where($where);
		$where = (!empty($where) ? getstring::setString($setWhere['where']," WHERE ",$setWhere['delimiter']) : "");
		$order = (!empty($order) ? getstring::setString($order," ORDER BY "," "," ") : "");
		$limit = (!empty($limit) ? (is_array($limit) ? " LIMIT ".$limit[0].",".$limit[1] : " LIMIT 0,".$limit) : "");
		$like = (!empty($like) ? (!empty($where) ? getstring::setString($like," "," && "," LIKE ","'% "," %'") : getstring::setString($like," WHERE "," && "," LIKE ","'% "," %'")) : "");
		//if($table == "content") { $where =  "WHERE keterangan LIKE '% lorem %'"; } else { $where = "";}
		
		$query = "SELECT ".$select." FROM ".$table." ".$where." ".$like." ".$order." ".$limit;
		
		return self::query($query,$dataType);
	}
	
	
	public static function store($table,$params,$where)
	{
	
		if(!empty($where))
		{
			$params = getstring::setString($params," SET ",",","=","'");
			$setWhere = self::where($where);
			$where = getstring::setString($setWhere['where']," WHERE ",$setWhere['delimiter']);
			
			//query for update
			$query = "UPDATE ".$table.$params.$where;
			
			return self::query($query);
		}
		else
		{
			$fields = getstring::setString(array_keys($params)," ","",",","`","`");
			$values = getstring::setString(array_values($params),"","",",","'","'");
			
			//query for insert
			$query = "INSERT INTO ".$table." (".$fields.") "." VALUES (".$values.") ";
			
			return self::query($query);			
		}
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
	
	
	public function test()
	{
		echo "Work";
	}
}