<?php

Class config
{

	/* 	getConfig(stirng $getConfig)
		Desc : Mendapatkan konfigurasi yang tersimpan pada static konfigurasi
		- getConfig : Nama Configurasi / index dari array konfigurasi
	*/
	
	public static function get($getConfig="")
	{
		/* config */
		$config = array();
		
		/* Important Directory */
		$config['config_path'] = "config";
		$config['app_path'] = "app";
		$config['system_path'] = "system";
		
		/* Error Configuration */
		$config['error_reporting'] = 1;
		$config['error_log'] = 1;
		$config['error_log_path'] = "log/error_log";
		
		
		/* View Configuration */
		$config['default_view'] = "default";
		$config['mobile_view'] = "mobile";

		
		/* Database Configuration */
		$config['db_driver'] = "mysql";
		$config['db_host'] = "localhost";
		$config['db_user'] = "root";
		$config['db_password'] = "";
		$config['db'] = "opsipay";
		$config['table_prefix'] = "";
		
		
		/* Session Configuration */
		$config['session_db'] = 1;
		$config['session_table'] = "session";
		$config['session_expired'] = ""; 

		
		/*Authentication*/
		$config['auth'] = 0;
		$config['auth_key'] = "user_id";
		
		/* security */
		$config['encrypt_key'] = "blablabla";
		
		/* Default Language */
		$config['language'] = "en";
		//load language ini file in folder language
		
		
		/* Default Date */
		$config['default_time_zone'] = "Asia/Jakarta";
	
		
		return (!empty($getConfig) ? (!empty($config[$getConfig]) ? trim($config[$getConfig]) : "") : $config); 
	}
	
	/* call database configuration */
	public static function getDb($getConfig="")
	{
		//$config = model::get("SELECT * FROM config WHERE config='".$getConfig."'");
		//return (!empty($config->value) ?$config->value : "");
		return "";
	}
	
	public static function work()
	{
		echo "Working";
	}
}