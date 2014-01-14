<?php


//our local directory
define("LOCAL_DIR",dirname(__FILE__));

/* Feel Free To Change App, System, Include or Configuration Directory */

//Main or App Path
define('DIR_APP','app');

//System Path
define('DIR_SYSTEM','system');

//Configuration Path
define('DIR_CONFIG','config');

//include Path
define('DIR_INCLUDE','include');

//load framework
require_once('system/framework.php');
use system\framework as framework;

//execution framework
$framework = framework::instance();
$framework->exec();

exit();
?>