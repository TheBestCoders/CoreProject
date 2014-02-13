<?php

/**
* Disclaimer
* Don't try to delete pre defined constants, methods and variables
* Otherwise this framework won't work properly or stopped permanantly
* You can just change their values only
* Created by: 
* Alauddin Ansari
* alauddin_ansari@live.com
* Version: v1.0
*/

ini_set("display_errors", 1);
error_reporting(E_ALL);
//ini_set('error_reporting', E_ALL);
date_default_timezone_set('Asia/Kolkata');

// Database constants
if($_SERVER['HTTP_HOST'] == "localhost"){
	define('_DB_SERVER_', 'localhost');
	define('_DB_NAME_', 'my_web_db');
	define('_DB_USER_', 'root');
	define('_DB_PASSWD_', '');
} else {
	define('_DB_SERVER_', 'mysql13.freehostia.com');
	define('_DB_NAME_', 'alaans_db');
	define('_DB_USER_', 'alaans_db');
	define('_DB_PASSWD_', 'alauddin');
}

// site constants
define('_SITE_NAME_', 'Alauddin Ansari');
define('_COOKIE_KEY_', '00102c7749a0d26d598a337a2ce78590');
define('_SESSION_KEY_', '85102c7749a0d26d598a337a2ce78590');

// mailer
define('_DEFAULT_MAIL_TYPE_', 'SMTP');
define('_MAIL_SMTP_', 'smtp.gmail.com');
define('_MAIL_PORT_', 465);
define('_MAIL_USERNAME_', 'alauddin.mails@gmail.com');
define('_MAIL_PASSWORD_', 'alauddin@mails');
define('_MAIL_FROM_', 'alauddin.mails@gmail.com');
define('_MAIL_FROM_NAME_', _SITE_NAME_);

// website
define('_FRIENDLY_URL_', 0);
define('_CACHE_', 0);
define('_CACHE_CSS_', 0);
define('_DEBUG_', 1);
define('_MAINTENANCE_MODE_', 0);

// currency
define('_CURRENCY_SYMB_', 'Rs.');
define('_CURRENCY_TYPE_', currency_us);
define('_CURRENCY_GAP_', ' ');

// language
define('_DEFAULT_LANG_', 1);
define('_COOKIE_EXPIRE_', 60 * 60 * 24 * 365); // 365 days

// pagination
define('_PER_PAGE_', 10);

// site url
define('_BASE_URL_', 'http://localhost/myweb/');

?>