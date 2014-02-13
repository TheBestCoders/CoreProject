<?php

class CoreController
{
	// To authenticate user. if true user must be logged in to see this page
	public $auth = false;
	
	// To assign or set page title
	public $page_title;
	public $meta_keyword;
	public $meta_description;
	
	// Database object
	public $db;
	
	// Session object variable.
	public $session;
	
	// Cookie object variable.
	public $cookie;
	
	// To use links
	public $link;
	
	// Language properties
	public $language;
	
	// Currency properties
	public $currency;
	
	// Dynamically assign page name i.e index.php = index
	public $page_name;
	public $controller_name;
	
	// Holds all the post or get data in an object format
	public $post;
	
	// Holds the current timestamp in Y-m-d H:i:s format i.e 2013-05-15 10:55:30
	public $now;
	
	// True if dont want to load header & footer. load only contents
	public $only_content = false;
	
	// Holds all the css files
	protected $css_files = array();
	// User's css
	public $add_css;
	
	// Holds all the javascript files
	protected $js_files = array();
	// User's javascript
	public $add_js;
	
	// Holds all the variables and values assign to this via setData.
	public $data;
	
	private $views = array();
	protected $user_view = array();
	
	public static $inited = false;
	
	public function load($method = 'index')
	{
		$this->init();
		$this->preExecution();
			
		if(in_array($method, get_class_methods($this))){
			$this->$method();
		} else {
			$this->index();
		}
		$this->postProcess();
		$this->assignMedia();
		$this->assignMeta();
		
		if(!$this->only_content)
			$this->loadHeader();
		
		$this->addView();
		
		if(!$this->only_content)
			$this->loadFooter();
		$this->loadViews();
	}
	
	/**
	* Initialize all the environments
	*/
	private function init()
	{
		if(self::$inited)
			return;
		self::$inited = true;
		
		$this->db = Db::getInstance();
		$this->session = new Session();
		//$this->cookie = new Cookie();
		$this->language = new Language();
		$this->link = new Link();
		//$this->currency = new Currency();
		$this->post = new Post();
	}
	
	/**
	* Having some system's process before Execution of page
	* For Core System Only
	*/
	private function preExecution()
	{
		// redirect user to login page if user not logged in
		if($this->auth && !$this->session->user['user_logged_in'])
		{
			Core::redirect($this->link->getPageLink('login.php?back='.$this->link->backLink()));
		}
		
		$this->controller_name = get_class($this);
		$this->page_name = basename(strtolower(str_replace('Controller', '', $this->controller_name)), '.php');
		$this->now = date('Y-m-d H:i:s');
		$this->session->setData('site_data', array('page_name' => $this->page_name));
		
		// setting user's language
		if($lang = Core::getValue('lang'))
		{
			$this->language->setLanguage($lang);
		}
		$this->session->updateData('site_data', 
			array('lang' => array(
					'id' => $this->language->id,
					'name' => $this->language->name
				)
			)
		);
		
		// website hits counter
		if(!$this->session->hits){
			$hit = new WebHits();
			$hit->AddHit();
			$this->session->hits = $hit->hit_count;
		}
		
		
		$this->setData(array(
			'page_name' => $this->page_name,
			'img_url' => _IMG_URL_,
			'css_url' => _CSS_URL_,
			'js_url' => _JS_URL_,
			'base_url' => _BASE_URL_,
			'upload_url' => _UPLOAD_URL_,
			'upload_dir' => _UPLOAD_DIR_,
		));
	}
	
	/**
	* Pre-Process of execution of page level 2
	* For Developer Only
	*/
	public function index()
	{
		/**
		* Developer's process should be defined only in Controller
		*/
	}
	
	/**
	* Post Execution Process
	* Processes for Views
	* For Core System Only
	*/
	private function postProcess()
	{
		// setting language
		if(!$this->language->lang_set)
		{
			$this->language->setLanguage();
		}
		// setting lang id for view
		$this->setData('lang_id', $this->language->id);
		// setting language done
		
	}
	
	/**
	* Assigning media types, css, js and tagss
	* For Core System Only
	*/
	private function assignMedia()
	{
		$this->addCSS(array(
				'style.css',
				'coremenu.css',
			)
		);
		$this->addCSS($this->add_css);
		
		$this->addJS(array(
				'jquery.tools.min.js',
				'coremenu.js',
				'selectedMenu.js',
				'general.js'
			)
		);
		$this->addJS($this->add_js);
	}
	
