<?php

$site_name = 'Code4Core';

$host = $_SERVER['HTTP_HOST'];

$http = 'http';
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on')
	$http = 'https';
	
$script = $_SERVER['SCRIPT_NAME'];
$dir = substr($script, 0, strrpos($script, '/'));
$base = str_replace('install', '', $dir);


$local_server = $host;
$local_db_name = 'my_web_db';
$local_db_user = 'root';
$local_db_pass = '';
$local_url = $http.'://'.$host.'';
$local_base_dir = $base;

$online_server = 'mysql13.freehostia.com';
$online_db_name = 'alaans_db';
$online_db_user = 'alaans_db';
$online_db_pass = 'alauddin';
$online_url = 'http://imwonder.freehostia.com';
$online_base_dir = '/';
$time_stamp = time();

$content = "<?php 
@ini_set('display_errors', 'on');
date_default_timezone_set('Asia/Kolkata');

require_once(dirname(__FILE__).'/autoload.php');

if(\$_SERVER['HTTP_HOST'] == '".$local_server."'){
	define('_DB_SERVER_', 'localhost');
	define('_DB_NAME_', '".$local_db_name."');
	define('_DB_USER_', '".$local_db_user."');
	define('_DB_PASSWD_', '".$local_db_pass."');
	define('__BASE_URL__', '".$local_url."');
	define('__BASE_URI__', '".$local_base_dir."');
} else {
	define('_DB_SERVER_', '".$online_server."');
	define('_DB_NAME_', '".$online_db_name."');
	define('_DB_USER_', '".$online_db_user."');
	define('_DB_PASSWD_', '".$online_db_pass."');
	define('__BASE_URL__', '".$online_url."');
	define('__BASE_URI__', '".$online_base_dir."');
}

define('_SITE_NAME_', '".$site_name."');
define('_COOKIE_KEY_', '".md5('AlauddinCookie'.$site_name)."');
define('_SESSION_KEY_', '".md5('AlauddinSession'.$site_name)."');

require_once(dirname(__FILE__).'/define.php');
require_once(dirname(__FILE__).'/tables.php');

?>";

$config_path = dirname(__FILE__).'/../include/config/config.inc.php';

if(!file_exists($config_path)){
	$file = fopen($config_path, 'w');
	fwrite($file, $content);
	fclose($file);
	echo 'File created successfully.';
}

?>