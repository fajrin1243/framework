<?php

class route
{
	//route configuration
	public static function config($get)
	{
		//is your route use trigger like GET params
		//if you input 0 your url like this http://yourweb.com/profile/settings
		//if you input 1 your url like this http://yourweb.com?page=profil&section=edit
		$config['route_trigger'] = 0;
		$config['default_class'] = "site";
		$config['default_function'] = "index";
		
		//we need you to define where your class & function in your route
		//you can define by the number of path or with alias (please first define your alias in function alias bellow this function)
		$config['class'] = 1; 
		$config['function'] = 2;
		
		//by the way the class and function have default alias : SF_CLASS & SF_FUNCTION
		
		//default error page or lost the page
		$config['error_page'] = "error";
		
		return (!empty($get) ? $config[$get] : $config);
	}
	
	
	//create your own alias for route, to easy call them in your code
	public static function alias()
	{	
		$path[1] = "page"; 
		$path[2] = "section";
		
		return $path;

		//$path[N] = route_params + autoIncreament; $path[4] = route_params1, $path[5] = route_params2, etc
		//or alias can generated automatically call constant : SF_PATH_numberPath;  $path[1] = SF_PATH_1, $path[2] = SF_PATH_2
	}
	
	//set your own route, create the condition for your route
	public static function set()
	{
		switch(page)
		{
			case "route.test":
				echo "testing";
				return true;
			break;
			
		}
		
		//please include return
	}
	
	public static function work()
	{
		echo "testing";
	}
}