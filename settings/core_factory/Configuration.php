<?php

class Configuration{
	private $_values;
	private $_table;
	
	public function __construct()
	{
		$this->_table = config;
		$this->setValues();
	}
	
	private function setValues()
	{
		$this->_values = array();
		
		$values = Db::getInstance()->select('SELECT * FROM '.$this->_table);
		foreach($values as $value)
			$this->_values[$value['name']] = $value['value'];
	}
	
	public function get($name = NULL)
	{
		if(isset($this->_values[$name]))
			return $this->_values[$name];
	}
	
	public function getMultiple($names = array())
	{
		if(!is_array($names))
			return false;
		
		$config = array();
		foreach($names as $name){
			if(isset($this->_values[$name]))
				$config[$name] = $this->_values[$name];
		}
		return $config;
	}
	
	public function exists($name = NULL)
	{
		return isset($this->_values[$name]);
	}
	
	public function update($name = NULL, $value =''){
		if(!$name == NULL){
			if($this->exists($name)){
				$result = Db::getInstance()->autoExecute($this->_table, array('value' => $value), 'UPDATE', 'name = "'.$name.'"');
			} else {
				$result = Db::getInstance()->autoExecute($this->_table, array('name' => $name, 'value' => $value), 'INSERT');
			}
			if($result){
				$this->_values[$name] = $value;
				return true;
			}
			return false;
		}
	}
	
	public function add($name = NULL, $value =''){
		if(!$name == NULL){
			$result = Db::getInstance()->autoExecute($this->_table, array('name' => $name, 'value' => $value), 'INSERT');
			if($result){
				$this->_values[$name] = $value;
				return true;
			}
			return false;
		}
	}
	
	public function delete($name = NULL){
		if(!$name == NULL){
			$result = Db::getInstance()->execute('DELETE FROM '.$this->_table.' WHERE name = "'.$name.'"');
			if($result){
				unset($this->_values[$name]);
				return true;
			}
			return false;
		}
	}
}

?>