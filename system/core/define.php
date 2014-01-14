<?php

use system\framework as framework;

//Define

$INDEX_POS = strpos($_SERVER['PHP_SELF'],"index.php");
$STRIP_INDEX = substr($_SERVER['PHP_SELF'],0,$INDEX_POS);

define("EXT",".php");
define("DS","/");
$del = explode(",","\,/");
define("BS",$del[0]);

//General Define
define("SCHEME" , $_SERVER['REQUEST_SCHEME']);
define("BASE_URL",SCHEME."://".$_SERVER['HTTP_HOST'].$STRIP_INDEX);
define("SITE_URL",SCHEME."://".$_SERVER['HTTP_HOST'].$STRIP_INDEX."index.php/");
define("IDENTITY",$_SERVER['HTTP_HOST']);
define("WEB_ROOT_DIR",$_SERVER['DOCUMENT_ROOT'].$STRIP_INDEX);
define("ROOT_DIR",$_SERVER['DOCUMENT_ROOT']);

define("INCLUDE_DIR",LOCAL_DIR.BS.DIR_INCLUDE);
define("SYSTEM_DIR",LOCAL_DIR.BS.DIR_SYSTEM);
define("CONFIG_DIR",LOCAL_DIR.BS.DIR_CONFIG);
define("APP_DIR",LOCAL_DIR.BS.DIR_APP);

define("INCLUDE_PATH",DIR_INCLUDE.DS);
define("LIBRARY_PATH",DIR_INCLUDE.DS."library/");
define("APP_PATH",DIR_APP.DS);
define("SYSTEM_PATH",DIR_SYSTEM.DS);
define("CONFIG_PATH",DIR_CONFIG.DS);

define("INCLUDE_URL",BASE_URL.DIR_INCLUDE.DS);
define("APP_URL",BASE_URL.DIR_APP.DS);
define("SYSTEM_URL",BASE_URL.DIR_SYSTEM.DS);
define("CONFIG_URL",BASE_URL.DIR_CONFIG.DS);

define("SF_LANG",framework::get('language'));
?>