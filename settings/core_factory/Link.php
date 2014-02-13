<?php

class Link
{
	protected $rewrite;
	protected $url;
	
	public function __construct($rewrite = NULL)
	{
		if($rewrite==NULL)
			$rewrite = _FRIENDLY_URL_;
		$this->rewrite = $rewrite; // 1 if rewriting enable
		$this->url = $_SERVER['SCRIPT_NAME'];
	}
	
	public function self($queryString = ''){
		$link = basename($_SERVER['PHP_SELF']).($queryString!='' ? '?'.$queryString : '');
		
		if($this->rewrite){
			$_link = $this->getSlug($link);
		} else {
			$_link = $link;
		}
		
		return _BASE_URL_.$_link;
	}
	
	public function getPageLink($link){
		if($this->rewrite){
			$_link = $this->getSlug($link);
		} else {
			$_link = $link;
		}
		
		return _BASE_URL_.$_link;
	}
	
	public function getAdminPageLink($link){
		//$_link = $this->getSlug($link);
		return _BASE_URL_._ADMIN_DIR_.$link;
	}
	
	public function getImage($link){
		return _IMG_URL_.$link;
	}
	
	public function backLink()
	{
		$selfLink = urlencode($this->self());
		return Core::encrypt($selfLink);
	}
	
	public function getBackLink($back = '')
	{
		$backLink = Core::decrypt($back);
		return urldecode($backLink);
	}
	
	private function getSlug($page)
	{
		$link = preg_replace('/\.php/', '', $page);
		$link = preg_replace('/(\?)?(\&)?(\w+\=)/', '-', $link);
		$link = str_replace('?', '-', $link);
		return $link;
	}
}

?>