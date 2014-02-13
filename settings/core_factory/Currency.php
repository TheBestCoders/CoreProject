<?php

class Currency{
	public $id;
	public $symbol;
	
	public function __construct()
	{
		$this->id = 1;
		$this->symbol = _CURRENCY_SYMB_;
	}
	
}

?>