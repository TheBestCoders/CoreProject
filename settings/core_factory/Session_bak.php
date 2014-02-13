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
		
		$this->update();
	}
	
	
	public function update()
	{
		if(isset($_SESSION[$this->_name]))
		{
			$this->_content = $_SESSION[$this->_name];
		} 
		if(!$this->_content->date_add)
			$this->_content->date_add = date('Y-m-d H:i:s');
		$this->write();
	}
	
	public function __isset($key)
	{
		return isset($this->_content->$key);
	}
	
	public function __get($key)
	{
		return isset($this->_content->$key) ? $this->_content->$key : false;
	}
	
	public function __set($key, $value)
	{
		$sess_data = '';
		if(is_array($value)){
			foreach($value as $x => $y){
				if(is_array($x)){
					$sess_data[$x] = array();
					foreach($x as $k => $v){
						$sess_data[$x][$k] = $v;
					}
				} else {
					$sess_data[$x] = $y;
				}
			}
		}
		
		$this->_content->$key = $sess_data;
		$this->write();
	}
	
	public function __unset($key)
	{
		unset($this->_content->$key);
		$this->write();
	}
	
	public function set($key, $value = NULL)
	{
		if(is_array($key)){
			foreach($key as $a => $b){
				$this->_content->$a = $b;
			}
		} else {
			if(is_array($value)){
				$this->_content->$key = array();
				foreach($value as $x => $y){
					if(is_array($x)){
						$this->_content->$key[$x] = array();
						foreach($x as $k => $v){
							$this->_content->$key[$x][$k] = $v;
						}
					} else {
						$this->_content->$key[$x] = $y;
					}
				}
			} else {
				$this->_content->$key = $value;
			}
		}
		
		$this->write();
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