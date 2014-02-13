<?php

abstract class Db
{
	protected $_server;
	protected $_user;
	protected $_password;
	protected $_database;
	protected $_link;
	protected $_result;
	protected $_lastQuery;
	protected static $_db;
	protected static $_instance;
	
	protected static $_servers = array('server' => _DB_SERVER_, 'user' => _DB_USER_, 'password' => _DB_PASSWD_, 'database' => _DB_NAME_);
	
	
	public function __destruct()
	{
		$this->disconnect();
	}
	
	public function __construct($server, $user, $password, $database)
	{
		$this->_server = $server;
		$this->_user = $user;
		$this->_password = $password;
		$this->_database = $database;

		$this->connect();
	}
	
	public static function getInstance()
	{
		if (!isset(self::$_instance)){
			self::$_instance = new MySQL(self::$_servers['server'], self::$_servers['user'], self::$_servers['password'], self::$_servers['database']);
		}	
		return self::$_instance;
	}
	
	public static function escape($value){
		return mysqli_real_escape_string(Db::getInstance()->_link, $value);
	}
	
	abstract public function getServerVersion();
	
	public function getRessource() { return $this->_link;}
	
	abstract public function connect();
	
	abstract public function disconnect();
	
	abstract public function execute($query);
	
	abstract public function select($query);
	
	abstract public function autoSelect($table, $columns=false, $where=false, $limit=false);
	
	abstract public function autoExecute($table, $values, $type, $where = false, $limit = false);
	
	abstract public function getRow($query);
	
	abstract public function getValue($query);

	abstract public function getErrorMsg();
	
	abstract public function getNumberError();
	
	abstract public function insertID();
	
	abstract public function getNumRow();
	
	abstract public function countRow($query);
	
	abstract public function getLastQuery();
	
	abstract public function freeResult();
}