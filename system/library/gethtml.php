<?php

namespace system\library;

use system\framework as framework;
use system\library\getcommon as getcommon;
use system\library\getutility as getutility;

	class gethtml
	{
		
		public static $route;
		private static $lists;
		
		function __construct()
		{
			self::$route = framework::get('config/route');
			self::$lists = framework::get(LIBRARY_PATH."lists");
		}
		
		/* 	Generally html function to set element in html 
			Css, js, meta, and others
		*/
	
	
		/* link rel to stylesheet */
		public static function css($css="",$path="")
		{
			$path = (empty($path) ? INCLUDE_URL."stylesheet/" : $path);
			
			$css = self::isInternal($css,$path);
		
			return "<link rel='stylesheet' href='".$css."' type='text/css' />";
		}
		
		/* script src for javascript */
		public static function js($js="",$path="")
		{
			$path = (empty($path) ? INCLUDE_URL."script/" : $path);
		
			$js = self::isInternal($js,$path);
			return "<script src='".$js."' text='text/javascript' ></script>";
		}
		
		/* image */
		public static function image($image="",$attribute="")
		{
			//$image = self::isInternal($image,IMAGE_URL);
			$attribute = getutility::setstring($attribute," "," ","=","'");
			
			return "<img src='".$image."' ".$attribute." />";
		}
		
		private static function isInternal($include,$path)
		{
			//cek location images
			if(!is_numeric(strpos($include,"http:")))
			{
				$include = $path.$include;
			}
			
			return $include;
		}
		
		/**
		* Form Section 
		* Function form here		
		*/
		//automatically set input with one params
		public static function setinput($params="",$params2="",$params3="",$params4="")
		{	
			//$params = (!is_array($params) ? (array) $params : "");
			if(!is_array($params)) 
			{	
				//echo $params." dan ".$params2." dan ";print_r($params3);echo " dan ".$params4." <br />";
				return self::defineinput($params,$params2,$params3,$params4);
			}
			
			if(!empty($params))
			{
				$name = (!empty($params['name']) ? $params['name'] : "");
				$value = (!empty($params['value']) ? $params['value'] : "");
				$label = (!empty($params['label']) ? $params['label'] : $params['name']);
				
				$label = ucfirst(str_replace("_"," ",$label));
				
				$params['type'] = (!empty($params['type']) ? $params['type'] : (!empty($params['option']) ? $params['option'] : ""));
				
				return self::defineinput($name,$value,$params,$label);
			}
		}
		
		//define set input type 
		public static function defineinput($name="",$value="",$attribute="",$label="")
		{
			$type = (!empty($attribute['type']) ? $attribute['type'] : "");
			
			if(is_numeric(strpos($type,"button")))
			{
				return self::formbutton($name,$value,$attribute,$label);
			}
			else if(is_numeric(strpos($type,"textarea")))
			{
				return self::formtextarea($name,$value,$attribute,$label);
			}
			else if(is_numeric(strpos($type,"submit")))
			{
				return self::formsubmit($value,$value,$attribute,$label);
			}
			else if((is_numeric(strpos($type,"lists"))) || (is_numeric(strpos($type,","))) || (is_array($type)))
			{
				$attribute['name'] = $name;
				return self::formlists($type,$value,$attribute,$label);
			}
			else
			{
				return self::forminput($name,$value,$attribute,$label);
			}
		}
		
		//open form
		public static function form($action="",$attribute="")
		{	
			if(strtolower($action) == "close")
			{
				return "</form>";
			}
			
			if(empty($attribute['method'])) { $attribute['method'] = "POST"; }
			if(empty($attribute['name'])) { $attribute['name'] = "default_form"; }
			
			$attribute = getutility::setstring($attribute," "," ","=","'");
			
			$action = self::triggerUrl($action);
		
			return "<form action='".SITE_URL.$action."' ".$attribute." >";
		}
		
		/*
		//close form & hidden input
		public static function close($hidden="")
		{
			$form = "";
			if(!empty($hidden))
			{
				foreach($hidden as $key=>$val)
				{
					$form .= self::forminput($key,$val,array("id"=>$key,"type"=>"hidden"));
				}
			}
			$form .="</form>";
			
			return $form;
		}
		*/
		
		/* parameter
		$lists = array lists
		$attribute = array
			- name
			- id
			- list_key
			- list_value
			- placeholder
		$chosen = true / false
		*/
		public static function formlists($lists="",$value="",$attribute="",$label="")
		{
			
			//is custom lists
			if(!is_array($lists))
			{
				$delimiter = strpos($lists,",");
				if(is_numeric($delimiter))
				{
					$lists = explode(",",$lists);
				}
				else
				{
					if(method_exists("lists",$lists))
					{
						$setParams = (!empty($attribute['params']) ? $attribute['params'] : "");
						$lists = self::$lists->$lists($setParams);
						
						if(!empty($setParams)) { unset($attribute['params']); }
					}
				}
			}
			
			$select = "<label>".$label."</label>";
			
			
			$getAttribute = (!empty($attribute) ? getutility::setstring($attribute," "," ","=","'") : "");
		
			
			if(empty($lists))
			{
				$select .= "<select ".$getAttribute." >";
					$select .= "<option value=''>".getutility::text('system.option_not_available')."</option>";
				$select .= "</select>";
			}
			else if(count($lists) <= 2)
			{
				//$i = 0;
				foreach($lists as $key=>$val)
				{
					$key = ($key == 0 ? $val : $key);
					if((!empty($value)) && ($value == $key))
					{
						$select .= "<input type='radio' value='".$key."' ".$getAttribute." checked='checked' />".$val;
					}
					else
					{
						$select .= "<input type='radio' value='".$key."' ".$getAttribute." />".$val;
					}
					//$i++;
				}
			}
			else
			{
		
				$select .= "<select ".$getAttribute." >";	
				$i = 0;
				foreach($lists as $key=>$val)
				{
					if(is_numeric($key))
					{
						if($key == $i)
						{
							$key = $val;
						}
					}
					
					if((!empty($value)) && ($value == $key))
					{
						$select .= "<option value='".$key."' selected='selected' >".$val."</option>";
					}
					else
					{
						$select .= "<option value='".$key."'>".$val."</option>";
					}
					$i++;
				}
				$select .= "</select>";

			}
				
			return $select;
		}
		
		
		public static function formdatalist($name="",$lists="",$label="")
		{
			$datalist = "<label>".$label."</label>";
			$datalist .= "<input list='".$name."'>";
			$datalist .= "<datalist id='".$name."'>";
			
			foreach($lists as $list)
			{
				$datalist .= "<option value='".$list."'>";
			}
			
			$datalist .= "</datalist>";
		}
		
		/* <keygen name="security"> dan <output>*/
		
		//input type
		public static function forminput($name="",$value="",$attr="",$label="")
		{
			/*
				*** New Input Type In HTML5 ***
				color, date, datetime, datetime-local, email, month, number, range, search, tel, time, url, week,
				
				*** New Input Parameter In HTML ***
				autocomplete, autofocus, form, formaction, formenctype, formmethod, formnovalidate, formtarget, height and width, list, min and max, multiple, pattern (regexp), placeholder, required, step, 
			*/
			
			if(!is_array($attr))  {  			
				$attribute = array();
				$attribute['type'] = $attr;
			} else  {
				$attribute = $attr;
				$attribute['type'] = (!empty($attribute['type']) ? $attribute['type'] : "text");
		}
			
			//hidden label
			$label = ($attribute['type'] == "hidden" ? "" : $label);
			$extra = "";
			
			if(!empty($value))
			{
				if(strtolower($attribute['type']) == "password")
				{
					$attribute['value'] = "";
					$extra = "<input type='hidden' name='old_".$name."' value='".$value."' />";
				}
				else
				{
					$attribute['value'] = $value;
				}
			}
			else
			{
				$attribute['value'] = "";
				
			}
			//$attribute['value'] = (!empty($value) ? (strtolower($attribut['type']) == "password" ? "<input type='hidden' name='old_".$name."' value='".$value."' />" : "") : (!empty($attribute['value']) ? $attribute['value'] : ""));
			
			$attribute = getutility::setstring($attribute," "," ","=","'");
		
			return "
				<label>".$label."</label>
				<input name='".$name."' ".$attribute." />".$extra;
		}
		
		public static function formtextarea($name="",$value="",$attribute="",$label="")
		{
			$attribute = getutility::setstring($attribute," "," ","=","'");
		
			return "
				<label>".$label."</label>
				<textarea name='".$name."' ".$attribute." />".$value."</textarea>";
		}
		
		//alias button
		public static function formbutton($value="",$attribute="",$label="")
		{
			$attribute = (empty($attribute) ? "button" :  $attribute);
			return self::forminput("",$value,$attribute,$label);
		}
		
		//alias submit
		public static function formsubmit($value="",$attribute="",$label="")
		{
			$attribute = (empty($attribute) ? "submit" :  $attribute);
			return self::forminput("",$value,$attribute,$label);
		}
		
		
		
		/* 	Table Function
			this function hadle data table 
		*/
		
		public static function table($getdata="",$th="",$td="",$attribute="",$callback="")
		{		
			if(!empty($attribute)) { $attribute = getutility::setstring($attribute," "," ","=","'"); }
		
			if(!empty($getdata['data']))
			{
				$data = $getdata['data'];
				$page = $getdata['page'];
			}
			else
			{
				$data = $getdata;
				$page = "";
			}
		
			$table = "<div class='getpage_table'><table class='table'>";
			
			if(!empty($th))
			{
				$th = (!is_array($th) ? (array) $th : $th);
				$table .= "<tr>";
				foreach($th as $th_item)
				{
					$table .= "<th class='pagefield' id='".$th_item."'>".$th_item."</th>";
				}
				$table .= "</tr>";
			}
			
			$total = count($data);
			if($total >= 1)
			{	
				for($i=0;$i<count($data);$i++)
				{
					$row = (is_array($data) ? $data[$i] : $data->$i);
					
					$table .= "<tr>";
					foreach($td as $td_item)
					{
						$table .= "<td class='pagedata'>".(is_array($row) ? $row[$td_item] : $row->$td_item)."</td>";
					}
					$table .= "</tr>";
				}
			}
			
			$table .= self::form_input("site_url",array("value"=>SITE_URL,"type"=>"hidden","class"=>"site_url"));
			$table .= self::form_input("getpage_callback",array("value"=>$callback,"type"=>"hidden","class"=>"getpage_callback"));
			$table .= "</table>";
			$table .= $page;
			$table .= "</div>";
			
			return $table;
		}
		
		/* 	URL FUnction 
			seturl, and other */
			
		/* 	function setlink(string ($url), string(label), string ($type),array($attribute))
	*/ 
	
		public static function url($url="",$label="",$type="",$attribute="")
		{
			$link = self::defineUrl($url,$type);
			$url = self::triggerUrl($url);
			
			return "<a href='".$link.$url."' ".getutility::setString($attribute,""," ","=","'").">".$label."</a>";
		}
		
		//create trigger for trigger url;
		public static function triggerUrl($url)
		{
			$isTrigger = self::$route->config('route_trigger');
			$getalias = self::$route->alias();
			
			
			
			if($isTrigger > 0)
			{
				if(is_numeric(strpos($url,"index.php")))
				{
					$ex = explode("index.php/",$url);
					$url = $ex[1];
				}
				
				$url = explode("/",$url);
			
					
				$j=1;
				for($i=0;$i<count($url);$i++)
				{
					if(empty($getalias[$i+1]))
					{
						$alias = "route_params".$j;
						$j++;
					}
					else
					{
						$alias = $getalias[$i+1];
					}
					
					//$getUrl[$getalias[$i+1]] = $url[$i];
					if(!empty($url[$i]))
					{
						$getUrl[] =  $alias."=".$url[$i];
					}
				}
				
				$getUrl = "?".implode("&",$getUrl);
				
			}
			else
			{
				$getUrl = $url;
			}
			
			return $getUrl;
		}
		
		public static function groupUrl($url="",$type="",$attribute="")
		{
			if(!is_array($url))
			{
				$url = array($url);
			}
			
			$link = self::defineLink($url,$type);
			
			foreach($url as $key=>$val)
			{
				if(empty($key)) { $key = $val; }
				echo "<a href='".$link.$val."' ".getutility::setString($attribute,""," ","=","'").">".$key."</a>";
			}
		}
		
		/* define link is anchor, external url, mail url and other */
		
		public static function defineUrl($url="",$type="")
		{
			if(!empty($type))
			{	
				switch($type)
				{
					case "external":
					$link = "";
					break;
					
					case "mail":
					case "email":
					$link = "mailto:";
					break;
					
					case "skype":
					case "call":
					$link = "callto:";
					break;
					
					case "ym":
					case "yahoo_messanger":
					$link = "ymsgr:sendIM?";
					break;
					
					case "anchor":
					case "#":
					$link = "";
					break;
					
					default : 
					$link = SITE_URL;
					break;
				}	
			}
			else
			{
				if(is_numeric(strpos($url,"#")))
				{
					$link = "";
				}
				else if(is_numeric(strpos($url,"http")))
				{
					$link = "";
				}
				else
				{
					$link = SITE_URL;
				}
			}
		
			
			
			return $link;
		}
		
		public static function redirect($url="",$msg="",$class="")
		{
			getcookie::set(array("message"=>$msg,"style_message"=>$class));
			header("location:".$url);
		}
			
		public static function table_checkbox($value="")
		{
			return gethtml::forminput("table_checkbox[]",$value,array("type"=>"checkbox"));
		}
		
		public static function test(){
			echo "working";
		}
	}

?>