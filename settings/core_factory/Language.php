<?php

class Language{
	public $id;
	public $name;
	public $file_name;
	public $languages;
	public $lang_set = false;
	private $cookie;
	
	public function __construct()
	{
		$this->languages = array(
				1 => array(
					'id' => 1,
					'name' => 'en',
					'file' => 'english.php',
				),
				2 => array(
					'id' => 2,
					'name' => 'fr',
					'file' => 'french.php',
				),
			);
		
		$this->cookie = new Cookie();
		
		if(isset($this->cookie->lang_id))
		{
			$this->id = $this->cookie->lang_id;
		} 
		else {
			$this->id = _DEFAULT_LANG_;
		}
		$this->name = $this->languages[$this->id]['name'];
	}
	
	public function getAllLanguages()
	{
		return $this->languages;
	}
	
	public function setLanguage($_lang_id = NULL)
	{
		if($_lang_id == NULL) $_lang_id = $this->id;
		
		if(is_string($_lang_id))
		{
			$_lang_id = array_search_multi($_lang_id, $this->languages);
		}
		
		if(!array_key_exists($_lang_id, $this->languages))
			return false;
		
		if(!isset($this->cookie->lang_id))
		{
			$this->cookie->lang_id = $_lang_id;
			$this->id = $_lang_id;
			$this->name = $this->languages[$this->id]['name'];
		}
		else
		{
			if($_lang_id != $this->id)
			{
				$this->cookie->lang_id = $_lang_id;
				
				$this->id = $_lang_id;
				$this->name = $this->languages[$this->id]['name'];
			}
		}
		
		$page_name = basename($_SERVER['PHP_SELF'], '.php');
		
		$this->loadingLangFile($page_name);
	}
	
	private function loadingLangFile($page_name)
	{
		$this->file_name = $this->languages[$this->id]['file'];
		require_once(_LANG_DIR_.$this->file_name);
		
		$lang_file = _LANG_DIR_.$this->name."/".$page_name.".php";
		if(file_exists($lang_file))
			require_once($lang_file);
			
		$this->lang_set = true;
	}
	
}

?>