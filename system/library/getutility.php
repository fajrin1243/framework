<?php

namespace system\library;

use system\framework as framework;
use system\library\gethtml as gethtml;
use system\library\getcommon as getcommon;

/* Utility help you to set anything in system that wasn't in html */

class getutility
{
	
	public static $gethtml;
	private static $model;
	
	function __construct()
	{
		$instance = framework::instance();
		self::$gethtml = $instance->get("system/library/gethtml");
		self::$model = $instance->get("system/core/model");
	}
	
	
	/***** Request Secition *****/
	
	public static function requestPath($pos="")
	{
		if(!empty($_SERVER['PATH_INFO']))
		{
			$path = explode("/",$_SERVER['PATH_INFO']);
		
			return (empty($pos) ? $path : (!empty($path[$pos]) ? $path[$pos] : ""));
		}
	}
	
	public static function requestGet($var="")
	{	
		if($_SERVER['REQUEST_METHOD'] == "GET")
		{
			return (empty($var) ? self::clearTags($_GET) : self::clearTags($_GET[$var]));
		}
		
	}
	
	public static function requestPost($var="")
	{
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			return (empty($var) ? self::clearTags($_POST) : self::clearTags($_POST[$var]));
		}
	}
	
	public static function requestVar($var="")
	{
		return (!empty($_GET[$var]) ? self::clearTags($_GET[$var]) : (!empty($_POST[$var]) ? self::clearTags($_POST[$var]) : ""));
	}
	
	
	/***** String Section *****/
	
	//mengembalikan parameter array berupa index, value,identified, delimiter,delimiter index dan delimiter value dalam bentuk string
	public static function setString($parameter="",$identifier="",$delimiter="",$delimiter_key="",$delimiter_val_start="",$delimiter_val_end="")
	{
		if(!empty($parameter))
		{
			if(!is_array($parameter))
			{
				$parameter = array($parameter);
			}
		
			$params = array();
			if((!empty($parameter)))
			{
				if(!empty($delimiter))
				{
					foreach($parameter as $key=>$val)
					{
						$params[] = strip_tags(trim($key))." ".$delimiter_key." ".$delimiter_val_start.strip_tags(trim($val)).(!empty($delimiter_val_end) ? $delimiter_val_end : $delimiter_val_start);
					}
				}
				else
				{
					foreach($parameter as $val)
					{
						$params[] = $delimiter_val_start.strip_tags(trim($val)).(!empty($delimiter_val_end) ? $delimiter_val_end : $delimiter_val_start);
					}
					$delimiter = $delimiter_key;
				}
				
				if(is_array($delimiter))
				{
					$param = "";
					for($i=0;$i<count($params);$i++)
					{
						if($i == 0)
						{
							$param .= $params[$i];
						}
						else
						{
							$del = (!empty($delimiter[$i-1]) ? $delimiter[$i-1] : $delimiter[0]);
							$param .= " ".$del." ".$params[$i];
						}
					}
				}
				else
				{
					$param = implode($delimiter,$params);
				}
			}
			
			return (!empty($param) ? $identifier.$param : "");
		}
		else
		{
			return "";
		}
	}
	
	//mengembalikan parameter array berupa index, value,identified, delimiter,delimiter index dan delimiter value dalam bentuk string
	public static function setArray($parameter="",$identifier="",$delimiter="",$delimiter_key="",$delimiter_val_start="",$delimiter_val_end="")
	{
		//remove identifier
		if((!empty($identifier)) && (is_numeric(strpos(strtolower($parameter),strtolower($identifier)))))
		{
			$parameter = str_replace($identifier," ",$parameter);
		}
		
		if((!empty($delimiter)) && (is_numeric(strpos($parameter,$delimiter))))
		{
			$arrayParams = explode($delimiter,$parameter);
		}
		else
		{
			$arrayParams = (array) $parameter;
		}
		
		if(!empty($arrayParams))
		{
			$params = array();
			foreach($arrayParams as $param)
			{
				if(!empty($delimiter_key))
				{
					$exparams = explode($delimiter_key,$param);
					if((!empty($exparams[0])) && (!empty($exparams[1])))
					{
						$exparams[1] = str_replace($delimiter_val_start," ",$exparams[1]);
						$exparams[1] = str_replace($delimiter_val_end," ",$exparams[1]);
						
						$params[$exparams[0]] = $exparams[1];
					}
				}
				else
				{
					$val = str_replace($delimiter_val_start," ",$params[1]);
					$val = str_replace($delimiter_val_end," ",$params[1]);
				}
			}
		}
		else
		{
			$params = "";
		}
		
		return $params;
	}
	
	public static function setValueIntoVar($teks,$post)
	{
		//proses penyesuaian advance sign
		$jmlhSign = explode("{{{",$teks);
		for($i=0;$i<count($jmlhSign);$i++)
		{
			$cekSign = self::getSign("{{{","}}}",$teks);
			if(!empty($cekSign))
			{
				$stringValue = self::getStringValue($cekSign['sign'],$teks);
				if(!empty($stringValue['key']))
				{
					if(!empty($post[$cekSign['string']]))
					{
						$teks = str_replace($stringValue['msg'],$stringValue['value'],$teks);
					}
					else
					{
						$teks = str_replace($stringValue['msg']," ",$teks);
					}
				}
			}
		}
		
		foreach($post as $key=>$val)
		{
			$teks = str_replace("[".strtolower($key)."]",$val,$teks);
		}
		
		return $teks;
	}
	
	//mendapatkan nilai random berupa angka atau campuran
	public static function getRandom($length,$type="")
	{
		if($type == "number")
		{
			$first = substr(11111111111,0,$length);
			$last = substr(99999999999,0,$length);
			$result = rand($first,$last);
		}
		else
		{
			$rand = base_convert(mt_rand(60466176, 2147483647), 10, 36);
			$result = substr($rand,0,$length);
		}
		return $result;
	}
	
	public static function setUniqueRandom($length,$type,$field,$table)
	{
		$random = $this->getRandom($length,$type);
		for($i=0;$i<1000;$i++)
		{
			$data = $this->getDataByParam($field,"='".$random."'",$table,"array");
			if(empty($data))
			{
				break;
			}
			else
			{
				$random = $this->getRandom($length,$type);
			}
		}
		
		return $random;
	}
	
	/* Function getSign
	desc : Mendapatkan string yang terdapat dalam sign khusus
	request : start {sign awal}, finish {sign akhir}, data { data yang ingin dilakukan pencarian
	response : array(sign {sign dilengkapi dengan stringnya}, string {tanpa menggunakan sign)
	*/
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

	/* Function getStringValue
	desc : Mendapatkan string yang memiliki value dengan kriteria pencarian yang diawali tanda = dan diakhiri tanda ;
	request : find (string pencarian), data : seluruh data yang ingin dilakukan pencarian
	response : array ( msg : data keseluruhan, key : string yang dicari, value : isi dari string tersebut);
	*/
	function getStringValue($find,$data)
	{
		$posStart = strpos($data,$find);
		$start = substr($data,$posStart);
		$posFinish = strpos($start,";");
		$msg = substr($data,$posStart,$posFinish);
		$msgc = substr($data,$posStart,$posFinish+1);
		
		$getKey = strlen($find);
		$key = substr($msg,0,$getKey);
		$getValue = strpos($msg,"=");
		$value = substr($msg,$getValue+1);
		
		$return = array();
		$return['msg'] = $msgc;
		$return['key'] = $key;
		$return['value'] = $value;
		
		return $return;
	}
	
	/* Function getSign
	desc : Mendapatkan banyak string yang terdapat dalam sign khusus
	request : start {sign awal}, finish {sign akhir}, data { data yang ingin dilakukan pencarian
	response : array(sign {sign dilengkapi dengan stringnya}, string {tanpa menggunakan sign)
	*/
	function getMultipleSign($start,$finish,$data)
	{
		$jmlhString = substr_count($data,$start);
		
		$return = array();
		for($i=0;$i<$jmlhString;$i++)
		{
			if(empty($teks))
			{
				$teks = $data;
			}
			
			$posStart = strpos($teks,$start);
			$wStart = strlen($start);
			$sStart = substr($teks,$posStart);
			$posFinish = strpos($sStart,$finish);
			$length = strlen($finish);
			
			
			$return['sign'][] = substr($teks,$posStart,$posFinish+$length);
			$return['string'][] = substr($teks,$posStart+$wStart,$posFinish-$length); 		
			
			$teks = substr($sStart,$posFinish+$length);
		}

		return $return;
	}
	
	//memilah data menjadi dua bagian yakni yang mana operator dan bukan.
	// request : isOperand(array("10","*","11","+"))
	// response : array("operator"=>array("key"=>array("1","3"),"val"=>array("*","+")),
	//					"number"=>array("key"=>array("0","2"),"val"=>array("10","11")));

	function isOperand($data)
	{
		$operator = array("*","/",":","+","-");
		$type = array();
		
		$i = 0;
		foreach($data as $key=>$val)
		{
			foreach($operator as $opr)
			{
				if($opr == $val)
				{
					$type[$i] = 1;
					break;
				}
			}
			
			if(!empty($type[$i]))
			{
				$type['operator']['key'][] = $key;
				$type['operator']['val'][] = $val;
			}
			else
			{
				$type['number']['key'][] = $key;
				$type['number']['val'][] = $val;
			}
			
			$i++;
		}
		
		return $type;
	}
	
	/***** Information Section *****/
	
	public static function userAgent()
	{
		$getUserAgent = $_SERVER['HTTP_USER_AGENT'];
		
		$lists = array("Android","Chrome","Firefox","Opera","Safari","Unknown");
		
		$user_agent = array();
		for($i=0;$i<count($lists);$i++)
		{
			if(is_numeric(strpos($getUserAgent,$lists[$i])))
			{
				$getPos = strpos($getUserAgent,$lists[$i]);
				$getPosText = substr($getUserAgent,$getPos);
				$getSpace = strpos($getPosText," ");
				$getInfo = (!is_numeric($getSpace) ? explode("/",$getPosText) : explode("/",substr($getPosText,0,$getSpace)));
				
				$user_agent['browser'] = $getInfo[0];
				$user_agent['version'] = (!empty($getInfo[1]) ? $getInfo[1] : "");
				$user_agent['mobile'] = ($getInfo[0] == "Android" ? 1 : "");
				
				break;
			}
		}
		
		return $user_agent;
	}
	
	public static function server()
	{
		$getServer = str_replace(" (Win32)","(Win32)",$_SERVER['SERVER_SOFTWARE']);
		$getServer = explode("/",str_replace(" ","/",$getServer));
	
		$server = array();
		for($i=0;$i<count($getServer);$i++)
		{
			if($i%2 == 0)
			{
				$server[$getServer[$i]] = $getServer[$i+1];
			}
		}
		
		return $server;
	}
	
	
	
	/***** Language Section *****/
	
	/* 	Function Text(string $text)
		Desc 	: Render language from text
		- text 	: string language
	*/
	
	public static function text($text="")
	{
		$getlang = getcommon::getsession("SF_LANG");
		(empty($getlang) ? getcommon::setsession(array("SF_LANG"=>framework::config("language"))) : "");
		$lang = self::clearTags(getcommon::getsession("SF_LANG"));
		
		if(!empty($text))
		{
			if(is_numeric(strpos($text,".")))
			{
				$gettext = explode(".",$text);
			}
			
			if(!empty($gettext))
			{
				$getfile = "language/".$gettext[0].".".$lang.".ini";
			
				if(!file_exists($getfile))
				{
					return $text;
				}
				else
				{
					$ini_file = parse_ini_file($getfile);
					if(!empty($ini_file[$gettext[1]]))
					{
						return $ini_file[$gettext[1]];
					}
					else
					{
						return $text;
					}
				}
			}
			else
			{
				return $text;
			}
		}
		else
		{
			return $text;
		}
	}
	
	//alias 
	
	public static function __($text="")
	{
		return self::text($text);
	}
	
	public static function langTrigger($lang,$label)
	{
		echo getlink::set("language/".$lang,$label);
	}
	
	public static function setlang($code)
	{
		getcommon::setsession(array("SF_LANG"=>$code));
		echo "<script>history.back(-1);</script>";
	}
	
	
	/***** Function Parameter *****/
	public static function getParam($category="")
		{
			$signs = self::getParamSign();
			
			if(!empty($signs))
			{
				foreach($signs as $sign)
				{
					$ids[] = $sign->id;
				}
				
				$where = array("id IN"=>implode(",",$ids));
			}
			else
			{
				$where = array();
			}
			
			
			if(!empty($category)) 
			{
				$where["category"] = $category;
			}
		
			$getParams = self::getparamvalue(self::$model->get("parameter",$where,"array","`id`,`name`,`option`,`category`"));
			
			return $getParams;
		}
		
		public static function getParamId($params)
		{
			return $get = self::$model->get("parameter",array("name"=>$params));
		}
		
		//need param as key
		//and value as value
		public static function saveParamValue($post)
		{
			$params = array();
			$getUser = getutility::getuser();
			$user_id = (!empty($post['user_id']) ? $post['user_id'] : (!empty($getUser->id) ? $getUser->id : ""));
			
			$i = 0;
			foreach($post as $key=>$val)
			{
				$is_param = self::$model->get("parameter",array("name"=>$key));
				$is_valued = self::$model->get("parameter_value",array("user_id"=>$user_id));
				
				if(!empty($is_param->id))
				{
					$params[$i] = array("value"=>$val);
					
					if(empty($is_valued->id))
					{
						if(!empty($params[$i]))
						{
							$params[$i] = array_merge($params[$i],array("user_id"=>$user_id));
							$params[$i] = array_merge($params[$i],array("parameter_id"=>$is_param->id));
							$where[$i] = "";
						}
					}
					else
					{
						$where[$i] = array("user_id"=>$user_id);
						$where[$i] = array_merge($where[$i],array("parameter_id"=>$is_param->id));
					}
				}
				
				$i++;
			}
			
			for($j=0;$j<count($params);$j++)
			{
				self::$modelstore("parameter_value",$params[$j],$where[$j]);
			}
			
		}
		
		public static function getParamvalue($getParams="")
		{
			$getuser = getutility::getuser();
			if(!empty($getuser->id))
			{
				$params = array();
				foreach($getParams as $param)
				{
					$get = self::$model->get("parameter_value",array("parameter_id"=>$param->id,"user_id"=>$getuser->id));
					$param->value = (!empty($get->value) ? $get->value : "");
					
					$params[] = $param;
				}
				
				return $params;
			}
			else
			{
				return $getParams;
			}
		}
		
		
		//get parameter that signs by tipe_smember or user_id
		public static function getParamSign()
		{
			$getuser = getutility::getuser();
			if(empty($getuser->id))
			{
				$where['user_id'] = "";
				$where['tipe_member_id'] = "";
			}
			else
			{
				$where['user_id'] = $getuser->id;
				$where[''] = "||";
				$where['tipe_member_id'] = $getuser->tipe_member_id;
			}
			
			$params = self::$model->get("parameter_sign",$where,"array");
			return $params;
		}
		
	
	
	/***** Mail Section *****/
	
	public static function sendEmail($to,$subject,$message,$headers,$parameters)
	{
		mail($to,$subject,$message,$headers,$parameters);
	}
	
	public static function readEmail($email="",$password="",$search="")
	{
		$exhost = strpos($email,"@")+1;
		$getExHost = substr($email,$exhost);
		$exdelimiter = strpos($getExHost,".");
		$getHost = substr($getExHost,0,$exdelimiter);
		
		switch($getHost)
		{
			case "yahoo":
				$hostname = '{imap.mail.yahoo.com:993/imap/ssl}INBOX';
			break;
			
			case "gmail":
				$hostname = '{imap.gmail.com:993/imap/ssl/novalidate-cert}';
			break;
			
			default:
				$hostname = '{'.$getExHost.':143/novalidate-cert}INBOX';
			break;
		}
		
		if((empty($hostname) ) || (empty($email)) || (empty($password)))
		{
			echo "Masukkan alamat email && password dengan benar";
		}
		else
		{
			$imap_stream = imap_open($hostname,$email,$password) or die('Cannot connect to Mail: ' . imap_last_error());

			
			if(!empty($search))
			{
				$emails = imap_search($imap_stream,$search);	
			}
			else
			{
				$emails = imap_search($imap_stream,'all');	
			}
				
				if(!empty($emails))
				{
					$return['header'] = imap_fetch_overview($imap_stream,implode(",",$emails),FT_UID);
					$structure = imap_fetchstructure($imap_stream, $emails);
					
					foreach($emails as $email)
					{
						
						if($structure->encoding == 3) {
							$body = imap_base64(imap_fetchbody($imap_stream,$email,FT_UID));
						} else if($structure->encoding == 1) {
							$body = imap_8bit(imap_fetchbody($imap_stream,$email,FT_UID));
						} else {
							$body = imap_qprint(imap_fetchbody($imap_stream,$email,FT_UID));
						}
						
						if(empty($body))
						{
							$return['body'][] = imap_fetchbody($imap_stream,$email,FT_UID);
						}
						
						$return['body'][] = $body;
					}
				}
				else
				{
					$return['error'] = "Email Is Empty";
				}
					
				return $return;

			/* close the connection */
			imap_close($imap_stream);
		}
	}
	
	
	
	/***** Connect Section *****/
	
	public static function connectIdentify($token="")
	{
		if(!empty($token))
		{
			return self::getAccess($token);
		}
		else
		{
			return self::requestConnectToken();
		}
	}
	
	public static function removeWWW($identify)
	{
		$identify = strtolower($identify);
		$iswww = strpos($identify,"www");
		if(!empty($iswww))
		{
			return str_replace("www.","",$identify);
		}
		else
		{
			return $identify;
		}
	}
	
	public static function getAccess($token="")
	{
		$identity = self::removeWWW($_SERVER['HTTP_ORIGIN']);
	
		if((!empty($identity)) && (!empty($token)))
		{
		
			$allow = self::$model->get("SELECT * FROM connect WHERE token='".$token."' && domain='".$identity."' && status='Allow'","array");
			
			if(empty($allow))
			{
				$return['error'] = "ACCESS_DENIED";
				$return['msg'] = "Access tidak di izinkan";
			}
			else
			{
				if(!empty($_GET))
				{
					if(!empty($_GET['get']))
					{
						$return = self::getConnectRequest($_GET);
					}
					else if(!empty($_GET['set']))
					{
						$return = self::setConnectRequest($_GET);
					}
					else
					{
						$return['error'] = "UNKNOWN_REQUEST";
						$return['msg'] = "Permintaan tidak diketahui";
					}
				}
				else if(!empty($_POST))
				{
					if(!empty($_POST['get']))
					{
						$return = self::getConnectRequest($_POST);
					}
					else if(!empty($_POST['set']))
					{
						$return = self::setConnectRequest($_POST);
					}
					else
					{
						$return['error'] = "UNKNOWN_REQUEST";
						$return['msg'] = "Permintaan tidak diketahui";
					}
				}
				else
				{
					$return['error'] = "UNKNOWN_REQUEST";
					$return['msg'] = "Permintaan tidak diketahui";
				}
			}
		}
		else
		{
			$return['error'] = "EMPTY_IDENTITY";
			$return['msg'] = "Sertakan identity anda terlebih dulu";
		}
		
		echo json_encode($return);
	}
	
	public static function getConnectParam($param)
	{
		$keywords = array("identity","token","get","set");
		$params = array();
	
		foreach($param as $key=>$val)
		{
			$notallow = "";
			
			foreach($keywords as $word)
			{
				if(strtolower($key) == $word)
				{
					$notallow = 1;
				}
			}
			
			if(empty($notallow))
			{
				$params[] = $key."='".$val."'";
			}
		}
		
		if(!empty($params)) { return "WHERE ".implode(" && ",$params); } else { return ""; }
	}
	
	public static function setConnectParam($param)
	{
		$keywords = array("identity","token","get","set","order");
		$params = array();
	
		foreach($param as $key=>$val)
		{
			$notallow = "";
			
			foreach($keywords as $word)
			{
				if(strtolower($key) == $word)
				{
					$notallow = 1;
				}
			}
			
			if(empty($notallow))
			{
				$params["`".$key."`"] = "'".$val."'";
			}
		}
		
		if(!empty($params)) { return $params; } else { return ""; }
	}
	
	public static function getConnectRequest($request)
	{
		$get = $request['get'];
		$params = self::getConnectParam($request);
		$order = (!empty($request['order']) ? $order : "");
		
		$data = self::getConnectCriteria($get,$params,$order);
		
		$return['success'] = "Success";
		$return['msg'] = "Berhasil mengambil data ".$get;
		$return['data'] = $data;
		
		if(!empty($data))
		{
			foreach($data as $row)
			{
				$id[] = $row['id'];
			}
			
			if(!empty($id))
			{
				$return['recent'] = max($id);
			}
		}
		
		return $return;
	}
	
	public static function getConnectCriteria($get="",$params="",$order="")
	{
		if($get == "signal")
		{
			$sql = "SELECT * FROM ".$get." ".$params." ORDER BY last_modified DESC";
			$getSignal = self::$model->get($sql,"array");
			
			foreach($getSignal as $row)
			{
				$already = 0;
				
				if(!empty($signal))
				{
					foreach($signal as $key=>$val)
					{
						if($key == $row['pair'])
						{
							$already = 1;
							break;
						}
					}
				}
				
				if(empty($already))
				{
					$id[] = $row['id'];
					$signal[$row['pair']] = $row;
				}
			}
			
			$params = "WHERE id IN (".implode(",",$id).")";
			
			$query = "SELECT * FROM ".$get." ".$params." ORDER BY last_modified DESC";
		}
		else
		{
			$query = "SELECT * FROM ".$get." ".$params;
		}
		
		return self::$model->get($query,"array");
	}
	
	public static function setConnectRequest($request)
	{
		$set = $request['set'];
		$params = self::setConnectParam($request);

		
		$is_allow = framework::configDB("allow_client_update");
		
		if(!empty($is_allow))
		{
			$return = self::$model->query("INSERT INTO ".$set." (".implode(",",array_keys($params)).") VALUES (".implode(",",array_values($params)).")");
		}
		else
		{
			$identity = $request['identity'];
			unset($request['set'],$request['identity']);
			$params = implode(",",$request);
			
			$return = self::$model->query("INSERT INTO set_request (`domain`,`request`,`values`,`last_modified`) VALUES ('".$identity."','".$set."','".$params."','".date("Y-m-d H:i:s")."')");
		}
		
		if(!$return)
		{
			$return['error'] = "ERROR_SET_QUERY";
			$return['msg'] = "Gagal melakukan proses pengisian data";
		}
		else
		{
			$return['success'] = "SUCCESS";
			$return['msg'] = "Berhasil melakukan query";
		}
		
		return $return;
	}
	
	public static function requestConnectToken()
	{
		$identity = self::removeWWW($_SERVER['HTTP_ORIGIN']);
	
		if(empty($identity))
		{
			$return['error'] = "EMPTY_IDENTITY";
			$return['msg'] = "Identitas tidak diketahui";
		}
		else
		{
			$is_exist = self::$model->get("SELECT * FROM connect WHERE domain='".$identity."'");
			
			$token = md5(base_convert(mt_rand(), 10, 36));
			
			if(!empty($is_exist->id))
			{
				if($is_exist->status == "Allow")
				{
					if((!empty($_GET['connect_id'])) && (!empty($_GET['connect_key'])))
					{
						$connect_id = $_GET['connect_id'];
						$connect_key = $_GET['connect_key'];
						
						$is_authorized = self::$model->get("SELECT * FROM connect WHERE domain='".$identity."' && connect_id='".$connect_id."' && connect_key='".$connect_key."'");
						
						if(!empty($is_authorized->id))
						{
							self::$model->query("UPDATE connect SET token='".$token."',last_modified='".date("Y-m-d H:i:s")."' WHERE id='".$is_authorized->id."'");
						
							$return['success'] = "Success";
							$return['msg'] = "This is your token";
							$return['data'] = $token;
						}
						else
						{
							$return['error'] = "WRONG_AUTHORIZED";
							$return['msg'] = "Data yang anda kirimkan tidak valid";
						}
					}
					else
					{
						$return['error'] = "EMPTY_AUTHORIZED";
						$return['msg'] = "Sertakan id dan key anda untuk melakukan authorisasi";
					}
				}
				else
				{
					$return['error'] = "ACCESS_DENIED";
					$return['msg'] = "Akses tidak diizinkan";
				}
			}
			else
			{
				self::$model->query("INSERT INTO connect (`domain`,`status`) values ('".$identity."','Pending')");
			
				$return['success'] = "Success";
				$return['msg'] = "Processing Request";
			}
		}
		
		echo json_encode($return);
	}
	
	
	/***** Security Section ******/
	
	//get hash
	public static function hash($data)
	{
		return mhash(MHASH_MD5,$data,"SINC_FRAMEWORK_HASH");
	}
	
	//get encrypt data
	public static function encrypt($data)
	{
		$key = self::hash(framework::config("encrypt_key"));
		
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		
		$ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,$data, MCRYPT_MODE_CBC, $iv);
		$ciphertext = $iv . $ciphertext;
		
		return $ciphertext_base64 = base64_encode($ciphertext);
	}
	
	//get decrypt data
	public static function decrypt($encrypt_data)
	{
		$key = self::hash(framework::config("encrypt_key"));
		$ciphertext_dec = base64_decode($encrypt_data);
		
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		
		# retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
		$iv_dec = substr($ciphertext_dec, 0, $iv_size);
		
		# retrieves the cipher text (everything except the $iv_size in the front)
		$ciphertext_dec = substr($ciphertext_dec, $iv_size);

		# may remove 00h valued characters from end of plain text
		return $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,$ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
	}
	
	//clear tags
	public static function clearTags($data)
	{
		$return = array();
		
		if(!is_array($data))
		{
			$return = strip_tags(trim($data));
			$return = self::codeEntities($data);
		}
		else
		{
			foreach($data as $key=>$val)
			{
				if(!empty($val))
				{
					$val = strip_tags(trim($val));
					$val = self::codeEntities($val);
				}
				
				$return[$key] = $val;
			}
		}
		
		return $return;
	}
	
	//change symbol to entities
	public static function codeEntities($data)
	{
		if(isset($data))
		{
			$data =  htmlentities ( trim($data) , ENT_NOQUOTES );
		}
		
		return $data;
	}
	
	//unset not allowed word
	public static function filterWords($data)
	{
		$not_allow = array("fuck","suck");
		
		foreach($not_allow as $word)
		{
			$data = substr($word," ",strtolower($data));
		}
		
		return $data;
	}
	
	//validate email
	public static function isValidEmail($email)
	{
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}
	
	
	public static function filterType($type,$filter)
	{
		switch($type)
		{
			case "flags":
			return self::flags($filter);
			break;
			
			case "sanitize":
			return self::sanitize($filter);
			break;
			
			case "other":
			return self::other($filter);
			break;
			
			default:
			return self::validate($filter);
			break;
		}
	}
	
	public static function validate($filter)
	{
		switch($filter)
		{
			case "boolean":
				return "FILTER_VALIDATE_BOOLEAN";
			break;
			
			case "email":
				return "FILTER_VALIDATE_EMAIL";
			break;
			
			case "float":
				return "FILTER_VALIDATE_FLOAT";
			break;
			
			case "int":
				return "FILTER_VALIDATE_INT";
			break;
			
			case "ip":
				return "FILTER_VALIDATE_IP";
			break;
			
			case "regex":
				return "FILTER_VALIDATE_REGEXP";
			break;
			
			default:
				return "FILTER_VALIDATE_URL";
			break;
		}
	}
	
	public static function sanitize($filter)
	{
		switch($filter)
		{
			case "boolean":
				return "FILTER_SANITIZE_EMAIL";
			break;
			
			case "email":
				return "FILTER_SANITIZE_ENCODED";
			break;
			
			case "float":
				return "FILTER_SANITIZE_MAGIC_QUOTES";
			break;
			
			case "int":
				return "FILTER_SANITIZE_NUMBER_FLOAT";
			break;
			
			case "ip":
				return "FILTER_SANITIZE_NUMBER_INT";
			break;
			
			case "regex":
				return "FILTER_SANITIZE_FULL_SPECIAL_CHARS";
			break;
			
			case "regex":
				return "FILTER_SANITIZE_STRING";
			break;
			
			case "regex":
				return "FILTER_SANITIZE_STRIPPED";
			break;
			
			case "regex":
				return "FILTER_SANITIZE_URL";
			break;
			
			default:
				return "FILTER_UNSAFE_RAW";
			break;
		}
	}
	
	public static function other($filter)
	{
		switch($filter)
		{
			default:
				echo "FILTER_CALLBACK";
			break;
		}
	}
	
	public static function flags($filter)
	{
		switch($filter)
		{
			case "strip_low":
				return "FILTER_FLAG_STRIP_LOW";
			break;
			
			case "strip_high":
				return "FILTER_FLAG_STRIP_HIGH";
			break;
			
			case "fraction":
				return "FILTER_FLAG_ALLOW_FRACTION";
			break;
			
			case "thousand":
				return "FILTER_FLAG_ALLOW_THOUSAND";
			break;
			
			case "scientific":
				return "FILTER_FLAG_ALLOW_SCIENTIFIC";
			break;
			
			case "quote":
				return "FILTER_FLAG_NO_ENCODE_QUOTES";
			break;
			
			case "low":
				return "FILTER_FLAG_ENCODE_LOW";
			break;
			
			case "encode_high":
				return "FILTER_FLAG_ENCODE_HIGH";
			break;
			
			/*
			
				FILTER_FLAG_ENCODE_AMP	FILTER_SANITIZE_STRING, FILTER_SANITIZE_RAW	 Encodes ampersands (&).
				FILTER_NULL_ON_FAILURE	FILTER_VALIDATE_BOOLEAN	 Returns NULL for unrecognized boolean values.
				FILTER_FLAG_ALLOW_OCTAL	FILTER_VALIDATE_INT	 Regards inputs starting with a zero (0) as octal numbers. This only allows the succeeding digits to be 0-7.
				FILTER_FLAG_ALLOW_HEX	FILTER_VALIDATE_INT	 Regards inputs starting with 0x or 0X as hexadecimal numbers. This only allows succeeding characters to be a-fA-F0-9.
				FILTER_FLAG_IPV4	FILTER_VALIDATE_IP	 Allows the IP address to be in IPv4 format.
				FILTER_FLAG_IPV6	FILTER_VALIDATE_IP	 Allows the IP address to be in IPv6 format.
				FILTER_FLAG_NO_PRIV_RANGE	FILTER_VALIDATE_IP	
				Fails validation for the following private IPv4 ranges: 10.0.0.0/8, 172.16.0.0/12 and 192.168.0.0/16.

				Fails validation for the IPv6 addresses starting with FD or FC.

				FILTER_FLAG_NO_RES_RANGE	FILTER_VALIDATE_IP	 Fails validation for the following reserved IPv4 ranges: 0.0.0.0/8, 169.254.0.0/16, 192.0.2.0/24 and 224.0.0.0/4. This flag does not apply to IPv6 addresses.
				FILTER_FLAG_PATH_REQUIRED	FILTER_VALIDATE_URL	 Requires the URL to contain a path part.
				FILTER_FLAG_QUERY_REQUIRED	
			
			*/
			
			
			
			
		}
	}
	
	public static function encrypt_md5($data)
	{
		return md5($data);
	}
	

	
	/**** Authentication *****/
	public static function authLogin($view="",$callback="",$register="")
	{
		$is_login = self::getUser();
		$type_login = self::requestvar('type');
		$auth_key = framework::config('auth_key');
		
		if(empty($is_login->id))
		{
			if(!empty($type_login))
			{
				$username = self::getvar('username');
				$password = self::getvar('password');
				
				$auth = self::authRegister($username,$password);
				
				self::authViewLogin(array("error"=>$auth['msg']),$callback,$register);
			}
			
			$do_login = self::authDoLogin();
			
			if(!empty($do_login['error']))
			{
				$parse['error'] = $do_login['msg'];
				self::AuthViewLogin($parse,$callback,$register);
			}
			else
			{
				getcommon::setsession(array($auth_key=>$do_login['data']->id,"email"=>$do_login['data']->email));
				header("location:".SITE_URL.$view);
			}
		}
		else
		{	
			return;
		}
	}
	
	public static function authRegister($email="",$password="")
	{
		if((!empty($email)) && (!empty($password)))
		{
			if(!filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				$return['error'] = "Email_Not_Valid";
				$return['msg'] = "Masukkan Alamat Email anda dengan benar";
			}
			else
			{
				$getuser = self::$model->get("user",array("email"=>$email),"object");
				if(!empty($getuser->id))
				{
					$return['error'] = "Email_Not_Valid";
					$return['msg'] = "Email Yang anda gunakan telah terdaftar";
				}
				else
				{
					self::$model->query("INSERT INTO user (email,password,tipe_member_id,last_modified,status) VALUES ('".$email."','".md5($password)."','4','".date('Y-m-d H:i:s')."','Inactive')");
					$return['msg'] = "Berhasil Terdaftar";
				}
			}
		}
		else
		{
			$return['error'] = "Empty_Fields";
			$return['msg'] = "Masukkan Username dan Password";
		}
		return $return;
	}
	
	public static function getUser()
	{
		$auth_key = framework::config("auth_key") or die("Please Provide Auth Key in configuration");
		$session = getcommon::getsession($auth_key);
		if(!empty($session))
		{
			return self::$model->query("SELECT * FROM user WHERE id ='".$session."'","object");
		}
	}
	
	/* 
		checked privilege
		Menu : string(class/function)
		Action : String("View"|"UPDATE"|"Edit"|"DELETE")
	*/
	public static function isAllowAccess($action="view",$menu="")
	{
		if(empty($menu))
		{
			$menu = self::path(1)."/".self::path(2);
			if(empty($menu))
			{
				$menu = "index.php";
			}
		}
		
		$getUser = self::getUser();
		
		if(!empty($getUser->id))
		{
			
			$catch = self::$model->get("privilege",array("type"=>"user_id","type"=>$getUser->id,"menu"=>$menu));
			if(!empty($catch->id))
			{
				$catch = self::$model->get("privilege",array("type"=>"tipe_member_id","type"=>$getUser->tipe_member_id,"menu"=>$menu));
			}
			
			//aksees tak terbatas untuk super users
			if(!empty($catch->id))
			{
				if(!empty($catch))
				{
					$getAction = explode(";",$catch->action);
					
					foreach($getAction as $cekAction)
					{
						if($cekAction == $action)
						{
							return 1;
						}
					}
				}
			}
		}
		else
		{
			return 0;
		}
	}
  
	/*
		set privilege
		$type = string("tipe_member_id" |  "user_id")
		$type_id = string($tipe_member_id |  $user_id)
		$menu = string("menu");
		$action = array("action1","action2") ["view" | "Edit" | "Delete"]
	*/
	public static function setprevilage($type="tipe_member_id",$type_id="",$menu="",$action="view")
	{
		if((!empty($type_id)) && (!empty($menu)))
		{
			if(!is_array($action))
			{
				$action = array($action);
			}
			
			$exist = self::$model->get("privilege",array("type"=>$type,"type_id"=>$type_id,"menu"=>$menu));
			if(!empty($exist->id))
			{
				$where = array("id"=>$exist->id);
			}
			else
			{
				$where = "";
			}
			
			self::$modelstore("privilege",array("type"=>$type,"type_id"=>$type_id,"menu"=>$menu,"action"=>implode(";",$action),"last_modified"=>date("Y-m-d H:i:s")),$where);
			
		}
	}
  
	public static function authLogout($callback="")
	{
		echo getsession::destroy();
		header('location:'.SITE_URL.$callback);
	}
	
	private static function authDoLogin()
	{
		//get::direct("model","m_user.php");
		$post = self::requestPost();
		
		if((empty($post['username'])) || (empty($post['password'])))
		{
			$return['error'] = "Empty_Fields";
			$return['msg'] = "Masukkan Username dan Password";
		}
		else
		{
			if(!filter_var($post['username'],FILTER_VALIDATE_EMAIL))
			{
				$where = array("username"=>$post['username'],"status"=>"Active");
			}
			else
			{
				$where = array("email"=>$post['username']);
			}

			$getuser = self::$model->get("user",$where,"object");
			if(!empty($getuser->id))
			{
				if(md5($post['password']) == $getuser->password)
				{
					$return['success'] = "Success";
					$return['msg'] = "Berhasil Login";
					$return['data'] = $getuser;
				}
				else
				{
					$return['error'] = "Wrong_Password";
					$return['msg'] = "Password yang anda masukkan salah";
				}
			}
			else
			{
				$return['error'] = "Username_Unregistered";
				$return['msg'] = "Username yang anda masukkan tidak terdaftar";
			}
		}
		
		return $return;
	}
	
	
	
	public static function authViewLogin($parse="",$callback="",$register="")
	{
		error_reporting(0);
	
		echo "<div class='msg'>".(!empty($parse['error']) ? $parse['error'] : "")."</div>";
		echo gethtml::form($callback,array("method"=>"post","id"=>"authlogin"));
			echo gethtml::formInput("username","",array("placeHolder"=>"Email / Username"))."<br />";
			echo gethtml::formInput("password","",array("type"=>"password","placeHolder"=>"password"))."[Forgot Password ?]<br />";
			echo gethtml::formSubmit("Login");
			echo (!empty($register) ? gethtml::formSubmit("Register",array("type"=>"submit","class"=>"register","onclick"=>"regclick()")) : "");
			echo gethtml::formInput("type","",array("type"=>"hidden","id"=>"typelogin"));
		echo gethtml::form("close");
		
		echo (!empty($register) ? "<script>
			function regclick()
			{
				document.getElementById('typelogin').value='reg';
				return true;
			}
		</script>" : "");
		
		break;
	}
	
	public static function setToolbar($toolbars,$attr="")
	{
		//$toolbar = self::js("sinc.js");
		$toolbar = "";
		foreach($toolbars as $key=>$val)
		{
			$toolbar .= gethtml::forminput($key,$val,array("class"=>"sinc_toolbar","id"=>$key,"type"=>"button"));
		}
		
		$action = (!empty($attr['action']) ? $attr['action'] : "action");
		
		$toolbar .= gethtml::forminput($action,"",array("id"=>$action,"type"=>"hidden"));
		$toolbar .= gethtml::forminput("attr",$attr['name'],array("class"=>"attr_name","type"=>"hidden"));
		$toolbar .= gethtml::forminput("attr",$action,array("class"=>"attr_action","type"=>"hidden"));
		//$toolbar .= gethtml::forminput($label,$label,array("type"=>"button"));
		
		return $toolbar;
	}
	
}

?>