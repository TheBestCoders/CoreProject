<?php

class TestController extends CoreController
{
	public $auth = false;
	public $page_title = 'Test Page';
	//public $only_content = true;
	
	public function index()
	{
		print_this($_GET);
		//echo $this->page_name;
		//unset($_SESSION['00102c7749a0d26d598a337a2ce78590']);
		
		/*$arr = array(
			'userid' => 1293,
			'user_name' => 'Alauddin Ansari',
			'user_email' => 'alauddina@soms.in',
			'last_login' => '2013-05-03 16:34:35',
			'test_data' => array('hello' => 'world')
		);
		$this->session->user =  $arr;*/
		//$this->session->set('user', $arr);
		
		//$this->session->unset_data('user', array('test_data' => 'hello'));
		//$this->session->unset_data('user');
		
		//print_this($_SESSION);
		
		$image_src = Core::getImageDataSrc('media/images/mypic-cl.jpg', 200, 100);
		$this->setData('image_src', $image_src);
		
	}
	
	public function abc()
	{
		echo 'abc';
		$this->loadView('home');
	}
	
}

?>