<?php

class WebHits
{
	public $hit_id;
	public $hit_count = 0;
	public $hit_on;
	public $ip;
	public $first_visit;
	public $last_visit;
	private $table;
	
	public function AddHit($type='web')
	{
		$this->table = web_hits;
		
		$ip = $this->getIP();
		
		if($this->ipExists($ip, $this->table))
		{
			$data = array(
					'hit_count' => $this->hit_count + 1,
					'ip' => $ip,
					'last_visit' => date("Y-m-d H:i:s")
				);
			
			Db::getInstance()->autoExecute($this->table, $data, 'UPDATE', 'hit_id="'.$this->hit_id.'"');
			 $this->hit_count =  $this->hit_count + 1;
		}
		else 
		{
			$data = array(
					'hit_count' => $this->hit_count + 1,
					'hit_on' => $type,
					'ip' => $ip,
					'first_visit' => date("Y-m-d H:i:s"),
					'last_visit' => date("Y-m-d H:i:s")
				);
				
			Db::getInstance()->autoExecute($this->table, $data, 'INSERT');
			$this->hit_count =  $this->hit_count + 1;
		}
		
	}
	
	public function ipExists($ip)
	{
		$date = date('Y-m-d');
		$exist = Db::getInstance()->getRow('SELECT * FROM '.$this->table.' WHERE ip = \''.$ip.'\' AND first_visit like "'.$date.'%"');
		if($exist)
			foreach($exist as $key=>$value)
				if (array_key_exists($key, $this))
					$this->{$key} = $value;

		return $exist;
	}
	
	public function getIP() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		
		return $ipaddress;
	}
	
}

?>