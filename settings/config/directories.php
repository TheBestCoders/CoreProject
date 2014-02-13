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

if(!defined('_BASE_URL_')){
	$url = '';
	if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$url = 'https://';
	} else {
		$url = 'http://';
	}
	$url .= $_SERVER['HTTP_HOST'];
	
	$dirs = explode('/', $_SERVER['SCRIPT_NAME']);
	array_pop($dirs);
	$uri = implode('/', $dirs).'/'; // define root path: /myweb/
	define('_BASE_URL_', $url.$uri);
}

define('_ROOT_DIR_', realpath(dirname(__FILE__).'/../../').'\\'); // Physical path: D:/xampp/htdocs/myweb/

// directories name only
define('_MEDIA_', 'media/');
define('_ADMIN_DIR_', 'admin/');
define('_IMG_', _MEDIA_.'images/');
define('_CSS_', _MEDIA_.'css/');
define('_JS_', _MEDIA_.'js/');
define('_UPLOAD_', 'uploads/');
define('_TPL_', 'views/');
define('_MAIL_', 'mails/');
define('_LANG_', 'languages/');
define('_PDF_', 'pdf/');
define('_DOC_', 'doc/');

// templates
define('_TPL_DIR_', _ROOT_DIR_._TPL_); // physical path
define('_TPL_URL_', _BASE_URL_._TPL_); // virtual url
define('_TPL_PATH_', _TPL_); // dircotry name

// image, css, js
define('_IMG_URL_', _BASE_URL_._IMG_); // virtual url
define('_CSS_URL_', _BASE_URL_._CSS_); // virtual url
define('_JS_URL_', _BASE_URL_._JS_); // virtual url

// upload
define('_UPLOAD_URL_', _BASE_URL_._UPLOAD_); // virtual url
define('_UPLOAD_DIR_', _ROOT_DIR_._UPLOAD_); // physical path

define('_PDF_URL_', _BASE_URL_._PDF_);
define('_PDF_DIR_', _UPLOAD_DIR_._PDF_);

define('_DOC_URL_', _BASE_URL_._DOC_);
define('_DOC_DIR_', _UPLOAD_DIR_._DOC_);

// cache
define('_CACHE_DIR_', _ROOT_DIR_.'cache/'); // direcotry name

// admin
define('_ADMIN_CSS_URL_', _BASE_URL_._ADMIN_DIR_.'css/'); // virtual url
define('_ADMIN_JS_URL_', _BASE_URL_._ADMIN_DIR_.'js/'); // virtual url

// mail, language directory
define('_MAIL_DIR_', _ROOT_DIR_._MAIL_); // physical path
define('_LANG_DIR_', _ROOT_DIR_._LANG_); // physical path


?>