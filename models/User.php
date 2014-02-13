<?php

class User{
	public $user_id;
	public $firstname;
	public $lastname;
	public $email;
	public $password;
	public $secure_password;
	public $city;
	public $country;
	public $active;
	public $create_date;
	public $modified_date;
	public $last_login;
	public $table;
	public $error = '';
	
	public function __construct($user_id=NULL){
		$this->table = front_users;
		if($user_id != NULL){
			$this->getUser($user_id);
		}
	}
	
	private function getUser($user_id){
		$this->user_id = $user_id;
		
		$user = Db::getInstance()->getRow('SELECT * FROM '.$this->table.' WHERE user_id = "'.$user_id.'"');
		
		if($user){
			foreach($user as $key=>$value){
				if (array_key_exists($key, $this))
					$this->{$key} = $value;
			}
			return $this;
		} else {
			return false;
		}
	}
	
	public function addNewUser()
	{
		if(!Core::isEmpty(array($this->firstname, $this->lastname, $this->email, $this->password, $this->city, $this->country))){
			$this->secure_password = Core::encryptPassword($this->password);
			
			$user_data = array(
				'firstname' => $this->firstname, 
				'lastname' => $this->lastname, 
				'email' => $this->email, 
				'password' => $this->secure_password, 
				'city' => $this->city, 
				'country' => $this->country, 
				'active' => 1, 
				'create_date' => date('Y-m-d H:i:s'), 
				'modify_date' =>  date('Y-m-d H:i:s'),
				'last_login' =>  date('Y-m-d H:i:s')
			);
			$added = Db::getInstance()->autoExecute($this->table, $user_data, 'INSERT');
			
			if($added){
				$this->user_id = Db::getInstance()->insertID();
				return true;
			}
			else{
				$this->error .= 'Data not inserted!';
				return false;
			}
		} else {
			$this->error .= 'Fields are empty!';
			return false;
		}
	}
	
	public function loginUser($username, $password)
	{
		if(!Core::isEmpty(array($username, $password)))
		{
			$this->secure_password = Core::encryptPassword($password);
			
			$user_id = Db::getInstance()->getValue('SELECT user_id FROM '.$this->table.' WHERE email = "'.$username.'" AND password = "'.$this->secure_password.'" AND active = "1"');
			
			if(!$user_id){
				$this->error .= 'Email or Password are wrong or user doesn\'t exists';
				return false;
			} else {
				$this->getUser($user_id);
				
				Db::getInstance()->autoExecute($this->table, array('last_login'=>date('Y-m-d H:i:s')), 'UPDATE', 'user_id = "'.$user_id.'"');
				return true;
			}
		} else {
			$this->error .= 'Fields are empty!';
			return false;
		}
	}
	
	public function updateInfo($data = array(), $user_id = 0)
	{
		if(!$user_id){
			if($this->user_id)
				$user_id = $this->user_id;
			else
				return false;
		}
		
		return Db::getInstance()->autoExecute($this->table, $data, 'UPDATE', 'user_id = "'.$user_id.'"');
	}
	
	public function updatePassword($c_psw, $n_psw, $user_id=0, $change = 'change')
	{
		if(!$user_id){
			if($this->user_id)
				$user_id = $this->user_id;
			else
				return false;
		}
		
		if($change=='change'){
			$current = Core::encryptPassword($c_psw);
			if($current != Db::getInstance()->getValue('SELECT password FROM '.$this->table.' WHERE user_id = "'.$this->user_id.'"')){
				$this->error .= 'Current password is not matched.';
				return false;
			}
		}
		
		$new = Core::encryptPassword($n_psw);
		
		return Db::getInstance()->autoExecute($this->table, array('password'=>$new), 'UPDATE', 'user_id = "'.$this->user_id.'"');
	}
	
	public function getUserByEmail($email = '')
	{
		if(empty($email))
			return false;
		
		$sql = 'SELECT * FROM '.$this->table.' WHERE email = "'.$email.'"';
		
		$user = Db::getInstance()->getRow($sql);
		
		if($user){
			foreach($user as $key=>$value){
				if (array_key_exists($key, $this))
					$this->{$key} = $value;
			}
			return $this;
		} else {
			return false;
		}
	}
	
	public function emailExist($email)
	{			
		$exist = Db::getInstance()->select('SELECT email FROM '.$this->table.' WHERE email=\''.$email.'\' LIMIT 1');
		return count($exist);
	}
	
}

?>