	/**
	* Assigning Meta tags
	* For Core System Only
	*/
	private function assignMeta()
	{
		if(empty($this->page_title))
			$this->page_title = _SITE_NAME_;
		
		if(empty($this->meta_keyword))
			$this->meta_keyword = _SITE_NAME_;
		
		if(empty($this->meta_description))
			$this->meta_description = _SITE_NAME_;
		
		$this->setData(array(
			'meta_title' => $this->page_title,
			'meta_keyword' => $this->meta_keyword,
			'meta_description' => $this->meta_description,
		));
	}
	
	/**
	* Loading header of the page
	* For Core System Only
	*/
	private function loadHeader()
	{
		$css = array_unique($this->css_files);
		$js = array_unique($this->js_files);
		
		if(_CACHE_CSS_){
			$css_file = 'q[]='.implode('&q[]=', $css);
			$css_file = preg_replace('/.css/', '', $css_file);
			$css = array(_CSS_URL_.'index.css?'.$css_file);
		}
		
		$this->setData(array(
			'css_files' => $css,
			'js_files' => $js,
		));
		
		$this->addContent('header.php');
	}
	
	/**
	* Loading content part of the page from views
	* For Developer's only
	*/
	public function loadView($view, $data=NULL)
	{
		$this->user_view[] = array('page'=>$view, 'data'=>$data);
	}
	
	/**
	* Loading content part of the page from views
	* For Core System Only
	*/
	private function addView()
	{
		// To load the corresponding view if not defined by the developer
		if(!empty($this->user_view)){
			foreach($this->user_view as $view){
				$this->addContent($view['page'], $view['data']);
			}
		} else {
			$this->addContent();
		}
	}
	
	/**
	* Loading footer of the page
	* For Core System Only
	*/
	private function loadFooter()
	{
		$data = array(
			'year' => date('Y')
		);
		
		$this->addContent('footer.php', $data);
	}
	
	/**
	* For Core System Only
	*/
	private function addCSS($css_file, $media_type = 'all')
	{
		$css_uri = _CSS_URL_;
		
		if(is_array($css_file) && count($css_file))
		{
			foreach($css_file as $css)
			{
				$this->addCss($css);
			}
			return true;
		}
		
		if(Core::fileExists(_CSS_.$css_file)){
			if(_CACHE_CSS_){
				$this->css_files[] = $css_file;
			} else {
				$this->css_files[] = $css_uri.$css_file;
			}
			return true;
		}
		return false;
	}
	
	/**
	* For Core System Only
	*/
	private function addJS($js_file)
	{
		$js_uri = _JS_URL_;
		
		if(is_array($js_file) && count($js_file))
		{
			foreach($js_file as $js)
			{
				$this->addJS($js);
			}
			return true;
		}
		
		if(Core::fileExists(_JS_.$js_file)){
			$js = $js_uri.$js_file;
			$this->js_files[] = $js;
			return true;
		}
		return false;
		
	}
	
	public function addContent($page = NULL, $core_data = NULL)
	{
		if($page==NULL){
			$page = strtolower($this->page_name);
		}
		$page = str_replace('.php', '', $page).'.php';
		
		if(Core::fileExists(_TPL_.$page)){
			$this->views[] = array('page'=>$page, 'data'=>$core_data);
		} else {
			Core::displayError('View not found! ('.$page.')');
		}
		
	}
	
	/**
	* For Core System Only
	*/
	private function loadViews()
	{
		if($this->views && !empty($this->views)){
			foreach($this->views as $core_page){
				$page = $core_page['page'];
				$core_data = $core_page['data'];
				if($core_data && is_array($core_data)){
					foreach($core_data as $key => $value)
					{
						${$key} = $value;
					}
				}
				elseif(count($this->data))
				{
					foreach($this->data as $data => $value)
					{
						${$data} = $value;
					}
				}
				include_once(_TPL_DIR_.$page);
			}
		}
	}
	
	/**
	* For Core System Only
	*/
	public function setData($key = NULL, $value = NULL)
	{
		if($key)
		{
			if(is_array($key)){
				$d = (object)$this->data;
				foreach($key as $k => $v){
					$d->$k = $v;
				}
				$this->data = $d;
			} else {
				$this->data->$key = $value;
			}
		}
	}
}
?>