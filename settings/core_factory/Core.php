<?php

/**
* Core methods for this framework
* All methods are accessed by statically
* Ex. Core::getValue('test');
* Created by: 
* Alauddin Ansari
* alauddin_ansari@live.com
* Version: v1.0
*/

class Core
{
	static $_errors = array();
	static $_messages = '';
	
	public static function getValue($key, $defaultValue = false)
	{
	 	if (!isset($key) OR empty($key) OR !is_string($key))
			return false;
		$ret = (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $defaultValue));
		
		if (is_string($ret) === true)
			$ret = urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret)));
		
		if(empty($ret))
			$ret = $defaultValue;
		
		return !is_string($ret)? $ret : stripslashes($ret);
	}
	
	public static function isSubmit($submit)
	{
		return (
			isset($_POST[$submit]) OR isset($_POST[$submit.'_x']) OR isset($_POST[$submit.'_y'])
			OR isset($_GET[$submit]) OR isset($_GET[$submit.'_x']) OR isset($_GET[$submit.'_y'])
		);
	}
	
	public static function encryptPassword($str = NULL)
	{
		return md5(_COOKIE_KEY_.$str);
	}
	
	public static function encrypt($str = NULL)
	{
		if($str == NULL)
			return false;
		
		$compressed = base64_encode(gzcompress($str));
		return $compressed;
	}
	
	public static function decrypt($encrypted = NULL)
	{
		if($encrypted == NULL)
			return false;
		
		$uncompressed = gzuncompress(base64_decode($encrypted));
		return $uncompressed;
	}
	
	public static function encode($str = NULL)
	{
		return self::encrypt($str);
	}
	
	public static function decode($encoded = NULL)
	{
		return self::decrypt($encoded);
	}
	
	public static function truncate($str = '', $maxLen = 50, $suffix = '...')
	{
		$str = strip_tags($str);
		
	 	if (strlen($str) <= $maxLen)
	 		return $str;
	 	$str = utf8_decode($str);
	 	return (utf8_encode(substr($str, 0, $maxLen - strlen($suffix)).$suffix));
	}
	
	public static function redirect($url){
		if(strpos($url, 'http')===0)
			header("Location: ".$url);
		else
			header("Location: "._BASE_URL_.$url);
		exit;
	}
	
	public static function formatCurrency($price, $decimal = 2, $decimal_sep = '.', $thousand_sep = ',', $format_type = NULL){
		if($price=='' || empty($price))
			$price = 0;
		
		if (!is_numeric($price))
			return $price;
		
		$format_type = _CURRENCY_TYPE_;
		$symbol = _CURRENCY_SYMB_;
		$blank = _CURRENCY_GAP_;
		
		$c_decimals = $decimal;
		/*$tmp_p = (string)($price);
		if(!strpos($tmp_p, $decimal_sep))
			$c_decimals = 0;*/
		
		$formatted = '';
		$formatted_price = number_format($price, $c_decimals, $decimal_sep, $thousand_sep);
		switch($format_type){
			case 1: // for US Doller like: $ x,xxx.xx
				$formatted = sprintf('%s %s', $symbol, $formatted_price); // like $ 2,000.25
				break;
			case 2: // for Euro format like: x,xxx.xx $
				$formatted = sprintf('%s %s', $formatted_price, $symbol); // like 2,000.25 $
				break;
		}
		
		return $formatted;
	}
	
	public static function isEmpty($keys=NULL){
		if($keys != NULL){			
			if(is_array($keys)){
				foreach($keys as $key){
					if(empty($key) || $key=='')
						return true;
				}
			} else if(is_object($keys)){
				foreach($keys as $key){
					if(empty($key) || $key=='')
						return true;
				}
			} else if(empty($keys) || $key==''){
				return true;
			}
			return false;
		} else {
			return true;
		}
	}
	
	public static function getRootPath($filepath = ''){
		return _ROOT_DIR_.$filepath;
	}
	
	public static function fileExists($file_path = ''){
		if(empty($file_path))
			return false;
		
		if(file_exists(self::getRootPath($file_path)))
			return true;
		return false;
	}
	
	public static function existsInArray($needle, array $haystack)
	{
		if(is_array($needle)){
			foreach($needle as $need)
			{
				if(in_array($need, $haystack))
					return true;
			}
		} else {
			return (in_array($need, $haystack));
		}
		return false;
	}
	
	public static function isEmail($email)
	{
		return empty($email) OR preg_match('/^[a-z0-9!#$%&\'*+\/=?^`{}|~_-]+[.a-z0-9!#$%&\'*+\/=?^`{}|~_-]*@[a-z0-9]+[._a-z0-9-]*\.[a-z0-9]+$/ui', $email);
	}
	
	public static function isMd5($md5)
	{
		return preg_match('/^[a-f0-9A-F]{32}$/', $md5);
	}
	
	public static function isSha1($sha1)
	{
		return preg_match('/^[a-fA-F0-9]{40}$/', $sha1);
	}
	
	public static function isPhoneNumber($number)
	{
		return preg_match('/^[+0-9. ()-]*$/', $number);
	}
	
	public static function isPostCode($postcode)
	{
		return empty($postcode) OR preg_match('/^[a-zA-Z 0-9-]+$/', $postcode);
	}
	
	public static function isColor($color)
	{
		return preg_match('/^(#[0-9a-fA-F]{6}|[a-zA-Z0-9-]*)$/', $color);
	}
	
	public static function nlbr($string = ''){
		$order   = array('\r\n', '\n', '\r');
		$replace = '<br />';
		return stripslashes(str_replace($order, $replace, $string));
	}
	
	public static function sendMail($template = NULL, $templateVars = array(), $subject = NULL, $to = NULL, $toName = NULL, $from = NULL, $fromName = NULL, $lang = 'en')
	{
		$templatePath = _MAIL_DIR_.$lang.'/';
		
		$message = '';
		$temp_vars = array(
						'site_name' => _SITE_NAME_,
						'base_url' => _BASE_URL_,
						'subject' => $subject
					);
		
		$temp_vars = array_merge($temp_vars, $templateVars);
		
		$temp_array = array();
		foreach($temp_vars as $key=>$value){
			$temp_array['{'.$key.'}'] = $value;
		}
		
		$template = str_replace('.tpl', '', $template);
		if(file_exists($templatePath.$template.'.tpl')){
			$template = file_get_contents($templatePath.$template.'.tpl');
			$message = str_replace(array_keys($temp_array), array_values($temp_array), $template);
		}
		//echo $message; exit;
		
		$mail = new Mail($to, $toName, $from, $fromName, $subject, $message);
	
		if(!$mail->Send()){
			self::displayError($mail->error);
			return false;
		} else {
			return true;
		}
	}
	
	public static function throwError($error = NULL)
	{
		die($error);
	}
	
	public static function displayError($error = NULL)
	{
		self::$_errors[] = $error;
	}
	
	public static function displayMessage($message = NULL)
	{
		self::$_messages = $message;
	}
	
	public static function getErrors(){
		$errors = self::$_errors;
		
		$_html = '';
		if(count($errors)){
			if(count($errors)==1 && isset($errors[0])){
				$_html .= '<div class="error">';
				$_html .= $errors[0];
				$_html .= '</div>';
			} else {
				$_html .= '<ol class="error" type="1">';
				foreach($errors as $error){
					$_html .= '<li>'.$error.'</li>';
				}
				$_html .= '</ol>';
			}
		}
		return $_html;
	}
	
	public static function getMessages(){
		$messages = self::$_messages;
				
		$_html = '';
		if(!empty($messages)){
			$_html .= '<div class="message">';
			$_html .= $messages;
			$_html .= '</div>';
		}
		return $_html;
	}
	
	public static function getImageDataSrc($filename='', $width=0, $height=0, $type=''){
		if(!empty($filename))
		{
			if(self::fileExists($filename))
			{
				$filepath = self::getRootPath($filename);
				$image = new Image();
				$image->load($filepath);
				if($width || $height)
					$image->resize($width, $height);
				return $image->imageOutput($type);
			}
		} else {
			return false;
		}
	}
}

?>