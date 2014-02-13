<?php

class MySQL extends Db
{

	public function connect()
	{
		if($this->_link = mysqli_connect($this->_server, $this->_user, $this->_password)){
			if (!$this->set_db($this->_database))
				die('The database selection cannot be made.');
		} else {
			die('Link to database cannot be established.');
		}
		
		return $this->_link;
	}
	
	public function getServerVersion(){
		return mysqli_get_server_info();
	}
	
	public function set_db($db_name) {
		return mysqli_select_db($this->_link, $db_name);
	}
	
	public function disconnect()
	{
		if ($this->_link)
			@mysqli_close($this->_link);
		$this->_link = false;
	}
	
	public function execute($query)
	{
		$this->_result = false;
		$this->_lastQuery = $query;
		
		if ($this->_link)
		{
			$this->_result = mysqli_query($this->_link, $query);
			
			return $this->_result;
		}
		
		return false;
	}
	
	public function select($query, $limit = 0)
	{
		$this->_result = false;
		$this->_lastQuery = $query;
		
		if($this->_link && $this->_result = mysqli_query($this->_link, $query))
		{
			$resultArray = array();
			$cnt = 0;
			if ($this->_result !== true){
				while ($row = mysqli_fetch_assoc($this->_result)){
					$resultArray[] = $row;
					$cnt++;
					if($limit && $cnt >= $limit)
						break;
				}
			}
			return $resultArray;
		}
		$this->throwError();
		return false;
	}
	
	public function autoSelect($table, $columns=false, $where=false, $limit=false)
	{
		$_columns = '*';
		$_where = '';
		$_limit = '';
		
		if (!sizeof($table))
			return false;
		
		if($columns) $_columns = $columns;
		if($where) $_where = ' WHERE '.$where;
		if($limit) $_limit = ' LIMIT '.$limit;
			
		$query = 'SELECT '.$_columns.' FROM `'.$table.'`'.$_where.''.$_limit;
		
		$this->_lastQuery = $query;
		
		if($this->_link && $this->_result = mysqli_query($this->_link, $query))
		{
			$resultArray = array();
			if ($this->_result !== true)
				while ($row = mysqli_fetch_assoc($this->_result))
					$resultArray[] = $row;
			return $resultArray;
		}
		$this->throwError();
		return false;
	}
	
	public function	autoExecute($table, $values, $type, $where = false, $limit = false)
	{
		if (!sizeof($values) && strtoupper($type) != 'DELETE')
			return true;

		if (strtoupper($type) == 'INSERT')
		{
			$query = 'INSERT INTO `'.$table.'` (';
			foreach ($values AS $key => $value)
				$query .= '`'.$key.'`,';
			$query = rtrim($query, ',').') VALUES (';
			foreach ($values AS $key => $value)
				$query .= '\''.mysqli_real_escape_string($this->_link, $value).'\',';
			$query = rtrim($query, ',').')';
			if ($limit)
				$query .= ' LIMIT '.(int)($limit);
			
			$this->_lastQuery = $query;
			
			if(mysqli_query($this->_link, $query)){
				return true;
			} else {
				$this->throwError();
				return false;
			}
		}
		elseif (strtoupper($type) == 'UPDATE')
		{
			$query = 'UPDATE `'.$table.'` SET ';
			foreach ($values AS $key => $value)
				$query .= '`'.$key.'` = \''.mysqli_real_escape_string($this->_link, $value).'\',';
			$query = rtrim($query, ',');
			if ($where)
				$query .= ' WHERE '.$where;
			if ($limit)
				$query .= ' LIMIT '.(int)($limit);
			
			$this->_lastQuery = $query;
			
			return mysqli_query($this->_link, $query);
		}
		elseif (strtoupper($type) == 'DELETE')
		{
			$query = 'DELETE FROM `'.$table.'` ';
			if ($where)
				$query .= ' WHERE '.$where;
			$this->_lastQuery = $query;
			
			return mysqli_query($this->_link, $query);
		}
		$this->throwError();
		return false;
	}
	
	public function getRow($query)
	{
		$query .= ' LIMIT 1';
		$this->_result = false;
		$this->_lastQuery = $query;
		
		if ($this->_link)
			if ($this->_result = mysqli_query($this->_link, $query))
			{
				$result = mysqli_fetch_assoc($this->_result);
				return $result;
					
			}
		$this->throwError();
		return false;
	}
	
	public function getValue($query)
	{
		$query .= ' LIMIT 1';
		$this->_result = false;
		$this->_lastQuery = $query;
		
		if ($this->_link AND $this->_result = mysqli_query($this->_link, $query) AND is_array($tmpArray = mysqli_fetch_assoc($this->_result)))
		{
			$result =  array_shift($tmpArray);
			return $result;
		}
		$this->throwError();
		return false;
	}
	
	public function getErrorMsg()
	{
		return mysqli_error($this->_link);
	}
	
	public function getNumberError()
	{
		return mysqli_errno($this->_link);
	}
	
	public function insertID()
	{
		return mysqli_insert_id($this->_link);
	}
	
	public function getNumRow()
	{
		return mysqli_num_rows($this->_result);
	}
	
	public function freeResult()
	{
		if($this->_result)
			return mysqli_free_result($this->_result);
		return false;
	}
	
	public function countRow($query)
	{
		$this->_result = false;
		$this->_lastQuery = $query;
		
		if ($this->_link AND $this->_result = mysqli_query($this->_link, $query))
		{
			return $this->getNumRow();
		}
		$this->throwError();
		return false;
	}
	
	public function getLastQuery()
	{
		return $this->_lastQuery;
	}
	
	public function getLastResult()
	{
		if($this->_result)
			return $this->_result;
		return false;
	}
	
	private function throwError()
	{
		if(mysqli_error($this->_link)){
			echo $this->_lastQuery.'<br />';
			die(mysqli_error($this->_link));
		}
	}
	
}   