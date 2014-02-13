<?php

class NotFoundController extends CoreController
{
	public $auth = false;
	public $page_title = '404 Page Not Found!';
	
	public function index()
	{
		$this->setData('error', $this->session->error);
	}
}

?>