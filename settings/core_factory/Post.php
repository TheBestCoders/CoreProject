<?php

class Post
{	
	public function __construct()
	{
		if(isset($_REQUEST) && !empty($_REQUEST)){
			foreach($_REQUEST as $data => $value)
			{
				$this->{$data} = $value;
			}
		} else {
			return false;
		}
	}
	
	public function __isset($key)
	{
		return isset($this->$key);
	}
	
	public function __get($key)
	{
		return isset($this->$key) ? $this->$key : false;
	}
	
	public function __set($key, $value)
	{
		if(is_array($value)){
			$this->$key = (object)array();
			foreach($value as $x => $y){
				if(is_array($x)){
					$this->$key->$x = (object)array();
					foreach($x as $k => $v){
						$this->$key->$x->$k = $v;
					}
				} else {
					$this->$key->$x = $y;
				}
			}
		} else {
			$this->$key = $value;
		}
	}
	
	public function __unset($key)
	{
		unset($this->$key);
	}
	
}

?>