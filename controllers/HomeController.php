<?php

class HomeController extends CoreController
{
	public $auth = false;
	public $page_title = 'The wonder';
	
	public function index()
	{
		// assigning variables to View
		$this->setData(
			array(
				'page_data' => 'Alauddin Ansari'
			)
		);
		
		
		
		$this->loadView('home');
		//print_this($this->session);
	}
	
	public function abc()
	{
		echo 'ABC';
	}
	
	public function setMedia()
	{
		$this->page_title = 'Hello World';
	}
	
	// if not called this method here, it automatic load view
	public function loadContent()
	{
		$this->loadView('home');
	}
	
}

?>