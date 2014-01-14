<?php

/* Application Route */

use system\framework as framework;
use system\library\gethtml as gethtml;
use system\library\getcommon as getcommon;
use system\library\getutility as getutility;


class app_route
{
	
	private static $route;
	private static $getutility;
	
	function __construct()
	{
		self::$route = framework::get('route');
		
		self::defined();
		self::exec();

	}
	
	//defined path
	public static function defined()
	{
		$alias = self::$route->alias();
		$trigger = self::$route->config("route_trigger");
		$default_class = self::$route->config('default_class');
		$default_function = self::$route->config('default_function');
		
		$count = ($trigger > 0 ? count($alias) : (count(getutility::requestPath()) >= 2 ? (count(getutility::requestPath()) -1) : 2));
		
		$j = 1;
		for($i = 1;$i<=$count;$i++)
		{
			if(!empty($alias[$i]))
			{
				$getalias = $alias[$i];
			}
			else
			{
				$getalias = "route_params".$j;
				$j++;
			}
			
			if(!empty($getalias))
			{
				$path[$i] = ($trigger > 0 ? getutility::requestVar($getalias) : getutility::requestPath($i));
				(!defined($getalias) ? define($getalias,(!empty($path[$i]) ? $path[$i] : "")) : "");
			}
			
			//define("SF_PATH_".$i,(!empty($path[$i]) ? $path[$i] : ""));
			framework::setObjectValue("SF_PATH_".$i,(!empty($path[$i]) ? $path[$i] : ""));
			
			if(!empty($path[$i]))
			{
				if(($i == self::$route->config('class')) || ($getalias == self::$route->config('class')))
				{
					framework::setObjectValue("SF_CLASS",(!empty($path[$i]) ? $path[$i] : ""));
					//define("SF_CLASS",(!empty($path[$i]) ? $path[$i] : ""));
				}
				else if(($i == self::$route->config('function')) || ($getalias == self::$route->config('function')))
				{
					framework::setObjectValue("SF_FUNCTION",(!empty($path[$i]) ? $path[$i] : ""));
					//define("SF_FUNCTION",(!empty($path[$i]) ? $path[$i] : ""));
				}
			}
		}
		
		$sfclass = framework::get("SF_CLASS");
		$sffunction = framework::get("SF_FUNCTION");
		
		//(!defined("SF_CLASS") ? define("SF_CLASS",$default_class) : "");
		(empty($sfclass) ? framework::setObjectValue("SF_CLASS",$default_class) : "");
		//(!defined("SF_FUNCTION") ? define("SF_FUNCTION",$default_function) : "");
		(empty($sffunction) ? framework::setObjectValue("SF_FUNCTION",$default_function) : "");
		
	}
	
	
	
	//execution route
	public static function exec()
	{
	
		//special Route
		$specialRoute = self::specialRoute();
		$specialRoute = (empty($specialRoute) ? route::set() : $specialRoute);
	
		$route[0] = (!empty($specialRoute[0]) ? $specialRoute[0] : framework::get("SF_CLASS"));
		$route[1] = (!empty($specialRoute[1]) ? $specialRoute[1] : framework::get("SF_FUNCTION"));
	
		/* Checked authentication pass */
		//$auth = framework::config("auth");
		//$auth_pass = (!empty($auth) ? self::user_login() : 1);
		
		//if((!empty($auth_pass)) || (!empty($specialRoute)))
		//{
			$set = framework::set("app/".$route[0],$route[0]);
			if(!empty($set))
			{
				echo "Controller ".$set;
				return;
			}
			
			$controller = framework::get($route[0]);
			$function = $route[1];
			
			if(!method_exists($route[0],$function))
			{
				echo "Undefined Method ".$route[1]." In Controller ".$route[0];
				return;
			}
			
			$controller->$function();
			
			//if((!empty($exec)) && (empty($specialRoute))) { geterror::set($exec) ; }
		//}
	}
	
	public static function specialRoute()
	{
		$SF_PATH_1 = framework::get("SF_PATH_1");
		$SF_PATH_2 = framework::get("SF_PATH_2");
	
		switch($SF_PATH_1)
		{
			case "connect":
				getConnect::identify($SF_PATH_2);
				return 1;
			break;
			
			case "page":
				echo json_encode(getpage::initializePage(getrequest::getvar('token'),getrequest::getvar('page'),getrequest::getvar('order'),getrequest::getvar('like')));
				return 1;
			break;
			
			case "language":
				getlang::setlang($SF_PATH_2);
			break;
		}
	}
	
	public static function user_login()
	{
		return getauth::login();
	}
}

?>