<?php

namespace system\library;

use system\framework as framework;
use system\library\gethtml as gethtml;
use system\library\getutility as getutility;

class getcommon {

	public static $sessionStart;
	public static $model;
	
	function __construct()
	{
		self::sessionSetDB();
		self::sessionExpired();
		self::$model = framework::get("system/core/model");
	}
	
	public static function setCookie($cookie="",$expire="time()+3600",$path="",$domain="")
	{
		//setcookie(name, value, expire, path, domain);
		
		if(!is_array($cookie))
		{
			$cookie = (array) $cookie;
		}
	
		foreach($cookie as $key=>$val)
		{
			setcookie($key,$val);
		}
	}
	
	public static function getCookie($cookie)
	{
		return (!empty($_COOKIE[$cookie]) ? $_COOKIE[$cookie] : "");
	}
	
	
	
	/***** Function Session *****/
	
	public static function sessionExpired()
	{	
		$rows = self::$model->query("SELECT * FROM session","array");
		foreach($rows as $row)
		{
			$now = strtotime(date("Y-m-d H:i:s"));
			$expired = strtotime($row->date."+1 minutes");
			
			if($now > $expired)
			{
				self::$model->query("DELETE FROM session WHERE session_id='".$row->session_id."'");
			}
		}
		//echo strtotime(date("Y-m-d H:i:s"));
		//echo " dan ".strtotime(self::get("date")."+1 day");
	}

	public static function sessionStart()
	{
		if(!isset(self::$sessionStart))
		{
			session_start();
			self::$sessionStart = 1;
			
		}
	}
	
	public static function sessionSetDB($data="")
	{	
		self::sessionStart();
		$session_id = session_id();
		
		self::$model = framework::get("system/core/model");
		
		$session_db = framework::config("session_db");
		$session_table = framework::config("session_table");
		$user_agent = implode(";",getutility::userAgent());
		
		if((!empty($session_db)) && (!empty($session_table)))
		{
			$is_exist = self::$model->get("session",array("session_id"=>$session_id));
		
			if(empty($is_exist->session_id))
			{
				self::$model->query("INSERT INTO ".$session_table." VALUES ('".$session_id."','".$user_agent."','".$data."','".date("Y-m-d H:i:s")."')");
			}
			else
			{	
				self::$model->query("UPDATE ".$session_table." SET user_agent='".$user_agent."',data='".(!empty($data) ? $data : $is_exist->data)."',date='".date("Y-m-d H:i:s")."' WHERE session_id='".$session_id."' ");
			}
		}
	}
	
	public static function sessionUnsetDB($data="")
	{	
		self::sessionStart();
		$session_id = session_id();
		
		$session_db = framework::config("session_db");
		$session_table = framework::config("session_table");
		$user_agent = implode(";",getutility::userAgent());
		
		if((!empty($session_db)) && (!empty($session_table)))
		{
			$is_exist = self::$model->get("session",array("session_id"=>$session_id));
		
			if(!empty($is_exist->session_id))
			{
				if(!empty($data))
				{
					$getData = json_decode($is_exist->data);
					unset($getData->$data);
					self::sessionSetDB(json_encode($getData));
				}
				else
				{
					self::$model->query("DELETE FROM ".$session_table." WHERE session_id='".$session_id."'");
				}
			}
		}
	}
	
	public static function sessionDestroy($session="")
	{
		self::sessionStart();
		
		if(!empty($session))
		{
			$session = (array) $session;
			
			foreach($session as $data)
			{
				self::sessionUnsetDB($data);
				unset($_SESSION[$data]);
			}
		}
		else
		{	
			//generate new session
			self::sessionUnsetDB();
			session_regenerate_id();
			session_destroy();
		}
	}
	
	public static function setSession($session)
	{
		self::sessionStart();
		
		if(!is_array($session))
		{	
			$session = (array) $session;
		}
		
		foreach($session as $key=>$val)
		{
			$_SESSION[$key] = getutility::encrypt($val);
		}
		
		self::sessionSetDB(json_encode($_SESSION));
		//session_encode();
	}
	
	public static function getSession($session="")
	{
		
		self::sessionStart();
		//session_decode($_SESSION);
		$session_id = session_id();
		
		$session_db = framework::config("session_db");
		$session_table = framework::config("session_table");
		$user_agent = implode(";",getutility::userAgent());
		
		if((!empty($session_table)) && (!empty($session_db)))
		{
			$getSession = self::$model->get($session_table,array("session_id"=>$session_id),"object");
			if(!empty($getSession->session_id))
			{
				if(!empty($getSession->$session))
				{
					
					return $getSession->$session;
				}
				else
				{
					$getSessionData = json_decode($getSession->data);
					return (!empty($getSessionData->$session) ? getutility::decrypt($getSessionData->$session) : "");
				}
			}
		}
		else
		{	
			if(!empty($session))
			{
				return (!empty($_SESSION[$session]) ? getutility::decrypt($_SESSION[$session]) : "");
			}
			else
			{
				return $_SESSION;
			}
		}
	}	
	
	
	
	/***** Document ******/
	public static function readXml($file)
	{
		$xml = simplexml_load_file($file);
		return $xml;
	}
}

?>