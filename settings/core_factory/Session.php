<?php

class Session
{
	protected $_id;
	protected $_name;
	protected $_content;
	
	public function __construct()
	{
		$this->_id = session_id();
		$this->_name = _SESSION_KEY_;
		$this->_content = (object)array();
		
		$this->updateContent();
	}
	
	
	public function updateContent()
	{
		if(isset($_SESSION[$this->_name]))
		{
			$this->_content = $_SESSION[$this->_name];
		} 
		if(!isset($this->_content->date_add))
			$this->_content->date_add = date('Y-m-d H:i:s');
		$this->write();
	}
	
	public function __get($key)
	{
		return isset($this->_content->$key) ? $this->_content->$key : false;
	}
	
	public function __set($key, $value)
	{
		$this->_content->$key = $value;
		$this->write();
	}
	
	public function __isset($key)
	{
		return isset($this->_content->$key);
	}
	
	public function unsetData($key = NULL, $trail = array())
	{
		if($key == NULL)
			return false;
			
		if(is_string($trail))
			$trail = array($trail => '');
		
		if(count($trail) > 0){
			foreach($trail as $k => $v){
				if(!empty($v))
					unset($this->_content->{$key}[$k][$v]);
				else
					unset($this->_content->{$key}[$k]);
			}
		} else {
			unset($this->_content->{$key});
		}
		
		$this->write();
	}
	
	public function setData($key, $value = NULL)
	{
		if(!empty($key)){
			if(is_array($key)){
				foreach($key as $a => $b){
					$this->_content->$a = $b;
				}
			} else {
				$this->_content->$key = $value;
			}
			
			$this->write();
		}
	}
	
	public function updateData($key, $value = NULL)
	{
		if(!empty($key) && is_string($key) && isset($this->_content->$key))
		{
			if(is_array($this->_content->$key) && is_array($value)){
				$content = $this->_content->$key;
				$value = array_merge($content, $value);
				$this->_content->$key = $value;
			}
			elseif(is_string($this->_content->$key) && is_string($value))
			{
				$this->_content->$key = $value;
			}
		} else {
			$this->setData($key, $value);
		}
	}
	
	private function write()
	{
		$this->_content->last_modified = date('Y-m-d H:i:s');
		$_SESSION[$this->_name] = $this->_content;
	}
	
	public function getId()
	{
		return $this->_id;
	}
	
}

?>