<?php

class Cookie
{
	protected $_name;
	protected $_content;
	protected $_expire;
	protected $_path;
	
	public function __construct()
	{
		$this->_name = _COOKIE_KEY_;
		$this->_content = array();
		$this->_expire = time() * _COOKIE_EXPIRE_;
		$this->_path = _BASE_URL_;
		
		$this->update();
	}
	
	
	public function update()
	{
		if(isset($_COOKIE[$this->_name]))
		{
			$content = base64_decode($_COOKIE[$this->_name]);
			
			$tmpContent = explode('¤', $content);
			foreach ($tmpContent AS $keyAndValue)
			{
				$tmpContent2 = explode('|', $keyAndValue);
				if(sizeof($tmpContent2) == 2)
					 $this->_content[$tmpContent2[0]] = $tmpContent2[1];
			}
			
			if(!isset($this->_content['date_add']))
				$this->_content['date_add'] = date('Y-m-d H:i:s');
		} 
		else 
		{
			$this->_content['date_add'] = date('Y-m-d H:i:s');
			$this->write();
		}
	}
	
	public function __isset($key)
	{
		return isset($this->_content[$key]);
	}
	
	public function __get($key)
	{
		return isset($this->_content[$key]) ? $this->_content[$key] : false;
	}
	
	public function __set($key, $value)
	{
		if (is_array($value))
			return false;

		if (preg_match('/¤|\|/', $key.$value))
			throw new Exception('Forbidden chars in cookie');
			
		$this->_content[$key] = $value;
		
		$this->write();
	}
	
	public function __unset($key)
	{
		unset($this->_content[$key]);
		$this->write();
	}
	
	public function write()
	{
		$cookie = '';
		
		foreach ($this->_content as $key => $value)
			$cookie .= $key.'|'.$value.'¤';
			
		return $this->_setcookie($cookie);
	}
	
	protected function _setcookie($cookie = NULL)
	{
		if ($cookie)
		{
			$content = base64_encode($cookie);
			$time = $this->_expire;
		}
		else
		{
			$content = 0;
			$time = time() - 1;
		}
		
		return setcookie($this->_name, $content, $time, $this->_path);
	}
	
	public function logout()
	{
		$this->_content = array();
		$this->_setcookie();
		unset($_COOKIE[$this->_name]);
		$this->write();
	}
}

?>